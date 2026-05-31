@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
            <p class="text-slate-500 text-sm mt-1">
                {{ auth()->user()->company_name ?? 'Railway Contractor' }} &nbsp;·&nbsp; {{ now()->format('l, d F Y') }}
            </p>
        </div>
        <a href="{{ route('documents.upload') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm shadow-blue-600/20 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Upload LOA PDF
        </a>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Total --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Tenders</span>
                <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-400 mt-1">All documents</p>
        </div>

        {{-- Confirmed --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Confirmed</span>
                <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['confirmed'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Ready to use</p>
        </div>

        {{-- Pending Review --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pending Review</span>
                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-amber-500">{{ $stats['extracted'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Needs confirmation</p>
        </div>

        {{-- Uploaded --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Just Uploaded</span>
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['uploaded'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Processing pending</p>
        </div>
    </div>

    {{-- Recent Tenders --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div>
                <h2 class="font-semibold text-slate-900">Recent Tenders</h2>
                <p class="text-xs text-slate-500 mt-0.5">Your latest uploaded documents</p>
            </div>
            <a href="{{ route('documents.index') }}"
                class="text-xs font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                View all
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @forelse($recentDocuments as $doc)
            <div class="px-6 py-4 border-b border-slate-50 last:border-0 flex items-center gap-4 hover:bg-slate-50/50 transition-colors group">
                {{-- Icon --}}
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-red-100 transition-colors">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>

                {{-- Details --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-semibold text-slate-800 text-sm truncate">
                            {{ $doc->tender_number ?? $doc->original_filename }}
                        </p>
                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            @if($doc->status === 'confirmed') bg-emerald-100 text-emerald-700
                            @elseif($doc->status === 'extracted') bg-amber-100 text-amber-700
                            @else bg-blue-100 text-blue-700 @endif">
                            @if($doc->status === 'confirmed')
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                            @elseif($doc->status === 'extracted')
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span>
                            @else
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                            @endif
                            {{ ucfirst($doc->status) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4 mt-1 text-xs text-slate-400">
                        @if($doc->contractor_name)
                            <span class="truncate max-w-48">{{ $doc->contractor_name }}</span>
                        @else
                            <span class="italic">No contractor extracted yet</span>
                        @endif
                        @if($doc->contract_value)
                            <span class="shrink-0 font-medium text-slate-600">₹{{ number_format($doc->contract_value, 0) }}</span>
                        @endif
                        @if($doc->letter_date)
                            <span class="shrink-0 hidden sm:inline">{{ $doc->letter_date->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="shrink-0">
                    @if($doc->status === 'confirmed')
                        <a href="{{ route('documents.show', $doc) }}"
                            class="text-xs font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                            View
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('documents.review', $doc) }}"
                            class="text-xs font-medium text-amber-600 hover:text-amber-700 flex items-center gap-1">
                            Review
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-700 mb-1">No tenders yet</h3>
                <p class="text-sm text-slate-400 mb-5">Upload your first LOA PDF to get started</p>
                <a href="{{ route('documents.upload') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Upload your first PDF
                </a>
            </div>
        @endforelse
    </div>

@endsection
