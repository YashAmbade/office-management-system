<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Sign In" />
    <title>Sign In - OMS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <script>
        (function() {
            const saved = localStorage.getItem("hr-theme");
    const isDark = saved === "dark"; // ignore browser/system preference — default to light unless the user explicitly toggled dark
    if (isDark) document.documentElement.classList.add("dark");
            if (isDark) document.documentElement.classList.add("dark");
        })();
    </script>
    <link href="{{ asset('assets/css/index.css') }}" rel="stylesheet">
</head>

<body data-page-title="Sign In" class="bg-bg text-text dark:text-text font-sans antialiased"
    style="background: linear-gradient(90deg, #1CB5E0 0%, #14229b 100%);">
    <main class="min-h-dvh flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="bg-surface border border-border rounded-3xl shadow-xl shadow-black/5 p-6 sm:p-8"
                style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('assets/images/midbrains-blue.png') }}" alt="OMS"
                        class="h-10 object-contain" />
                </div>

                <h2 class="text-xl font-bold text-heading text-center">Welcome back</h2>
                <p class="text-sm text-muted text-center mt-1">Sign in to your account</p>

                @if ($errors->any())
                    <div class="mt-4 rounded-xl bg-danger-soft text-danger text-sm px-3.5 py-2.5">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4 mt-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-medium text-text-secondary mb-1.5">Email</label>
                        <div class="relative">
                            <i class="ph ph-envelope-simple absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                            <input type="email" id="email" name="email" autocomplete="email" required
                                value="{{ old('email') }}" placeholder="jane@company.com"
                                class="w-full h-11 pl-9 pr-3 rounded-xl bg-subtle border border-border text-sm text-text placeholder:text-faint focus:outline-none focus:border-primary transition-colors" />
                        </div>
                    </div>

                   <div>
    <label for="password" class="block text-xs font-medium text-text-secondary mb-1.5">Password</label>
    <div class="relative" style="position: relative;">
        <input type="password" id="password" name="password"
            autocomplete="current-password" required placeholder="Enter your password"
            style="padding-right: 2.5rem;"
            class="w-full h-11 pl-3 rounded-xl bg-subtle border border-border text-sm text-text placeholder:text-faint focus:outline-none focus:border-primary transition-colors" />
        <button type="button" id="togglePassword" aria-label="Show password"
            style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); display: flex; align-items: center; background: none; border: 0; padding: 0; cursor: pointer;"
            class="text-muted hover:text-heading transition-colors">
            <i class="ph ph-eye" id="togglePasswordIcon" style="font-size: 1.125rem;"></i>
        </button>
    </div>
</div>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        icon.className = show ? 'ph ph-eye-slash' : 'ph ph-eye';
        this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
</script>

                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" id="remember" name="remember" class="accent-primary" />
                        <span class="text-xs text-text-secondary">Keep me signed in</span>
                    </label>

                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-1.5 h-11 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
                        Sign in<i class="ph ph-arrow-right text-base"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>
