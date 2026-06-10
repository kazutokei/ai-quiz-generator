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
        
        @yield('custom_styles')
    </style>
</head>
<body class="bg-bg-base text-slate-200 min-h-screen font-sans selection:bg-brand-2/30">

    <!-- NAVBAR -->
    <nav class="flex items-center @yield('nav_class', 'justify-between py-4 px-8') bg-bg-surface/85 backdrop-blur-md border-b border-border-subtle sticky top-0 z-50">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold bg-gradient-to-br from-brand-1 to-brand-2 text-transparent bg-clip-text tracking-tight hover:opacity-80 transition-opacity">
            Quiz<span class="font-light">AI</span>
        </a>
        @yield('nav_extra', '<span class="text-xs text-slate-500">Powered by Groq · Llama 3</span>')
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="text-center py-6 border-t border-border-subtle text-slate-500 text-xs">
        &copy; {{ date('Y') }} QuizAI — AI Quiz Generator
    </footer>

    @yield('scripts')
</body>
</html>
