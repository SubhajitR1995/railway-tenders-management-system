@extends('layouts.app')
@section('title', 'Edit Tender')
@section('page-title', 'Edit Tender')

@section('content')
    <div class="mb-5">
        <a href="{{ route('tenders.show', $tender) }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Tender
        </a>
    </div>

    <div class="max-w-3xl">
        {{-- Tender ID Badge --}}
        <div class="flex items-center gap-3 mb-6">
            <span class="inline-flex items-center gap-2 bg-slate-100 text-slate-600 text-xs font-mono font-medium px-3 py-1.5 rounded-full">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ $tender->tender_number }}
            </span>
            <span @class([
                'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                'bg-slate-100 text-slate-600' => $tender->status === 'draft',
                'bg-emerald-100 text-emerald-700' => $tender->status === 'published',
            ])>
                {{ ucfirst($tender->status) }}
            </span>
        </div>

        <form method="POST" action="{{ route('tenders.update', $tender) }}" class="space-y-6">
            @csrf
            @method('PUT')

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
                        <input type="text" id="title" name="title" value="{{ old('title', $tender->title) }}" required
                            class="w-full border @error('title') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category_id" name="category_id" required
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white transition-colors">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $tender->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="budget" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Budget (USD) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">$</span>
                                <input type="number" id="budget" name="budget" value="{{ old('budget', $tender->budget) }}" required min="0" step="0.01"
                                    class="w-full border @error('budget') border-red-400 @else border-slate-300 @enderror rounded-lg pl-7 pr-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
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
                                value="{{ old('submission_deadline', $tender->submission_deadline->format('Y-m-d')) }}" required
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
                        <textarea id="description" name="description" rows="5" required
                            class="w-full border @error('description') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors resize-none">{{ old('description', $tender->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="requirements" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Requirements
                            <span class="text-slate-400 font-normal ml-1">(Optional)</span>
                        </label>
                        <textarea id="requirements" name="requirements" rows="4"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors resize-none">{{ old('requirements', $tender->requirements) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('tenders.show', $tender) }}"
                    class="inline-flex items-center gap-2 border border-slate-300 text-slate-600 hover:bg-slate-50 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
