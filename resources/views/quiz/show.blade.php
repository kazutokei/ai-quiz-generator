@extends('layouts.app')

@section('title', $quiz->title . ' — QuizAI')
@section('meta_description', 'Quiz: ' . $quiz->title . ' — Review AI-generated multiple choice questions and answers.')

@section('custom_styles')
    .choice-correct {
        @apply bg-emerald-500/10 border-emerald-500/50 text-emerald-400 font-medium;
    }
    .choice-correct .choice-letter {
        @apply bg-emerald-500/20 text-emerald-300;
    }
    
    .choice-wrong {
        @apply opacity-60 bg-bg-surface border-border-subtle text-text-muted;
    }
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

<!-- QUESTIONS (Static Review Mode) -->
<div class="max-w-4xl mx-auto px-6 pb-12 flex flex-col gap-6">
    @foreach ($questions as $index => $question)
        <div class="question-card bg-bg-card border border-emerald-500/30 rounded-2xl overflow-hidden shadow-sm">
            
            <div class="p-5 sm:p-6 flex items-start gap-3 sm:gap-4 border-b border-border-subtle/50">
                <div class="shrink-0 w-8 h-8 rounded-lg bg-brand-1/20 text-brand-2 text-sm font-bold flex items-center justify-center">
                    {{ $index + 1 }}
                </div>
                <div class="font-semibold text-[15px] leading-relaxed text-text-main pt-1">
                    {{ $question->question }}
                </div>
            </div>
            
            <div class="p-5 sm:p-6 flex flex-col gap-3 bg-bg-surface">
                @foreach (['A' => $question->choice_a, 'B' => $question->choice_b, 'C' => $question->choice_c, 'D' => $question->choice_d] as $letter => $text)
                    @php
                        $isCorrect = ($letter === $question->correct_answer);
                    @endphp
                    <div class="flex items-center gap-3 p-3.5 rounded-xl border text-left text-[14px] {{ $isCorrect ? 'choice-correct' : 'choice-wrong' }}">
                        <span class="choice-letter shrink-0 w-7 h-7 rounded-[6px] {{ $isCorrect ? '' : 'bg-border-subtle text-text-muted font-bold text-xs' }} flex items-center justify-center transition-colors">
                            @if($isCorrect)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            @else
                                {{ $letter }}
                            @endif
                        </span>
                        {{ $text }}
                    </div>
                @endforeach
            </div>
            
            <div class="mx-5 sm:mx-6 mb-5 sm:mb-6 p-4 bg-brand-1/10 border-l-4 border-brand-1 rounded-r-xl text-sm text-text-muted leading-relaxed">
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
</div>
@endsection
