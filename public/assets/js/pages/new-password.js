// New password: reveal toggles, live strength meter, confirm-match check,
// then route to the success page.
(function () {
  document.querySelectorAll("[data-password-toggle]").forEach((btn) => {
    const input = btn.closest(".relative")?.querySelector("input");
    const icon = btn.querySelector("i");
    if (!input) return;
    btn.addEventListener("click", () => {
      const show = input.type === "password";
      input.type = show ? "text" : "password";
      if (icon) {
        icon.classList.toggle("ph-eye", !show);
        icon.classList.toggle("ph-eye-slash", show);
      }
      btn.setAttribute("aria-pressed", String(show));
      btn.setAttribute("aria-label", show ? "Hide password" : "Show password");
    });
  });

  const pw = document.getElementById("password");
  const confirm = document.getElementById("confirm-password");
  const bars = document.querySelectorAll("[data-strength-bars] span");
  const strengthLabel = document.querySelector("[data-strength-label]");
  const matchError = document.querySelector("[data-match-error]");

  const COLORS = ["bg-danger", "bg-[#f59e0b]", "bg-primary", "bg-success"];
  const LABELS = ["Weak password", "Fair password", "Good password", "Strong password"];

  const score = (v) => {
    let s = 0;
    if (v.length >= 8) s++;
    if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
    if (/\d/.test(v)) s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    return s;
  };

  const renderStrength = () => {
    if (!pw || !bars.length) return;
    const v = pw.value;
    const s = v ? score(v) : 0;
    bars.forEach((bar, i) => {
      bar.className = "h-1 flex-1 rounded-full transition-colors";
      bar.classList.add(i < s ? COLORS[Math.min(s, 4) - 1] : "bg-subtle");
    });
    if (strengthLabel) {
      strengthLabel.textContent = v
        ? LABELS[Math.min(s, 4) - 1] || LABELS[0]
        : "Use 8+ characters with letters, numbers & symbols.";
    }
  };
  if (pw) pw.addEventListener("input", renderStrength);

  const checkMatch = () => {
    if (!pw || !confirm) return true;
    const ok = confirm.value === "" || confirm.value === pw.value;
    confirm.setCustomValidity(ok ? "" : "Passwords do not match.");
    if (matchError) matchError.classList.toggle("hidden", ok);
    confirm.classList.toggle("border-danger", !ok);
    return ok;
  };
  if (confirm) confirm.addEventListener("input", checkMatch);
  if (pw) pw.addEventListener("input", checkMatch);

  const form = document.querySelector("[data-newpw-form]");
  if (form)
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      if (!checkMatch()) {
        confirm.focus();
        return;
      }
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      window.location.href = "successfully.html";
    });
})();
