// Sign-in page: password reveal toggle + demo submit that routes to the
// dashboard once the native required fields are valid.
(function () {
  // Password visibility toggle
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

  const form = document.querySelector("[data-signin-form]");
  if (form)
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      window.location.href = "index.html";
    });
})();
