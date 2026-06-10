@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-text-main">Your Quizzes</h1>
            <p class="text-text-muted text-sm mt-1">Manage your generated AI quizzes from PDF modules.</p>
        </div>
        <button onclick="openUploadModal()" class="bg-brand-1 hover:bg-brand-1/90 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            Generate New Quiz
        </button>
    </div>

    <!-- QUIZ LIST -->
    @if ($quizzes->isEmpty())
        <div class="text-center py-20 bg-bg-card border border-dashed border-border-subtle rounded-2xl">
            <div class="relative flex justify-center mb-8 animate-float">
                <!-- Soft glow behind the icon -->
                <div class="absolute inset-0 bg-brand-1/20 blur-2xl rounded-full w-24 h-24 mx-auto"></div>
                <!-- Large icon container -->
                <div class="relative bg-brand-1/10 w-24 h-24 rounded-[2rem] flex items-center justify-center text-brand-1 border border-brand-1/20 shadow-lg shadow-brand-1/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 1.98-3A2.5 2.5 0 0 1 9.5 2Z"/>
                        <path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-1.98-3A2.5 2.5 0 0 0 14.5 2Z"/>
                    </svg>
                </div>
            </div>
            <h3 class="font-bold text-text-main text-xl mb-2">No Quizzes Generated Yet</h3>
            <p class="text-base text-text-muted">Upload a PDF module and let AI craft your first quiz instantly.</p>
        </div>
    @else
        <div class="grid gap-4">
            @foreach ($quizzes as $quiz)
                <div class="group bg-bg-card border border-border-subtle rounded-2xl p-5 hover:border-brand-1/30 hover:shadow-md transition-all duration-200 flex items-center justify-between gap-4">
                    <a href="{{ route('quiz.show', $quiz) }}" class="flex items-center gap-5 flex-1 min-w-0">
                        <div class="w-12 h-12 bg-brand-1/10 rounded-xl flex items-center justify-center text-brand-1 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <line x1="10" y1="9" x2="8" y2="9"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-text-main truncate text-base mb-1">{{ $quiz->title }}</div>
                            <div class="flex items-center gap-3 text-xs text-text-muted">
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    {{ $quiz->created_at->format('j M Y') }}
                                </span>
                                <span>&bull;</span>
                                <span>{{ $quiz->questions->count() }} Questions</span>
                            </div>
                        </div>
                    </a>
                    <div class="flex items-center gap-2 shrink-0">
                        <button onclick="openDeleteModal('{{ route('quiz.destroy', $quiz) }}')" class="p-2 text-text-muted hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Delete Quiz">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        </button>
                        <a href="{{ route('quiz.show', $quiz) }}" class="text-text-muted group-hover:text-brand-1 transition-colors p-2 hidden sm:block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- UPLOAD MODAL -->
    <div id="uploadModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 bg-text-main/20 backdrop-blur-sm" onclick="closeUploadModal()"></div>
        
        <div class="bg-bg-surface border border-border-subtle rounded-2xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden">
            
            <div class="px-6 py-4 border-b border-border-subtle flex items-center justify-between">
                <h3 class="font-bold text-text-main text-lg">Generate New Quiz</h3>
                <button onclick="closeUploadModal()" id="uploadCloseBtn" class="text-text-muted hover:text-text-main transition-colors p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <div class="p-8">
                <!-- Generating State -->
                <div id="uploadingState" class="text-center py-8 hidden">
                    <div class="w-12 h-12 rounded-full border-4 border-border-subtle border-t-brand-1 animate-spin mx-auto mb-6"></div>
                    <h4 class="text-lg font-semibold text-text-main mb-2">AI is Generating Quiz...</h4>
                    <p class="text-text-muted text-sm">Crafting 15 multiple-choice questions</p>
                    <div class="w-full bg-border-subtle rounded-full h-1.5 mt-6 overflow-hidden">
                        <div class="bg-brand-1 h-1.5 rounded-full animate-pulse w-3/4 mx-auto" style="animation: indeterminate 2s linear infinite;"></div>
                    </div>
                </div>

                <style>
                    @keyframes indeterminate {
                        0% { transform: translateX(-100%); }
                        100% { transform: translateX(200%); }
                    }
                </style>

                <!-- Upload Form State -->
                <form id="uploadForm" action="{{ route('quiz.generate') }}" method="POST" enctype="multipart/form-data" onsubmit="startUpload()">
                    @csrf
                    <div id="dropZone" class="border-2 border-dashed border-border-subtle rounded-2xl p-12 text-center cursor-pointer transition-colors hover:border-brand-1 hover:bg-brand-1/5 relative">
                        <input type="file" id="pdfInput" name="pdf" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                        
                        <div class="w-12 h-12 text-brand-1 mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <div class="font-medium text-text-main mb-1 text-base">Click or drag PDF to upload</div>
                        <div class="text-sm text-text-muted">Max file size 100MB.</div>
                        <div id="fileName" class="mt-4 text-sm font-semibold text-emerald-500 truncate px-2 max-w-[280px] mx-auto block" title=""></div>
                    </div>

                    <button type="submit" id="submitBtn" class="w-full mt-6 bg-brand-1 text-white font-semibold py-3 rounded-xl hover:opacity-90 transition-opacity hidden">
                        Start Generation
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 bg-text-main/20 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        
        <div class="bg-bg-surface border border-border-subtle rounded-2xl shadow-2xl w-full max-w-sm relative z-10 overflow-hidden">
            
            <div class="px-6 py-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                </div>
                <h3 class="font-bold text-text-main text-lg mb-2">Delete Quiz?</h3>
                <p class="text-text-muted text-sm">Are you sure you want to delete this quiz? This action cannot be undone.</p>
            </div>
            
            <div class="px-6 py-4 bg-bg-card border-t border-border-subtle flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-border-subtle hover:bg-border-subtle/80 text-text-main rounded-xl font-medium transition-colors">Cancel</button>
                <form id="deleteForm" method="POST" class="flex-1 flex">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium transition-colors">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let isUploading = false;

    function openUploadModal() {
        document.getElementById('uploadModal').classList.remove('hidden');
    }

    function closeUploadModal() {
        if (!isUploading) {
            document.getElementById('uploadModal').classList.add('hidden');
        }
    }

    function startUpload() {
        isUploading = true;
        document.getElementById('uploadForm').classList.add('hidden');
        document.getElementById('uploadingState').classList.remove('hidden');
        document.getElementById('uploadCloseBtn').classList.add('opacity-50', 'cursor-not-allowed');
    }

    function openDeleteModal(url) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function handleFileSelection(file) {
        if (!file.name.toLowerCase().endsWith('.pdf') && file.type !== 'application/pdf') {
            showNotification('Only PDF files are allowed.', 'error');
            resetUploadState();
            return false;
        }
        
        const MAX_SIZE_MB = 100;
        if (file.size > MAX_SIZE_MB * 1024 * 1024) {
            showNotification(`File is too large. Maximum size is ${MAX_SIZE_MB}MB.`, 'error');
            resetUploadState();
            return false;
        }

        const fileNameEl = document.getElementById('fileName');
        fileNameEl.textContent = file.name;
        fileNameEl.title = file.name; // Add title for tooltip on hover
        document.getElementById('dropZone').classList.add('border-brand-1', 'bg-brand-1/5');
        document.getElementById('submitBtn').classList.remove('hidden');
        return true;
    }

    function resetUploadState() {
        document.getElementById('pdfInput').value = '';
        document.getElementById('submitBtn').classList.add('hidden');
        document.getElementById('fileName').textContent = '';
        document.getElementById('fileName').title = '';
        document.getElementById('dropZone').classList.remove('border-brand-1', 'bg-brand-1/5');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const dropZone = document.getElementById('dropZone');
        const pdfInput = document.getElementById('pdfInput');

        if(pdfInput && dropZone) {
            pdfInput.addEventListener('change', () => {
                if (pdfInput.files.length > 0) {
                    handleFileSelection(pdfInput.files[0]);
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
                    if (!pdfInput.files.length) {
                        dropZone.classList.remove('border-brand-1', 'bg-brand-1/5');
                    }
                });
            });
            
            dropZone.addEventListener('drop', e => {
                e.preventDefault();
                const file = e.dataTransfer.files[0];
                if (file) {
                    if (handleFileSelection(file)) {
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        pdfInput.files = dt.files;
                    }
                }
            });
        }
    });
</script>
@endsection
