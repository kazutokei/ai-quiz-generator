<!DOCTYPE html>
<html lang="en">
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
            --color-bg-base: #0d0f1a;
            --color-bg-surface: #141622;
            --color-bg-card: #1b1e30;
            --color-border-subtle: #252840;
            --color-brand-1: #6c63ff;
            --color-brand-2: #a78bfa;
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

        /* Custom Toastify adjustments */
        .toastify {
            background: #1b1e30 !important;
            color: #f1f5f9 !important;
            border: 1px solid #252840 !important;
            border-radius: 14px !important;
            padding: 14px 20px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6), 0 10px 10px -5px rgba(0, 0, 0, 0.4) !important;
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
<body class="bg-bg-base text-slate-200 min-h-screen font-sans selection:bg-brand-2/30">

    <!-- NAVBAR -->
    <nav class="flex items-center @yield('nav_class', 'justify-between py-4 px-8') bg-bg-surface/85 backdrop-blur-md border-b border-border-subtle sticky top-0 z-50">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold bg-gradient-to-br from-brand-1 to-brand-2 text-transparent bg-clip-text tracking-tight hover:opacity-80 transition-opacity">
            Quiz<span class="font-light">AI</span>
        </a>
        @hasSection('nav_extra')
            @yield('nav_extra')
        @else
            <span class="text-xs text-slate-500">Powered by Groq · Llama 3</span>
        @endif
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="text-center py-6 border-t border-border-subtle text-slate-500 text-xs">
        &copy; {{ date('Y') }} QuizAI — AI Quiz Generator
    </footer>

    <!-- Toastify JS CDN -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
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
