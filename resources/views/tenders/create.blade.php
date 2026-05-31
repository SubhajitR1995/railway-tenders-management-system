@extends('layouts.app')
@section('title', 'New Tender')
@section('page-title', 'Create New Tender')

@section('content')
    <div class="mb-5">
        <a href="{{ route('tenders.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Tenders
        </a>
    </div>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('tenders.store') }}" class="space-y-6">
            @csrf

            {{-- Basic Info --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                        Basic Information
                    </h2>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Tender Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            class="w-full border @error('title') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder:text-slate-400 transition-colors"
                            placeholder="e.g. Supply and Installation of Railway Track Sections">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category_id" name="category_id" required
                            class="w-full border @error('category_id') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white transition-colors">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="budget" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Budget (USD) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">$</span>
                                <input type="number" id="budget" name="budget" value="{{ old('budget') }}" required min="0" step="0.01"
                                    class="w-full border @error('budget') border-red-400 @else border-slate-300 @enderror rounded-lg pl-7 pr-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-slate-400 transition-colors"
                                    placeholder="0.00">
                            </div>
                            @error('budget')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="submission_deadline" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Submission Deadline <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="submission_deadline" name="submission_deadline"
                                value="{{ old('submission_deadline') }}" required
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                class="w-full border @error('submission_deadline') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            @error('submission_deadline')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                        Tender Details
                    </h2>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-slate-400 mb-2">Provide a detailed description of the tender scope and objectives.</p>
                        <textarea id="description" name="description" rows="5" required
                            class="w-full border @error('description') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-slate-400 transition-colors resize-none"
                            placeholder="Describe the scope, objectives, and deliverables of this tender...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="requirements" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Requirements
                            <span class="text-slate-400 font-normal ml-1">(Optional)</span>
                        </label>
                        <p class="text-xs text-slate-400 mb-2">List bidder qualifications, certifications, or technical requirements.</p>
                        <textarea id="requirements" name="requirements" rows="4"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-slate-400 transition-colors resize-none"
                            placeholder="List required qualifications, certifications, experience...">{{ old('requirements') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Create Tender (Draft)
                </button>
                <a href="{{ route('tenders.index') }}"
                    class="inline-flex items-center gap-2 border border-slate-300 text-slate-600 hover:bg-slate-50 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
