@extends('layouts.app')

@section('title', $quiz->title . ' — QuizAI')
@section('meta_description', 'Quiz: ' . $quiz->title . ' — Review AI-generated multiple choice questions and answers.')

@section('custom_styles')
    /* Styles are handled by Tailwind utility classes in JS */
@endsection

@section('nav_class', 'gap-4 py-4 px-8')
@section('nav_extra')
    <span class="text-border-subtle">/</span>
    <span class="text-sm text-text-muted font-medium truncate max-w-[200px] sm:max-w-xs">{{ $quiz->title }}</span>
@endsection

@section('content')
<!-- HEADER -->
<div class="max-w-4xl mx-auto mt-8 px-6">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-text-muted hover:text-brand-1 transition-colors mb-6 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:-translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to Quizzes
    </a>

    <h1 class="text-2xl md:text-3xl font-extrabold bg-gradient-to-br from-text-main to-brand-2 text-transparent bg-clip-text mb-3">
        {{ $quiz->title }}
    </h1>
    
    <div class="flex flex-wrap items-center gap-4 text-sm text-text-muted mb-8">
        <span class="flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            {{ $questions->count() }} questions
        </span>
        <span class="flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Generated {{ $quiz->created_at->diffForHumans() }}
        </span>
    </div>
</div>

<!-- MODE SELECTOR HEADER (Hidden on load) -->
<div id="modeSelectorHeader" class="max-w-4xl mx-auto px-6 mb-8 hidden">
    <div class="bg-bg-surface/80 backdrop-blur-md border border-border-subtle rounded-2xl p-2 flex flex-col sm:flex-row gap-2 shadow-sm">
        <button id="btnReviewerMode" onclick="setMode('reviewer')" class="flex-1 py-2.5 rounded-xl font-semibold text-sm transition-all bg-brand-1 text-white shadow-md">
            Reviewer Mode
        </button>
        <button id="btnInstantMode" onclick="setMode('instant')" class="flex-1 py-2.5 rounded-xl font-semibold text-sm transition-all text-text-muted hover:bg-border-subtle/50">
            Instant Feedback Mode
        </button>
        <button id="btnExamMode" onclick="setMode('exam')" class="flex-1 py-2.5 rounded-xl font-semibold text-sm transition-all text-text-muted hover:bg-border-subtle/50">
            Exam Mode
        </button>
    </div>
</div>

<!-- MODE SELECTION SPLASH -->
<div id="modeSelectionSplash" class="max-w-4xl mx-auto px-6 py-12 mb-20 text-center animate-fade-in">
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-brand-1/10 text-brand-1 mb-6 shadow-inner">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    </div>
    <h2 class="text-3xl font-black text-text-main mb-4 tracking-tight">Ready to start?</h2>
    <p class="text-text-muted mb-10 max-w-lg mx-auto leading-relaxed">Choose how you want to tackle this material. You can instantly review the answers, get immediate feedback as you go, or simulate a real exam.</p>
    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 max-w-4xl mx-auto">
        <!-- Primary Option: Reviewer Mode -->
        <button onclick="startQuiz('reviewer')" class="flex flex-col p-6 rounded-3xl bg-brand-1 text-white text-left shadow-xl shadow-brand-1/20 hover:shadow-2xl hover:shadow-brand-1/30 hover:-translate-y-1 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center mb-4 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
            </div>
            <h3 class="font-bold text-xl mb-2">Reviewer Mode</h3>
            <p class="text-sm text-white/80 leading-relaxed flex-1">Instantly see all questions, correct answers, and explanations. Best for quick reading and studying.</p>
        </button>

        <!-- Secondary Option: Instant Feedback -->
        <button onclick="startQuiz('instant')" class="flex flex-col p-6 rounded-3xl bg-bg-surface border border-border-subtle text-left hover:border-brand-1/50 hover:bg-brand-1/5 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-bg-card border border-border-subtle flex items-center justify-center mb-4 text-brand-1 group-hover:bg-brand-1 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            </div>
            <h3 class="font-bold text-text-main text-xl mb-2 group-hover:text-brand-1 transition-colors">Instant Feedback</h3>
            <p class="text-sm text-text-muted leading-relaxed flex-1">Answer questions one by one and get immediate grading and explanations for each choice.</p>
        </button>

        <!-- Secondary Option: Exam Mode -->
        <button onclick="startQuiz('exam')" class="flex flex-col p-6 rounded-3xl bg-bg-surface border border-border-subtle text-left hover:border-brand-1/50 hover:bg-brand-1/5 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-bg-card border border-border-subtle flex items-center justify-center mb-4 text-brand-1 group-hover:bg-brand-1 group-hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <h3 class="font-bold text-text-main text-xl mb-2 group-hover:text-brand-1 transition-colors">Exam Mode</h3>
            <p class="text-sm text-text-muted leading-relaxed flex-1">Test your knowledge. Answer all questions without hints and see your final score at the very end.</p>
        </button>
    </div>
</div>

<!-- QUESTIONS (Hidden on load) -->
<div class="max-w-4xl mx-auto px-6 pb-28 flex flex-col gap-6 hidden" id="questionsContainer">
    @foreach ($questions as $index => $question)
        <div class="question-card bg-bg-card border border-border-subtle rounded-2xl overflow-hidden shadow-sm transition-all duration-300" data-question-id="{{ $question->id }}" data-answered="false">
            
            <div class="p-5 sm:p-6 flex items-start gap-3 sm:gap-4 border-b border-border-subtle/50">
                <div class="shrink-0 w-8 h-8 rounded-lg bg-brand-1/20 text-brand-2 text-sm font-bold flex items-center justify-center">
                    {{ $index + 1 }}
                </div>
                <div class="font-semibold text-[15px] leading-relaxed text-text-main pt-1">
                    {{ $question->question }}
                </div>
            </div>
            
            <div class="p-5 sm:p-6 flex flex-col gap-3 bg-bg-surface options-container">
                @foreach (['A' => $question->choice_a, 'B' => $question->choice_b, 'C' => $question->choice_c, 'D' => $question->choice_d] as $letter => $text)
                    @php
                        $isCorrect = ($letter === $question->correct_answer);
                    @endphp
                    <button onclick="selectOption(this)" 
                            data-correct="{{ $isCorrect ? 'true' : 'false' }}" 
                            data-letter="{{ $letter }}"
                            class="choice-btn group w-full flex items-center gap-3 p-3.5 rounded-xl border border-border-subtle text-left text-[14px] text-text-muted hover:border-brand-1/50 hover:bg-brand-1/5 transition-all">
                        <span class="choice-letter shrink-0 w-7 h-7 rounded-[6px] bg-border-subtle text-text-main font-bold text-xs flex items-center justify-center transition-colors group-hover:bg-brand-1/20 group-hover:text-brand-1">
                            {{ $letter }}
                        </span>
                        <span class="choice-text flex-1">{{ $text }}</span>
                        <span class="status-icon hidden shrink-0"></span>
                    </button>
                @endforeach
            </div>
            
            <div class="explanation-block hidden mx-5 sm:mx-6 mb-5 sm:mb-6 p-4 bg-brand-1/10 border-l-4 border-brand-1 rounded-r-xl text-sm text-text-muted leading-relaxed">
                <div class="flex items-start gap-2">
                    <div class="text-brand-2 shrink-0 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1.45.62 2.84 1.5 3.5.76.76 1.23 1.52 1.41 2.5"/><path d="M9.5 8.5 12 11l2.5-2.5"/></svg>
                    </div>
                    <div>
                        <span class="text-brand-2 font-semibold">Explanation:</span> <span class="text-text-main">{{ $question->explanation }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Submit Exam Button -->
    <div id="submitExamContainer" class="hidden mt-8 mb-12 text-center">
        <button onclick="submitExam()" id="btnSubmitExam" disabled class="bg-brand-1 text-white font-bold py-4 px-12 rounded-xl shadow-lg hover:bg-brand-1/90 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            Submit Exam
        </button>
    </div>
</div>

<!-- Score Modal -->
<div id="scoreModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 hidden">
    <div class="fixed inset-0 bg-bg-base/80 backdrop-blur-sm transition-opacity" onclick="closeScoreModal()"></div>
    <div class="bg-bg-surface border border-border-subtle rounded-3xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="scoreModalContent">
        
        <div class="px-8 pt-10 pb-8 text-center relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-brand-1/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-brand-2/10 rounded-full blur-xl"></div>
            
            <div class="relative w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-brand-1 to-brand-2 rounded-2xl flex items-center justify-center shadow-lg shadow-brand-1/20 transform -rotate-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white relative z-10 transform rotate-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15V3m0 12l-4-4m4 4l4-4M2 17l.621 2.485A2 2 0 0 0 4.561 21h14.878a2 2 0 0 0 1.94-1.515L22 17"></path></svg>
            </div>
            
            <h3 class="font-extrabold text-text-main text-2xl mb-2">Exam Completed!</h3>
            <p class="text-text-muted text-sm mb-8">Great job! Here is how you performed.</p>
            
            <div class="inline-flex flex-col items-center justify-center p-6 bg-bg-card border border-border-subtle rounded-2xl w-full shadow-inner">
                <div class="text-sm font-semibold tracking-wider text-text-muted uppercase mb-2">Final Score</div>
                <div class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-brand-1 to-brand-2 flex items-baseline gap-1">
                    <span id="scoreDisplay">0</span><span class="text-2xl text-text-muted font-bold">/{{ $questions->count() }}</span>
                </div>
                <div id="scoreMessage" class="mt-4 text-sm font-bold"></div>
            </div>
        </div>
        
        <div class="px-8 py-6 bg-bg-card border-t border-border-subtle flex gap-4">
            <button onclick="closeScoreModal()" class="flex-1 py-3.5 bg-brand-1 text-white hover:bg-brand-1/90 rounded-xl font-bold transition-all shadow-[0_4px_12px_rgba(var(--color-brand-1),0.3)] hover:shadow-[0_6px_16px_rgba(var(--color-brand-1),0.4)] hover:-translate-y-0.5">Review Answers</button>
        </div>
    </div>
</div>

<!-- Mode Change Warning Modal -->
<div id="modeWarningModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 hidden">
    <div class="fixed inset-0 bg-bg-base/80 backdrop-blur-sm transition-opacity" onclick="closeModeWarning()"></div>
    <div class="bg-bg-surface border border-border-subtle rounded-3xl shadow-2xl w-full max-w-sm relative z-10 p-6 transform scale-95 opacity-0 transition-all duration-300" id="modeWarningContent">
        <div class="w-12 h-12 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center mb-4 mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <h3 class="font-bold text-text-main text-xl text-center mb-2">Change Mode?</h3>
        <p class="text-text-muted text-sm text-center mb-6">Switching modes will reset your current progress. Are you sure you want to continue?</p>
        <div class="flex gap-3">
            <button onclick="closeModeWarning()" class="flex-1 py-2.5 bg-bg-card border border-border-subtle hover:bg-border-subtle/50 rounded-xl font-semibold text-text-main transition-colors">Cancel</button>
            <button onclick="confirmModeChange()" class="flex-1 py-2.5 bg-brand-1 text-white hover:bg-brand-1/90 rounded-xl font-semibold shadow-md transition-colors">Yes, Change</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentMode = 'none'; 
    let pendingMode = null;
    let totalQuestions = {{ $questions->count() }};
    let answeredQuestions = 0;
    let score = 0;
    let examSubmitted = false;

    // Remove the DOMContentLoaded auto-start since we now have a splash screen
    // document.addEventListener('DOMContentLoaded', () => {
    //    setMode('reviewer');
    // });

    function startQuiz(mode) {
        document.getElementById('modeSelectionSplash').classList.add('hidden');
        document.getElementById('modeSelectorHeader').classList.remove('hidden');
        document.getElementById('questionsContainer').classList.remove('hidden');
        setMode(mode);
    }

    function setMode(mode) {
        if (examSubmitted && mode !== 'reviewer') return;
        if (currentMode === mode) return;

        if (answeredQuestions > 0 && currentMode !== 'none' && currentMode !== 'reviewer') {
            pendingMode = mode;
            const modal = document.getElementById('modeWarningModal');
            const content = document.getElementById('modeWarningContent');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
            return;
        }

        executeModeChange(mode);
    }

    function closeModeWarning() {
        const modal = document.getElementById('modeWarningModal');
        const content = document.getElementById('modeWarningContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            pendingMode = null;
        }, 300);
    }

    function confirmModeChange() {
        if (pendingMode) {
            executeModeChange(pendingMode);
            closeModeWarning();
        }
    }

    function executeModeChange(mode) {
        currentMode = mode;
        const btnReviewer = document.getElementById('btnReviewerMode');
        const btnInstant = document.getElementById('btnInstantMode');
        const btnExam = document.getElementById('btnExamMode');
        const submitContainer = document.getElementById('submitExamContainer');
        const scoreBanner = document.getElementById('scoreBanner');
        
        answeredQuestions = 0;
        score = 0;
        examSubmitted = false;
        
        if(document.getElementById('btnSubmitExam')) {
            document.getElementById('btnSubmitExam').disabled = true;
        }

        // Reset classes
        btnReviewer.className = 'flex-1 py-2.5 rounded-xl font-semibold text-sm transition-all text-text-muted hover:bg-border-subtle/50';
        btnInstant.className = 'flex-1 py-2.5 rounded-xl font-semibold text-sm transition-all text-text-muted hover:bg-border-subtle/50';
        btnExam.className = 'flex-1 py-2.5 rounded-xl font-semibold text-sm transition-all text-text-muted hover:bg-border-subtle/50';

        if (mode === 'reviewer') {
            btnReviewer.className = 'flex-1 py-2.5 rounded-xl font-semibold text-sm transition-all bg-brand-1 text-white shadow-md';
            submitContainer.classList.add('hidden');
            
            resetQuestions();
            // Automatically show all answers
            document.querySelectorAll('.question-card').forEach(card => {
                card.dataset.answered = 'true';
                revealCorrectAnswer(card);
                card.querySelector('.explanation-block').classList.remove('hidden');
            });
            
        } else if (mode === 'instant') {
            btnInstant.className = 'flex-1 py-2.5 rounded-xl font-semibold text-sm transition-all bg-brand-1 text-white shadow-md';
            submitContainer.classList.add('hidden');
            document.getElementById('btnSubmitExam').textContent = "View Final Score";
            resetQuestions();
        } else {
            btnExam.className = 'flex-1 py-2.5 rounded-xl font-semibold text-sm transition-all bg-brand-1 text-white shadow-md';
            submitContainer.classList.remove('hidden');
            document.getElementById('btnSubmitExam').textContent = "Submit Exam";
            resetQuestions();
        }
    }

    function resetQuestions() {
        document.querySelectorAll('.question-card').forEach(card => {
            card.dataset.answered = 'false';
            card.querySelector('.explanation-block').classList.add('hidden');
            
            card.querySelectorAll('.choice-btn').forEach(btn => {
                btn.className = 'choice-btn group w-full flex items-center gap-3 p-3.5 rounded-xl border border-border-subtle text-left text-[14px] text-text-muted hover:border-brand-1/50 hover:bg-brand-1/5 transition-all';
                btn.dataset.selected = 'false';
                
                const letterEl = btn.querySelector('.choice-letter');
                letterEl.className = 'choice-letter shrink-0 w-7 h-7 rounded-[6px] bg-border-subtle text-text-main font-bold text-xs flex items-center justify-center transition-colors group-hover:bg-brand-1/20 group-hover:text-brand-1';
                letterEl.innerHTML = btn.dataset.letter; 
                
                const iconEl = btn.querySelector('.status-icon');
                iconEl.className = 'status-icon hidden shrink-0';
                iconEl.innerHTML = '';
            });
        });
    }

    function selectOption(button) {
        const card = button.closest('.question-card');
        
        if (currentMode === 'reviewer' || examSubmitted || (currentMode === 'instant' && card.dataset.answered === 'true')) return;

        const isCorrect = button.dataset.correct === 'true';

        if (currentMode === 'instant') {
            if (card.dataset.answered === 'false') {
                card.dataset.answered = 'true';
                answeredQuestions++;
                if (isCorrect) score++;
            }
            applyFeedback(button, isCorrect);
            revealCorrectAnswer(card);
            card.querySelector('.explanation-block').classList.remove('hidden');
            
            if (answeredQuestions === totalQuestions) {
                document.getElementById('btnSubmitExam').disabled = false;
                document.getElementById('submitExamContainer').classList.remove('hidden');
            }
        } else if (currentMode === 'exam') {
            card.querySelectorAll('.choice-btn').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-brand-1', 'bg-brand-1/5', 'border-brand-1', 'text-text-main');
                btn.classList.add('text-text-muted');
                btn.dataset.selected = 'false';
            });
            
            button.classList.add('ring-2', 'ring-brand-1', 'bg-brand-1/5', 'border-brand-1', 'text-text-main');
            button.classList.remove('text-text-muted');
            button.dataset.selected = 'true';

            if (card.dataset.answered === 'false') {
                card.dataset.answered = 'true';
                answeredQuestions++;
            }

            if (answeredQuestions === totalQuestions) {
                document.getElementById('btnSubmitExam').disabled = false;
            }
        }
    }

    function applyFeedback(button, isCorrect) {
        const letterEl = button.querySelector('.choice-letter');
        const iconEl = button.querySelector('.status-icon');
        
        if (isCorrect) {
            button.className = 'choice-btn w-full flex items-center gap-3 p-3.5 rounded-xl border text-left text-[14px] bg-emerald-500/10 border-emerald-500/50 text-emerald-500 font-medium transition-all';
            letterEl.className = 'choice-letter shrink-0 w-7 h-7 rounded-[6px] bg-emerald-500/20 text-emerald-500 font-bold text-xs flex items-center justify-center transition-colors';
            
            iconEl.classList.remove('hidden');
            iconEl.classList.add('text-emerald-500');
            iconEl.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`;
        } else {
            button.className = 'choice-btn w-full flex items-center gap-3 p-3.5 rounded-xl border text-left text-[14px] bg-red-500/10 border-red-500/50 text-red-500 font-medium transition-all';
            letterEl.className = 'choice-letter shrink-0 w-7 h-7 rounded-[6px] bg-red-500/20 text-red-500 font-bold text-xs flex items-center justify-center transition-colors';
            
            iconEl.classList.remove('hidden');
            iconEl.classList.add('text-red-500');
            iconEl.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;
        }
    }

    function revealCorrectAnswer(card) {
        card.querySelectorAll('.choice-btn').forEach(btn => {
            if (btn.dataset.correct === 'true') {
                if (currentMode === 'reviewer' || currentMode === 'instant' || examSubmitted) {
                    applyFeedback(btn, true);
                }
            } else if (!btn.classList.contains('bg-red-500/10')) {
                btn.classList.add('opacity-40');
            }
        });
    }

    function closeScoreModal() {
        const modal = document.getElementById('scoreModal');
        const content = document.getElementById('scoreModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function showScoreModal() {
        document.getElementById('scoreDisplay').textContent = score;
        
        let percentage = (score / totalQuestions) * 100;
        let msgEl = document.getElementById('scoreMessage');
        if (percentage >= 90) {
            msgEl.textContent = "Outstanding Performance!";
            msgEl.className = "mt-4 text-sm font-bold text-emerald-500 bg-emerald-500/10 border border-emerald-500/20 px-5 py-2 rounded-full";
        } else if (percentage >= 70) {
            msgEl.textContent = "Good Job!";
            msgEl.className = "mt-4 text-sm font-bold text-brand-1 bg-brand-1/10 border border-brand-1/20 px-5 py-2 rounded-full";
        } else {
            msgEl.textContent = "Keep Practicing!";
            msgEl.className = "mt-4 text-sm font-bold text-amber-500 bg-amber-500/10 border border-amber-500/20 px-5 py-2 rounded-full";
        }

        const modal = document.getElementById('scoreModal');
        const content = document.getElementById('scoreModalContent');
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
        
        document.getElementById('btnReviewerMode').classList.add('opacity-50', 'cursor-not-allowed');
        document.getElementById('btnInstantMode').classList.add('opacity-50', 'cursor-not-allowed');
        document.getElementById('btnExamMode').classList.add('opacity-50', 'cursor-not-allowed');
        examSubmitted = true;
        
        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function submitExam() {
        if (answeredQuestions < totalQuestions) return;
        
        if (currentMode === 'exam') {
            score = 0;
            document.querySelectorAll('.question-card').forEach(card => {
                const selectedBtn = card.querySelector('.choice-btn[data-selected="true"]');
                if (selectedBtn) {
                    const isCorrect = selectedBtn.dataset.correct === 'true';
                    if (isCorrect) score++;
                    
                    applyFeedback(selectedBtn, isCorrect);
                }
                
                revealCorrectAnswer(card);
                card.querySelector('.explanation-block').classList.remove('hidden');
            });
        }

        document.getElementById('submitExamContainer').classList.add('hidden');
        showScoreModal();
    }
</script>
@endsection
