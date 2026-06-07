@extends('layouts.app')
@section('title', $tender->title)
@section('page-title', $tender->tender_number)

@section('page-actions')
    @if(auth()->user()->canManageTenders())
        @if($tender->isDraft())
            <a href="{{ route('tenders.edit', $tender) }}"
                class="inline-flex items-center gap-2 border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium px-3 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        @elseif($tender->isPublished())
            <a href="{{ route('tenders.edit', $tender) }}"
                class="inline-flex items-center gap-2 border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium px-3 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        @endif
    @endif
@endsection

@section('content')
    {{-- Back Link --}}
    <div class="mb-5">
        <a href="{{ route('tenders.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Tenders
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Tender Header Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-mono text-slate-400 font-medium">{{ $tender->tender_number }}</span>
                            <span class="text-slate-300">·</span>
                            <span class="text-xs text-slate-400">{{ $tender->category->name }}</span>
                        </div>
                        <h1 class="text-2xl font-bold text-slate-900 leading-tight">{{ $tender->title }}</h1>
                    </div>
                    <span @class([
                        'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold shrink-0',
                        'bg-slate-100 text-slate-600' => $tender->status === 'draft',
                        'bg-emerald-100 text-emerald-700' => $tender->status === 'published',
                        'bg-amber-100 text-amber-700' => $tender->status === 'closed',
                        'bg-purple-100 text-purple-700' => $tender->status === 'awarded',
                    ])>
                        @if($tender->status === 'published')
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                        @endif
                        {{ ucfirst($tender->status) }}
                    </span>
                </div>

                <div class="space-y-5">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Description</h3>
                        <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $tender->description }}</p>
                    </div>

                    @if($tender->requirements)
                        <div class="border-t border-slate-100 pt-5">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Requirements</h3>
                            <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $tender->requirements }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- Tender Details Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Tender Details</h3>
                <dl class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">Budget</dt>
                            <dd class="font-bold text-slate-900 text-lg mt-0.5">${{ number_format($tender->budget, 2) }}</dd>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">Submission Deadline</dt>
                            <dd class="font-semibold text-slate-900 mt-0.5">{{ $tender->submission_deadline->format('d M Y') }}</dd>
                            <dd class="text-xs font-medium mt-0.5 {{ $tender->submission_deadline->isPast() ? 'text-red-500' : 'text-emerald-600' }}">
                                {{ $tender->submission_deadline->diffForHumans() }}
                            </dd>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">Posted By</dt>
                            <dd class="font-semibold text-slate-900 mt-0.5">{{ $tender->creator->name }}</dd>
                            <dd class="text-xs text-slate-400">{{ $tender->created_at->format('d M Y') }}</dd>
                        </div>
                    </div>

                </dl>
            </div>

            {{-- Actions Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-2.5">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Actions</h3>

                @if(auth()->user()->canManageTenders())
                    @if($tender->isDraft())
                        <form method="POST" action="{{ route('tenders.publish', $tender) }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors"
                                onclick="return confirm('Publish this tender? It will be visible to all bidders.')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Publish Tender
                            </button>
                        </form>
                        <form method="POST" action="{{ route('tenders.destroy', $tender) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center justify-center gap-2 w-full border border-red-200 text-red-600 hover:bg-red-50 px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors"
                                onclick="return confirm('Delete this draft tender? This cannot be undone.')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Draft
                            </button>
                        </form>
                    @elseif($tender->isPublished())
                        <form method="POST" action="{{ route('tenders.close', $tender) }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors"
                                onclick="return confirm('Close this tender for new submissions?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Close Tender
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
