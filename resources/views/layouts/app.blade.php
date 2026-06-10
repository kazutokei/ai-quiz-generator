<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'AI Quiz Generator — Upload a PDF and instantly generate a multiple-choice quiz using AI.')">
    <title>@yield('title', 'AI Quiz Generator')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <!-- Toastify CSS CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Inter', sans-serif;
            --color-brand-1: #4f46e5;
            --color-brand-2: #818cf8;
            --color-bg-base: #f8fafc;
            --color-bg-surface: #ffffff;
            --color-bg-card: #ffffff;
            --color-text-main: #0f172a;
            --color-text-muted: #64748b;
            --color-border-subtle: #e2e8f0;
        }

        html.dark {
            --color-brand-1: #6c63ff;
            --color-brand-2: #a78bfa;
            --color-bg-base: #0d0f1a;
            --color-bg-surface: #141622;
            --color-bg-card: #1b1e30;
            --color-text-main: #f1f5f9;
            --color-text-muted: #94a3b8;
            --color-border-subtle: #252840;
        }

        .spinner {
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            display: none;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* Custom Toastify adjustments */
        .toastify {
            background: var(--color-bg-card) !important;
            color: var(--color-text-main) !important;
            border: 1px solid var(--color-border-subtle) !important;
            border-radius: 14px !important;
            padding: 14px 20px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 14px !important;
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            max-width: 380px !important;
        }
        
        @yield('custom_styles')
    </style>
</head>
<body class="bg-bg-base text-text-main min-h-screen font-sans selection:bg-brand-2/30 transition-colors duration-300">

    <!-- NAVBAR -->
    <nav class="flex items-center @yield('nav_class', 'justify-between py-4 px-8') bg-bg-surface border-b border-border-subtle sticky top-0 z-50">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group hover:opacity-80 transition-opacity">
            <div class="text-brand-1 group-hover:text-brand-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 1.98-3A2.5 2.5 0 0 1 9.5 2Z"/>
                    <path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-1.98-3A2.5 2.5 0 0 0 14.5 2Z"/>
                </svg>
            </div>
            <span class="text-xl font-bold text-brand-1 tracking-tight">QuizR</span>
        </a>
        
        <div class="flex items-center gap-4">
            <button onclick="toggleTheme()" class="p-2 rounded-full hover:bg-border-subtle/50 text-text-muted hover:text-text-main transition-colors">
                <!-- Sun Icon -->
                <svg id="theme-icon-sun" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <!-- Moon Icon -->
                <svg id="theme-icon-moon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            @hasSection('nav_extra')
                @yield('nav_extra')
            @else
                <span class="text-sm font-medium text-text-muted">QuizR Generator</span>
                <div class="w-8 h-8 rounded-full bg-border-subtle flex items-center justify-center text-text-muted border border-border-subtle/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
            @endif
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="text-center py-6 border-t border-border-subtle text-text-muted text-xs flex flex-col gap-1">
        <div>&copy; {{ date('Y') }} QuizR</div>
        <div class="italic opacity-80">"Fueling curiosity, instantly."</div>
    </footer>

    <!-- Toastify JS CDN -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        // Theme initialization
        const htmlRoot = document.getElementById('html-root');
        const sunIcon = document.getElementById('theme-icon-sun');
        const moonIcon = document.getElementById('theme-icon-moon');
        
        let currentTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(currentTheme);

        function toggleTheme() {
            currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', currentTheme);
            applyTheme(currentTheme);
        }

        function applyTheme(theme) {
            if (theme === 'dark') {
                htmlRoot.classList.add('dark');
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            } else {
                htmlRoot.classList.remove('dark');
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        }
        function showNotification(message, type = 'success') {
            let icon = '';
            let borderStyle = '';
            
            if (type === 'success') {
                icon = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`;
                borderStyle = '1px solid rgba(16, 185, 129, 0.3)';
            } else {
                icon = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;
                borderStyle = '1px solid rgba(239, 68, 68, 0.3)';
            }

            Toastify({
                text: `${icon} <span class="font-medium">${message}</span>`,
                escapeMarkup: false,
                duration: 4000,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    border: borderStyle
                }
            }).showToast();
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                showNotification("{{ session('success') }}", 'success');
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showNotification("{{ $error }}", 'error');
                @endforeach
            @endif
        });
    </script>

    @yield('scripts')
</body>
</html>
