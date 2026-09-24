// OTP verification: 6 single-digit boxes with auto-advance, backspace,
// paste-to-fill, a resend countdown, and submit → new-password step.
(function () {
  const wrap = document.querySelector("[data-otp]");
  const form = document.querySelector("[data-otp-form]");
  if (!wrap || !form) return;

  const inputs = Array.from(wrap.querySelectorAll("input"));

  const focusAt = (i) => {
    if (i >= 0 && i < inputs.length) inputs[i].focus();
  };

  inputs.forEach((input, i) => {
    input.addEventListener("input", () => {
      // keep digits only, one char
      input.value = input.value.replace(/\D/g, "").slice(0, 1);
      if (input.value) focusAt(i + 1);
    });

    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !input.value && i > 0) {
        focusAt(i - 1);
      } else if (e.key === "ArrowLeft") {
        focusAt(i - 1);
      } else if (e.key === "ArrowRight") {
        focusAt(i + 1);
      }
    });

    input.addEventListener("paste", (e) => {
      e.preventDefault();
      const digits = (e.clipboardData.getData("text") || "")
        .replace(/\D/g, "")
        .slice(0, inputs.length)
        .split("");
      digits.forEach((d, k) => {
        if (inputs[k]) inputs[k].value = d;
      });
      focusAt(Math.min(digits.length, inputs.length - 1));
    });
  });

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const code = inputs.map((i) => i.value).join("");
    if (code.length < inputs.length) {
      focusAt(inputs.findIndex((i) => !i.value));
      return;
    }
    window.location.href = "new-password.html";
  });

  // ===== Resend countdown =====
  const resendBtn = document.querySelector("[data-otp-resend]");
  const timerEl = document.querySelector("[data-otp-timer]");
  let left = 0;
  let timer = null;

  const tick = () => {
    if (left <= 0) {
      clearInterval(timer);
      if (timerEl) timerEl.textContent = "";
      if (resendBtn) resendBtn.disabled = false;
      return;
    }
    if (timerEl) timerEl.textContent = `(${left}s)`;
    left -= 1;
  };

  const startCountdown = (seconds) => {
    left = seconds;
    if (resendBtn) resendBtn.disabled = true;
    clearInterval(timer);
    tick();
    timer = setInterval(tick, 1000);
  };

  if (resendBtn)
    resendBtn.addEventListener("click", () => {
      inputs.forEach((i) => (i.value = ""));
      focusAt(0);
      startCountdown(30);
    });

  startCountdown(30);
})();
