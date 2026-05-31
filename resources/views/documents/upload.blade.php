@extends('layouts.app')
@section('title', 'Upload LOA PDF')

@section('content')
    <div class="max-w-2xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm mb-6">
            <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-700 transition-colors">Dashboard</a>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-800 font-medium">Upload LOA PDF</span>
        </div>

        {{-- Page Title --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Upload LOA PDF</h1>
            <p class="text-slate-500 text-sm mt-1">Upload your Railway Letter of Acceptance and we'll extract all details automatically.</p>
        </div>

        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" id="uploadForm">
            @csrf

            {{-- Drop Zone --}}
            <div id="dropZone"
                class="relative border-2 border-dashed border-slate-300 rounded-2xl p-12 text-center cursor-pointer transition-all bg-white hover:border-blue-400 hover:bg-blue-50/30 group"
                onclick="document.getElementById('pdf_file').click()">

                <div id="dropContent">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:bg-blue-100 transition-colors">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <p class="text-base font-semibold text-slate-700 mb-1">Drop your LOA PDF here</p>
                    <p class="text-sm text-slate-400">or <span class="text-blue-600 font-medium">click to browse</span> your files</p>
                    <div class="flex items-center justify-center gap-4 mt-4">
                        <span class="inline-flex items-center gap-1.5 text-xs text-slate-400 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-full">
                            <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                            PDF files only
                        </span>
                        <span class="text-xs text-slate-400">Max 20MB</span>
                    </div>
                </div>

                <div id="fileSelected" class="hidden">
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-base font-semibold text-slate-700 mb-1" id="fileName">File selected</p>
                    <p class="text-sm text-blue-500 hover:text-blue-600 font-medium">Click to change file</p>
                </div>
            </div>

            <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" class="hidden" required>

            @error('pdf_file')
                <div class="mt-2 flex items-center gap-2 text-red-600 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </div>
            @enderror

            {{-- What gets extracted --}}
            <div class="mt-6 bg-slate-50 border border-slate-200 rounded-2xl p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-800">Auto-extracted by AI</h3>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    @foreach([
                        'Railway Zone & Division',
                        'Letter Number & Date',
                        'Contractor Name & Address',
                        'Tender Number & Closing Date',
                        'Contract Value & EMD',
                        'Performance Guarantee',
                        'Bid Rate & Net Bid Value',
                        'Work Items (Schedule A & B)',
                        'Completion Period',
                        'IREPS Reference ID',
                    ] as $item)
                        <div class="flex items-center gap-2 text-xs text-slate-600">
                            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $item }}
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-slate-500 mt-4 flex items-start gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>Scanned PDFs use OCR. Extraction may take 30–60 seconds and some fields may need manual correction.</span>
                </p>
            </div>

            {{-- Submit --}}
            <button type="submit" id="submitBtn"
                class="mt-6 w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white py-3 px-6 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center gap-2 shadow-sm shadow-blue-600/20">
                <svg id="uploadIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <svg id="spinnerIcon" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span id="submitText">Upload &amp; Extract Data</span>
            </button>
        </form>
    </div>

    <script>
        const input = document.getElementById('pdf_file');
        const dropZone = document.getElementById('dropZone');
        const dropContent = document.getElementById('dropContent');
        const fileSelected = document.getElementById('fileSelected');
        const fileName = document.getElementById('fileName');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const uploadIcon = document.getElementById('uploadIcon');
        const spinnerIcon = document.getElementById('spinnerIcon');

        function showFile(name) {
            fileName.textContent = name;
            dropContent.classList.add('hidden');
            fileSelected.classList.remove('hidden');
            dropZone.classList.remove('border-slate-300');
            dropZone.classList.add('border-emerald-400', 'bg-emerald-50/30');
        }

        input.addEventListener('change', function () {
            if (this.files.length > 0) showFile(this.files[0].name);
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-400', 'bg-blue-50/30');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-blue-400', 'bg-blue-50/30');
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            if (file && file.type === 'application/pdf') {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                showFile(file.name);
            }
        });

        document.getElementById('uploadForm').addEventListener('submit', function () {
            submitText.textContent = 'Uploading & Extracting...';
            submitBtn.disabled = true;
            uploadIcon.classList.add('hidden');
            spinnerIcon.classList.remove('hidden');
        });
    </script>
@endsection
