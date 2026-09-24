(function () {
        // Delegated so it works regardless of when the markup is rendered,
        // and even when the click lands on the icon inside the button.
        document.addEventListener("click", (e) => {
          const btn = e.target.closest("[data-password-toggle]");
          if (!btn) return;
          const wrap = btn.closest(".relative") || btn.parentElement;
          const input = wrap && wrap.querySelector("input");
          const icon = btn.querySelector("i");
          if (!input) return;

          const show = input.type === "password";
          input.type = show ? "text" : "password";

          if (icon) {
            icon.classList.toggle("ph-eye", !show);
            icon.classList.toggle("ph-eye-slash", show);
          }
          btn.setAttribute("aria-pressed", String(show));
          btn.setAttribute(
            "aria-label",
            show ? "Hide password" : "Show password",
          );

          // Keep the caret where it was for a smooth experience.
          const pos = input.value.length;
          input.focus();
          try {
            input.setSelectionRange(pos, pos);
          } catch (_) {}
        });
      })();

      (function () {
        const input = document.getElementById("avatar-input");
        const preview = document.getElementById("avatar-preview");
        const changeBtn = document.querySelector("[data-avatar-change]");
        const removeBtn = document.querySelector("[data-avatar-remove]");
        if (!input || !preview) return;

        const DEFAULT_SRC = preview.src;
        let objectUrl = null;

        changeBtn?.addEventListener("click", () => input.click());

        input.addEventListener("change", () => {
          const file = input.files && input.files[0];
          if (!file) return;
          if (!file.type.startsWith("image/")) {
            alert("Please choose an image file (PNG, JPG or GIF).");
            input.value = "";
            return;
          }
          if (file.size > 2 * 1024 * 1024) {
            alert("Image is too large. Maximum size is 2 MB.");
            input.value = "";
            return;
          }
          if (objectUrl) URL.revokeObjectURL(objectUrl);
          objectUrl = URL.createObjectURL(file);
          preview.src = objectUrl;
        });

        removeBtn?.addEventListener("click", () => {
          if (objectUrl) {
            URL.revokeObjectURL(objectUrl);
            objectUrl = null;
          }
          preview.src = DEFAULT_SRC;
          input.value = "";
        });
      })();

      (function () {
        const root = document.documentElement;
        const THEME_KEY = "hr-theme";
        const radios = document.querySelectorAll("[data-theme-option]");
        if (!radios.length) return;

        const currentChoice = () => {
          const saved = localStorage.getItem(THEME_KEY);
          return saved === "dark" || saved === "light" ? saved : "system";
        };

        // Reflect the active theme on load
        radios.forEach((r) => {
          r.checked = r.value === currentChoice();
        });

        const applyTheme = (choice) => {
          let isDark;
          if (choice === "system") {
            localStorage.removeItem(THEME_KEY);
            isDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
          } else {
            localStorage.setItem(THEME_KEY, choice);
            isDark = choice === "dark";
          }

          // Atomic swap without transition flicker
          root.classList.add("no-transition");
          root.classList.toggle("dark", isDark);

          // Keep the topbar moon/sun icon in sync
          const icon = document.getElementById("theme-icon");
          if (icon) {
            icon.classList.toggle("ph-sun", isDark);
            icon.classList.toggle("ph-moon", !isDark);
          }

          void root.offsetHeight;
          root.classList.remove("no-transition");

          window.dispatchEvent(
            new CustomEvent("hr:theme-change", {
              detail: { theme: isDark ? "dark" : "light" },
            }),
          );
        };

        radios.forEach((r) =>
          r.addEventListener("change", () => {
            if (r.checked) applyTheme(r.value);
          }),
        );

        // If "System" is active, follow OS changes live
        window
          .matchMedia("(prefers-color-scheme: dark)")
          .addEventListener("change", () => {
            if (currentChoice() === "system") applyTheme("system");
          });
      })();
