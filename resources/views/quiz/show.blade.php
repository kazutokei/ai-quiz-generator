@extends('layouts.app')

@section('title', $quiz->title . ' — QuizAI')
@section('meta_description', 'Quiz: ' . $quiz->title . ' — Test your knowledge with AI-generated multiple choice questions.')

@section('custom_styles')
    .question-card.state-correct {
        @apply border-emerald-500/40;
    }
    .question-card.state-wrong {
        @apply border-red-500/40;
    }
    
    .choice-btn.selected-correct {
        @apply bg-emerald-500/10 border-emerald-500/50 text-emerald-400;
    }
    .choice-btn.selected-correct .choice-letter {
        @apply bg-emerald-500/20 text-emerald-300;
    }
    
    .choice-btn.selected-wrong {
        @apply bg-red-500/10 border-red-500/50 text-red-400;
    }
    .choice-btn.selected-wrong .choice-letter {
        @apply bg-red-500/20 text-red-300;
    }
    
    .choice-btn.reveal-correct {
        @apply bg-emerald-500/5 border-emerald-500/30 text-emerald-400;
    }
    .choice-btn.reveal-correct .choice-letter {
        @apply bg-emerald-500/20 text-emerald-300;
    }
@endsection

@section('nav_class', 'gap-4 py-4 px-8')
@section('nav_extra')
    <span class="text-border-subtle">/</span>
    <span class="text-sm text-slate-500 font-medium truncate max-w-[200px] sm:max-w-xs">{{ $quiz->title }}</span>
    
    <a href="{{ route('dashboard') }}" class="ml-auto text-sm text-slate-400 hover:text-white flex items-center gap-1.5 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span class="hidden sm:inline">Back to Dashboard</span>
    </a>
@endsection

@section('content')
<!-- HEADER -->
<div class="max-w-4xl mx-auto mt-10 px-6">
    <h1 class="text-2xl md:text-3xl font-extrabold bg-gradient-to-br from-white to-brand-2 text-transparent bg-clip-text mb-3">
        {{ $quiz->title }}
    </h1>
    
    <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 mb-8">
        <span class="flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            {{ $questions->count() }} questions
        </span>
        <span class="flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Generated {{ $quiz->created_at->diffForHumans() }}
        </span>
    </div>

    @if (session('success'))
        <div class="flex items-center gap-2 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm mb-6">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- SCORE BAR -->
    <div class="bg-bg-card border border-border-subtle rounded-2xl p-6 mb-10 flex items-center justify-between gap-6 flex-wrap shadow-lg">
        <div>
            <div class="font-bold text-slate-200">Your Score</div>
            <div class="text-xs text-slate-500 mt-1">Answer all questions to see your score</div>
        </div>
        <div class="text-3xl font-extrabold text-brand-2" id="scoreDisplay">
            0 / {{ $questions->count() }}
        </div>
        <div class="flex-1 min-w-[200px] h-2.5 bg-border-subtle rounded-full overflow-hidden">
            <div class="h-full rounded-full bg-gradient-to-r from-brand-1 to-brand-2 transition-all duration-700 ease-out" id="scoreBar" style="width:0%"></div>
        </div>
    </div>
</div>

<!-- QUESTIONS -->
<div class="max-w-4xl mx-auto px-6 pb-12 flex flex-col gap-6" id="questionsContainer">
    @foreach ($questions as $index => $question)
        <div class="question-card bg-bg-card border border-border-subtle rounded-2xl overflow-hidden transition-colors duration-300 shadow-sm" 
             id="card-{{ $question->id }}" data-correct="{{ $question->correct_answer }}">
            
            <div class="p-6 flex items-start gap-4 border-b border-border-subtle/50">
                <div class="shrink-0 w-8 h-8 rounded-lg bg-brand-1/20 text-brand-2 text-sm font-bold flex items-center justify-center">
                    {{ $index + 1 }}
                </div>
                <div class="font-semibold text-[15px] leading-relaxed text-slate-200 pt-1">
                    {{ $question->question }}
                </div>
            </div>
            
            <div class="p-6 flex flex-col gap-3 bg-bg-card/50">
                @foreach (['A' => $question->choice_a, 'B' => $question->choice_b, 'C' => $question->choice_c, 'D' => $question->choice_d] as $letter => $text)
                    <button class="choice-btn flex items-center gap-3 p-3.5 rounded-xl border border-border-subtle text-left text-[14px] text-slate-300 transition-all duration-200 hover:not-disabled:bg-brand-1/10 hover:not-disabled:border-brand-1/40 hover:not-disabled:text-white"
                            data-letter="{{ $letter }}"
                            data-qid="{{ $question->id }}"
                            onclick="handleAnswer(this)">
                        <span class="choice-letter shrink-0 w-7 h-7 rounded-[6px] bg-border-subtle font-bold text-xs flex items-center justify-center text-slate-400 transition-colors">
                            {{ $letter }}
                        </span>
                        {{ $text }}
                    </button>
                @endforeach
            </div>
            
            <!-- Explanation initially hidden -->
            <div class="explanation hidden mx-6 mb-6 p-4 bg-brand-1/10 border-l-4 border-brand-1 rounded-r-xl text-sm text-slate-400 leading-relaxed" 
                 id="exp-{{ $question->id }}">
                <span class="text-brand-2 font-semibold">💡 Explanation:</span> {{ $question->explanation }}
            </div>
        </div>
    @endforeach
</div>

<!-- DANGER ZONE -->
<div class="max-w-4xl mx-auto px-6 pb-16 text-right border-t border-border-subtle pt-8 mt-4">
    <form action="{{ route('quiz.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this quiz? This cannot be undone.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-5 py-2.5 bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl text-sm font-semibold hover:bg-red-500/20 hover:border-red-500/30 transition-all flex items-center gap-2 ml-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete Quiz
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let correctCount = 0;
    const total = {{ $questions->count() }};
    const answered = new Set();

    function handleAnswer(btn) {
        const qid     = btn.dataset.qid;
        const card    = document.getElementById('card-' + qid);
        const correct = card.dataset.correct;
        const chosen  = btn.dataset.letter;

        if (answered.has(qid)) return;
        answered.add(qid);

        // Disable all choices for this question
        card.querySelectorAll('.choice-btn').forEach(b => {
            b.disabled = true;
            if (b.dataset.letter === correct && b !== btn) {
                b.classList.add('reveal-correct');
            }
        });

        if (chosen === correct) {
            btn.classList.add('selected-correct');
            card.classList.add('state-correct');
            correctCount++;
        } else {
            btn.classList.add('selected-wrong');
            card.classList.add('state-wrong');
        }

        // Show explanation
        document.getElementById('exp-' + qid).classList.remove('hidden');

        // Update score
        updateScore();
    }

    function updateScore() {
        const pct = total > 0 ? Math.round((correctCount / total) * 100) : 0;
        document.getElementById('scoreDisplay').textContent = correctCount + ' / ' + total;
        document.getElementById('scoreBar').style.width = pct + '%';
    }
</script>
@endsection
