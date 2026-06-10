<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AI Quiz Generator — Upload a PDF and instantly generate a multiple-choice quiz using AI.">
    <title>AI Quiz Generator</title>
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
    </style>
</head>
<body class="bg-bg-base text-slate-200 min-h-screen font-sans selection:bg-brand-2/30">

<!-- NAVBAR -->
<nav class="flex items-center justify-between py-4 px-8 bg-bg-surface/85 backdrop-blur-md border-b border-border-subtle sticky top-0 z-50">
    <div class="text-xl font-bold bg-gradient-to-br from-brand-1 to-brand-2 text-transparent bg-clip-text tracking-tight">
        Quiz<span class="font-light">AI</span>
    </div>
    <span class="text-xs text-slate-500">Powered by Groq · Llama 3</span>
</nav>

<!-- HERO -->
<section class="text-center pt-16 pb-10 px-6">
    <div class="inline-block bg-brand-1/15 border border-brand-1/30 text-brand-2 text-xs font-semibold tracking-wider uppercase px-4 py-1.5 rounded-full mb-6">
        ✦ AI-Powered
    </div>
    <h1 class="text-4xl md:text-5xl font-extrabold leading-tight bg-gradient-to-br from-white via-white to-brand-2 text-transparent bg-clip-text mb-4">
        Turn any PDF into<br>an instant quiz
    </h1>
    <p class="text-slate-400 text-lg max-w-xl mx-auto leading-relaxed">
        Upload a document and our AI generates 15 multiple-choice questions in seconds.
    </p>
</section>

<!-- UPLOAD CARD -->
<div class="max-w-2xl mx-auto mb-14 px-6">
    <div class="bg-bg-card border border-border-subtle rounded-2xl p-8 shadow-xl shadow-black/50">
        <h2 class="text-base font-semibold mb-5 text-white">Upload your PDF</h2>

        @if ($errors->any())
            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <form id="uploadForm" action="{{ route('quiz.generate') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div id="dropZone" class="group relative border-2 border-dashed border-border-subtle rounded-xl p-10 text-center cursor-pointer transition-colors duration-200 hover:border-brand-1 hover:bg-brand-1/5">
                <input type="file" id="pdfInput" name="pdf" accept=".pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                
                <div class="w-12 h-12 bg-brand-1/15 rounded-xl flex items-center justify-center mx-auto mb-4 text-brand-2 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V4m0 8l-3-3m3 3l3-3"/>
                    </svg>
                </div>
                <div class="font-semibold text-sm mb-1 text-slate-200">Drop your PDF here</div>
                <div class="text-xs text-slate-500">or click to browse — max 100 MB</div>
                <div id="fileName" class="mt-3 text-sm font-medium text-emerald-400 truncate px-4"></div>
            </div>

            <button type="submit" id="submitBtn" class="w-full mt-5 py-3.5 bg-gradient-to-br from-brand-1 to-violet-500 text-white font-semibold rounded-xl transition-all duration-200 hover:opacity-90 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-lg shadow-brand-1/25">
                <span class="spinner" id="spinner"></span>
                <span id="btnText">Generate Quiz</span>
            </button>
        </form>
    </div>
</div>

<!-- QUIZ LIST -->
<section class="max-w-4xl mx-auto px-6 pb-16">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-bold text-white">Your Quizzes</h2>
        <span class="bg-border-subtle text-slate-400 text-xs font-semibold px-2.5 py-1 rounded-full">{{ $quizzes->count() }} total</span>
    </div>

    @if ($quizzes->isEmpty())
        <div class="text-center py-14 bg-bg-card border border-dashed border-border-subtle rounded-2xl">
            <div class="text-4xl mb-4 opacity-70">📄</div>
            <h3 class="font-semibold text-white mb-1">No quizzes yet</h3>
            <p class="text-sm text-slate-500">Upload a PDF above to generate your first quiz.</p>
        </div>
    @else
        <div class="grid gap-4">
            @foreach ($quizzes as $quiz)
                <div class="bg-bg-card border border-border-subtle rounded-xl p-5 flex items-center justify-between gap-4 transition-all duration-200 hover:border-brand-1/40 hover:-translate-y-0.5 group">
                    <div class="min-w-0 flex-1">
                        <div class="font-semibold text-slate-200 truncate mb-1 group-hover:text-brand-2 transition-colors">{{ $quiz->title }}</div>
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                {{ $quiz->questions->count() }} questions
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $quiz->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <a href="{{ route('quiz.show', $quiz) }}" class="px-3.5 py-2 bg-brand-1/15 text-brand-2 hover:bg-brand-1/25 rounded-lg text-sm font-semibold transition-colors">
                            View
                        </a>
                        <form action="{{ route('quiz.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Delete this quiz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3.5 py-2 bg-red-500/10 text-red-400 hover:bg-red-500/20 rounded-lg text-sm font-semibold transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>

<footer class="text-center py-6 border-t border-border-subtle text-slate-500 text-xs">
    &copy; {{ date('Y') }} QuizAI — AI Quiz Generator
</footer>

<script>
    const dropZone  = document.getElementById('dropZone');
    const pdfInput  = document.getElementById('pdfInput');
    const fileName  = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');
    const spinner   = document.getElementById('spinner');
    const btnText   = document.getElementById('btnText');
    const form      = document.getElementById('uploadForm');

    pdfInput.addEventListener('change', () => {
        if (pdfInput.files[0]) {
            fileName.textContent = '✓ ' + pdfInput.files[0].name;
            dropZone.classList.add('border-brand-1', 'bg-brand-1/5');
        }
    });

    ['dragover', 'dragenter'].forEach(evt => {
        dropZone.addEventListener(evt, e => {
            e.preventDefault();
            dropZone.classList.add('border-brand-1', 'bg-brand-1/5');
        });
    });
    ['dragleave', 'drop'].forEach(evt => {
        dropZone.addEventListener(evt, () => {
            if(!pdfInput.files.length) {
                dropZone.classList.remove('border-brand-1', 'bg-brand-1/5');
            }
        });
    });
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (file && file.type === 'application/pdf') {
            const dt = new DataTransfer();
            dt.items.add(file);
            pdfInput.files = dt.files;
            fileName.textContent = '✓ ' + file.name;
        }
    });

    form.addEventListener('submit', () => {
        submitBtn.disabled = true;
        spinner.style.display = 'block';
        btnText.textContent = 'Generating…';
    });
</script>

</body>
</html>
