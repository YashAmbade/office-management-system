<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="@yield('page-description', 'Office Management System')" />
    <title>@yield('title', 'OMS') - OMS Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <script>
        (function() {
            const saved = localStorage.getItem("hr-theme");
            const isDark = saved ===
                "dark"; // ignore browser/system preference — default to light unless the user explicitly toggled dark
            if (isDark) document.documentElement.classList.add("dark");
            if (isDark) document.documentElement.classList.add("dark");

            const sidebarState = localStorage.getItem("hr-sidebar");
            if (sidebarState !== "expanded") {
                document.documentElement.classList.add("sidebar-collapsed");
            }
        })();
    </script>
    <link href="{{ asset('assets/css/index.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @livewireStyles
    @stack('styles')
</head>

<body data-page-title="@yield('title', 'Dashboard')"
    class="bg-bg text-text dark:text-text font-sans antialiased relative bg-gray-main">

    <!-- ====================================================================== -->
    <!-- Mobile Sidebar Overlay                                                  -->
    <!-- ====================================================================== -->
    <div id="mobile-sidebar-overlay"
        class="fixed inset-0 bg-black/50 z-40 hidden opacity-0 transition-opacity duration-300 lg:hidden"
        onclick="closeMobileSidebar()"></div>

    <!-- ====================================================================== -->
    <!-- Mobile Sidebar (drawer)                                                 -->
    <!-- ====================================================================== -->
    <aside id="mobile-sidebar"
        class="fixed top-0 left-0 z-70 h-full w-[280px] shadow-2xl transform -translate-x-full transition-transform duration-300 lg:hidden flex flex-col">
        <div class="h-16 flex items-center justify-between px-4 border-b border-border">
            <a class='flex items-center gap-3' href='index-2.html'>
                <img src="{{ asset('assets/images/midbrains-gold.png') }}" alt="logo" class="h-8 w-full" />
            </a>
            <button type="button" aria-label="Close menu"
                class="w-10 h-10 rounded-xl flex items-center justify-center text-muted hover:bg-white/5"
                onclick="closeMobileSidebar()">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>

        <nav class="flex-1 flex flex-col overflow-y-auto p-4">
            <div class="space-y-4">
                <div class="space-y-1">
                    <p class="nav-section">Main</p>
                    <a class="mobile-nav-item flex items-center gap-3 px-4 py-3 rounded-xl opacity-40 pointer-events-none cursor-not-allowed"
                        data-page='index' href="#"><i class="ph ph-squares-four text-xl"></i><span
                            class="font-medium">Dashboard</span></a>
                </div>

                @include('partials.sidebar-nav')

                <div class="space-y-1" style="display: none;">
                    <p class="nav-section">CRM &amp; Support</p>
                    <a class="mobile-nav-item flex items-center gap-3 px-4 py-3 rounded-xl opacity-40 pointer-events-none cursor-not-allowed"
                        data-page='clients' href="#"><i class="ph ph-handshake text-xl"></i><span
                            class="font-medium">Clients</span></a>
                    <a class="mobile-nav-item flex items-center gap-3 px-4 py-3 rounded-xl opacity-40 pointer-events-none cursor-not-allowed"
                        data-page='clients-details' href="#"><i
                            class="ph ph-identification-card text-xl"></i><span class="font-medium">Client
                            Details</span></a>
                    <a class="mobile-nav-item flex items-center gap-3 px-4 py-3 rounded-xl opacity-40 pointer-events-none cursor-not-allowed"
                        data-page='tickets' href="#"><i class="ph ph-ticket text-xl"></i><span
                            class="font-medium">Tickets</span></a>
                    <a class="mobile-nav-item flex items-center gap-3 px-4 py-3 rounded-xl opacity-40 pointer-events-none cursor-not-allowed"
                        data-page='ticket-details' href="#"><i class="ph ph-note text-xl"></i><span
                            class="font-medium">Ticket Details</span></a>
                </div>


            </div>
            <div class="mt-auto space-y-1 pt-3">
                <p class="nav-section">System</p>
                <a class="mobile-nav-item flex items-center gap-3 px-4 py-3 rounded-xl" data-page='settings'
                    href="{{ route('settings.index') }}" @if (request()->routeIs('settings.*')) data-active="true" @endif><i
                        class="ph ph-gear text-xl"></i><span class="font-medium">Settings</span></a>
                <a class="mobile-nav-item flex items-center gap-3 px-4 py-3 rounded-xl opacity-40 pointer-events-none cursor-not-allowed"
                    data-page='help' href="#"><i class="ph ph-lifebuoy text-xl"></i><span
                        class="font-medium">Help</span></a>
            </div>
        </nav>

        <div class="p-4 border-t border-border flex items-center gap-3">
            <span
                class="w-9 h-9 rounded-full bg-primary/10 text-primary text-xs font-semibold flex items-center justify-center flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-muted truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </aside>


    <aside id="sidebar"
        class="fixed top-0 left-0 z-40 h-full w-64 border-r hidden lg:flex flex-col transition-all duration-300">
        <div class="h-16 flex items-center justify-between px-4 border-b border-border-subtle">
            <a class='flex items-center gap-3 overflow-hidden' href='index-2.html'>
                <img src="{{ asset('assets/images/midbrains-gold.png') }}" alt="logo"
                    class="h-8 w-full logo-sidebar" />
            </a>
            <button id="sidebar-toggle"
                class="sidebar-toggle-btn w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:text-white hover:bg-white/5 transition-colors flex-shrink-0"
                aria-label="Toggle sidebar">
                <i class="ph ph-sidebar-simple text-lg"></i>
            </button>
        </div>

        <nav class="flex-1 flex flex-col overflow-y-auto py-3 px-3">
            <div class="space-y-4">
                @include('partials.sidebar-nav')

            </div>

            @can('manage', \App\Models\MenuPermission::class)
                <p class="nav-section nav-text">Permissions</p>
                <a class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all" data-page='departments'
                    href="{{ route('settings.permissions') }}"
                    @if (request()->routeIs('settings.permissions')) data-active="true" @endif><i
                        class="ph ph-key text-2xl flex-shrink-0"></i><span
                        class="nav-text font-medium whitespace-nowrap">Permissions</span></a>
            @endcan

            @can('manage', \App\Models\MenuPermission::class)
                <a class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all"
                    href="{{ route('settings.menu-items') }}" @if (request()->routeIs('settings.menu-items')) data-active="true" @endif>
                    <i class="ph ph-list-bullets text-2xl flex-shrink-0"></i>
                    <span class="nav-text font-medium whitespace-nowrap">Menu Items</span>
                </a>
            @endcan

            <div class="mt-auto space-y-1 pt-3">
                <p class="nav-section nav-text">System</p>
                <a class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all" data-page='settings'
                    href="{{ route('settings.index') }}"
                    @if (request()->routeIs('settings.*')) data-active="true" @endif><i
                        class="ph ph-gear text-2xl flex-shrink-0"></i><span
                        class="nav-text font-medium whitespace-nowrap">Settings</span></a>
                <a class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all opacity-40 pointer-events-none cursor-not-allowed"
                    data-page='help' href="#"><i class="ph ph-lifebuoy text-2xl flex-shrink-0"></i><span
                        class="nav-text font-medium whitespace-nowrap">Help</span></a>
            </div>
        </nav>

        <div class="px-3 py-3 border-t border-border-subtle" x-data="{ open: false }">
            <div x-on:click="open = !open" x-on:click.outside="open = false"
                class="relative flex items-center gap-3 rounded-xl p-2 hover:bg-white/5 transition-colors cursor-pointer">
                <div class="relative flex-shrink-0">
                    <span
                        class="w-9 h-9 rounded-full bg-primary/10 text-primary text-xs font-semibold flex items-center justify-center">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                    <span
                        class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-primary border-2 border-sidebar"></span>
                </div>
                <div class="nav-text min-w-0 flex-1 leading-tight">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-muted truncate">{{ auth()->user()->email }}</p>
                </div>
                <i class="ph ph-dots-three-vertical nav-text text-muted flex-shrink-0"></i>

                <div x-show="open" x-cloak x-transition x-on:click.stop
                    class="absolute bottom-full left-2 w-56 mb-2 rounded-xl border border-border bg-surface shadow-lg py-1 z-50">
                    <a href="{{ route('settings.index') }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm text-text hover:bg-subtle transition-colors whitespace-nowrap">
                        <i class="ph ph-gear"></i>Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2 px-3 py-2 text-sm text-danger hover:bg-danger-soft transition-colors">
                            <i class="ph ph-sign-out"></i>Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>


    <header id="topbar" data-scrolled="false"
        class="fixed top-0 right-0 z-45 h-16 bg-white/70 dark:bg-w1/60 backdrop-blur-xl border-b border-border-subtle left-0 lg:left-64 transition-all duration-300 data-[scrolled=true]:shadow-sm">
        <div class="flex items-center justify-between h-full gap-2 px-3 sm:px-4 lg:px-6">
            <!-- Left: menu + title -->
            <div class="flex items-center gap-2 min-w-0">
                <button type="button"
                    class="sidebar-toggle-btn w-9 h-9 rounded-lg flex lg:hidden! items-center justify-center text-muted transition-colors flex-shrink-0"
                    aria-label="Toggle sidebar">
                    <i class="ph ph-list text-xl"></i>
                </button>
                <h1 id="page-title" class="hidden sm:block text-lg sm:text-xl font-bold text-heading truncate">
                    Dashboard
                </h1>
            </div>

            <!-- Right -->
            <div class="flex items-center gap-1.5 sm:gap-2">

                <!-- Search (mobile icon) -->
                <button id="mobile-search-btn" class="topbar-icon-btn flex md:hidden! w-9 h-9 border border-border"
                    aria-label="Search" aria-expanded="false">
                    <i class="ph ph-magnifying-glass text-lg"></i>
                </button>

                <!-- Notifications -->
                @livewire('notification-bell')

                <!-- Theme Toggle -->
                {{-- <button id="theme-toggle" class="topbar-icon-btn w-9 h-9 sm:w-10 sm:h-10 border border-border bs-buttons"
                    aria-label="Toggle theme">
                    <i id="theme-icon" class="ph ph-moon text-lg sm:text-xl"></i>
                </button> --}}


                <!-- Share -->
                <button type="button" data-modal-open="share-modal"
                    class="inline-flex items-center gap-1.5 h-9 sm:h-10 px-3 sm:px-4 rounded-xl bg-gold text-white text-sm font-medium hover:bg-primary-strong transition-colors bs-buttons">
                    <i class="ph ph-export text-base"></i>
                    <span class="hidden sm:inline">Share</span>
                </button>
            </div>
        </div>

        <!-- Mobile search panel -->
        <div id="mobile-search-panel"
            class="md:hidden absolute left-0 right-0 top-full px-4 pb-3 pt-1 origin-top -translate-y-2 opacity-0 pointer-events-none transition-all duration-300 ease-out">
            <div
                class="topbar-search relative flex items-center h-11 px-3 rounded-xl bg-w1 border border-border shadow-lg group">
                <i
                    class="ph ph-magnifying-glass text-faint text-lg group-focus-within:text-primary transition-colors flex-shrink-0"></i>
                <input id="mobile-search-input" type="text" placeholder="Search..."
                    class="flex-1 min-w-0 h-full bg-transparent px-2.5 text-sm text-text placeholder:text-faint focus:outline-none" />
                <button id="mobile-search-close" type="button"
                    class="w-7 h-7 rounded-md flex items-center justify-center text-muted hover:text-heading hover:bg-subtle transition-colors flex-shrink-0"
                    aria-label="Close search">
                    <i class="ph ph-x"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- ====================================================================== -->
    <!-- Main Content                                                            -->
    <!-- ====================================================================== -->
    <main class="pt-16 lg:pl-64 transition-all duration-300 min-h-screen" id="main-content">
        <div class="p-4 sm:p-6 ">
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('js/theme-and-ui.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.all.min.js"></script>
    <script>
        window.confirmStatusChange = function(targetLabel, {
            needsReason = false,
            isReopen = false
        } = {}) {
            const options = {
                title: isReopen ? `Reopen this task as "${targetLabel}"?` : `Move to "${targetLabel}"?`,
                text: isReopen ?
                    'This task was already marked Completed. Reopening it will be logged in the activity history.' :
                    undefined,
                icon: isReopen ? 'warning' : undefined,
                showCancelButton: true,
                confirmButtonText: isReopen ? 'Yes, reopen it' : 'Confirm',
                confirmButtonColor: isReopen ? '#d45656' : undefined,
                cancelButtonText: 'Cancel',
            };

            if (needsReason) {
                options.input = 'textarea';
                options.inputLabel = 'Reason';
                options.inputPlaceholder = 'Please provide a reason…';
                options.inputValidator = (value) => !value && 'A reason is required.';
            }

            return Swal.fire(options).then((result) => {
                if (!result.isConfirmed) return false;
                return needsReason ? (result.value || false) : true;
            });
        };
    </script>
    <script>
        window.confirmSendBack = function(currentDueDate) {
            return Swal.fire({
                title: '',
                html: `
            <div class="send-back-modal">

                <!-- Header -->
                <div class="send-back-header">
                    <div class="send-back-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 14 4 9l5-5"/>
                            <path d="M4 9h10a6 6 0 0 1 6 6v1"/>
                        </svg>
                    </div>

                    <div>
                        <div class="send-back-title">Send Back for Changes</div>
                        <div class="send-back-subtitle">
                            Return this task to the previous stage for revision.
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="form-section">
                    <label class="field-label">
                        Reason <span class="required">*</span>
                    </label>

                    <select id="swal-reason-type" class="professional-select">
                        <option value="Needs Changes">Needs Changes</option>
                        <option value="Missing Information">Missing Information</option>
                        <option value="Quality Issue">Quality Issue</option>
                        <option value="Incorrect Content">Incorrect Content</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="form-section">
                    <label class="field-label">
                        Description
                        <span class="optional">Optional</span>
                    </label>

                    <textarea
                        id="swal-reason-desc"
                        class="professional-textarea"
                        placeholder="Briefly explain what needs to be changed..."
                        rows="3"
                    ></textarea>
                </div>

                <!-- Due Date -->
                <div class="form-section">
                    <label class="field-label">
                        New Due Date <span class="required">*</span>
                    </label>

                    <div class="date-input-wrapper">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>

                        <input
                            type="datetime-local"
                            id="swal-new-due-date"
                            value="${currentDueDate || ''}"
                        />
                    </div>
                </div>

                <!-- Notice -->
                <div class="priority-notice">
                    <div class="priority-notice-icon">
                        !
                    </div>
                    <div>
                        <div class="priority-notice-title">High Priority</div>
                        <div class="priority-notice-text">
                            This task will automatically be marked as high priority.
                        </div>
                    </div>
                </div>

            </div>

            <style>
                .send-back-modal {
                    text-align: left;
                    padding: 2px 2px 0;
                    color: #1f2937;
                }

                .send-back-header {
                    display: flex;
                    align-items: center;
                    gap: 13px;
                    padding-bottom: 18px;
                    margin-bottom: 4px;
                    border-bottom: 1px solid #edf0f2;
                }

                .send-back-icon {
                    width: 42px;
                    height: 42px;
                    min-width: 42px;
                    border-radius: 11px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #fff3f3;
                    color: #d45656;
                }

                .send-back-title {
                    font-size: 17px;
                    font-weight: 650;
                    line-height: 1.3;
                    color: #1f2937;
                }

                .send-back-subtitle {
                    font-size: 12px;
                    color: #7b8491;
                    margin-top: 3px;
                    line-height: 1.45;
                }

                .form-section {
                    margin-top: 16px;
                }

                .field-label {
                    display: flex;
                    align-items: center;
                    gap: 5px;
                    font-size: 12px;
                    font-weight: 600;
                    color: #374151;
                    margin-bottom: 7px;
                }

                .required {
                    color: #d45656;
                }

                .optional {
                    font-size: 10px;
                    font-weight: 500;
                    color: #9ca3af;
                    background: #f3f4f6;
                    padding: 2px 6px;
                    border-radius: 5px;
                }

                .professional-select,
                .professional-textarea,
                .date-input-wrapper input {
                    width: 100%;
                    box-sizing: border-box;
                    border: 1px solid #dfe3e8;
                    background: #fff;
                    border-radius: 8px;
                    color: #374151;
                    font-family: inherit;
                    font-size: 13px;
                    transition: all .15s ease;
                    outline: none;
                }

                .professional-select {
                    height: 40px;
                    padding: 0 11px;
                    cursor: pointer;
                }

                .professional-textarea {
                    display: block;
                    resize: vertical;
                    min-height: 76px;
                    padding: 10px 11px;
                    line-height: 1.45;
                }

                .professional-select:hover,
                .professional-textarea:hover,
                .date-input-wrapper:hover {
                    border-color: #cbd1d8;
                }

                .professional-select:focus,
                .professional-textarea:focus,
                .date-input-wrapper:focus-within {
                    border-color: #9ca7b3;
                    box-shadow: 0 0 0 3px rgba(107, 114, 128, .08);
                }

                .professional-textarea::placeholder {
                    color: #a7adb5;
                }

                .date-input-wrapper {
                    height: 40px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    padding: 0 10px;
                    box-sizing: border-box;
                    border: 1px solid #dfe3e8;
                    background: #fff;
                    border-radius: 8px;
                    transition: all .15s ease;
                }

                .date-input-wrapper svg {
                    flex-shrink: 0;
                    color: #8b95a1;
                }

                .date-input-wrapper input {
                    height: 100%;
                    border: 0;
                    padding: 0;
                    box-shadow: none !important;
                }

                .priority-notice {
                    display: flex;
                    align-items: flex-start;
                    gap: 10px;
                    margin-top: 18px;
                    padding: 11px 12px;
                    border-radius: 8px;
                    background: #fff8ed;
                    border: 1px solid #f5dfbd;
                }

                .priority-notice-icon {
                    width: 19px;
                    height: 19px;
                    min-width: 19px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    background: #e9a23b;
                    color: #fff;
                    font-size: 11px;
                    font-weight: 700;
                    margin-top: 1px;
                }

                .priority-notice-title {
                    font-size: 11px;
                    font-weight: 650;
                    color: #8a5a18;
                    margin-bottom: 2px;
                }

                .priority-notice-text {
                    font-size: 11px;
                    color: #9a7541;
                    line-height: 1.4;
                }

                /* SweetAlert adjustments */
                .swal2-popup.send-back-popup {
                    width: 470px !important;
                    padding: 22px 24px 20px !important;
                    border-radius: 14px !important;
                }

                .swal2-actions {
                    width: 100%;
                    margin-top: 20px !important;
                    gap: 8px;
                }

                .swal2-actions button {
                    font-family: inherit !important;
                    font-size: 13px !important;
                    font-weight: 600 !important;
                    border-radius: 8px !important;
                    height: 40px !important;
                    padding: 0 18px !important;
                    margin: 0 !important;
                }

                .swal2-confirm {
                    box-shadow: none !important;
                }

                .swal2-cancel {
                    background: #f4f5f6 !important;
                    color: #4b5563 !important;
                }

                .swal2-cancel:hover {
                    background: #e9eaec !important;
                }

                @media (max-width: 520px) {
                    .swal2-popup.send-back-popup {
                        width: calc(100% - 24px) !important;
                        padding: 20px !important;
                    }
                }
            </style>
        `,
                customClass: {
                    popup: 'send-back-popup'
                },
                showCancelButton: true,
                confirmButtonText: 'Send Back',
                confirmButtonColor: '#d45656',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusConfirm: false,

                preConfirm: () => {
                    const type = document.getElementById('swal-reason-type').value;
                    const desc = document.getElementById('swal-reason-desc').value.trim();
                    const newDueDate = document.getElementById('swal-new-due-date').value;

                    if (!newDueDate) {
                        Swal.showValidationMessage('Please select a new due date.');
                        return false;
                    }

                    return {
                        reason: desc ? `${type}: ${desc}` : type,
                        dueDate: newDueDate,
                    };
                }

            }).then((result) => {
                return result.isConfirmed ? result.value : false;
            });
        };
    </script>
    @livewireScripts
    @stack('scripts')
    @auth
        @if (auth()->user()->hasRole(['Super Admin', 'Manager']))
            <livewire:expiring-clients-alert />
        @endif
    @endauth
</body>

</html>
