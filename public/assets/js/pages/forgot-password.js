// Forgot password: validate email, then route to the check-your-email step.
(function () {
  const form = document.querySelector("[data-forgot-form]");
  if (!form) return;
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }
    window.location.href = "check-your-email.html";
  });
})();
