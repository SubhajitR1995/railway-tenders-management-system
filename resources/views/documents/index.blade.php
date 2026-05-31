@extends('layouts.app')
@section('title', 'My Tenders')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">My Tenders</h1>
            <p class="text-slate-500 text-sm mt-1">All your uploaded LOA documents</p>
        </div>
        <a href="{{ route('documents.upload') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm shadow-blue-600/20 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Upload LOA PDF
        </a>
    </div>

    @if($documents->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm py-20 text-center">
            <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-700 mb-2">No tenders uploaded yet</h3>
            <p class="text-slate-400 text-sm mb-6 max-w-sm mx-auto">Upload your first LOA PDF and we'll automatically extract all tender details for you.</p>
            <a href="{{ route('documents.upload') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Upload your first LOA PDF
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($documents as $doc)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all p-5">
                    <div class="flex items-start gap-4">
                        {{-- PDF Icon --}}
                        <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4 flex-wrap">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2.5 flex-wrap mb-1">
                                        <h3 class="font-bold text-slate-800 text-sm">
                                            {{ $doc->tender_number ?? $doc->original_filename }}
                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            @if($doc->status === 'confirmed') bg-emerald-100 text-emerald-700
                                            @elseif($doc->status === 'extracted') bg-amber-100 text-amber-700
                                            @else bg-blue-100 text-blue-700 @endif">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5
                                                @if($doc->status === 'confirmed') bg-emerald-500
                                                @elseif($doc->status === 'extracted') bg-amber-500
                                                @else bg-blue-500 @endif"></span>
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                    </div>
                                    @if($doc->contractor_name)
                                        <p class="text-sm text-slate-600 font-medium mb-2">{{ $doc->contractor_name }}</p>
                                    @endif

                                    {{-- Meta tags --}}
                                    <div class="flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-slate-500">
                                        @if($doc->railway_zone)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $doc->railway_zone }}
                                            </span>
                                        @endif
                                        @if($doc->division)
                                            <span>{{ $doc->division }}</span>
                                        @endif
                                        @if($doc->letter_date)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $doc->letter_date->format('d M Y') }}
                                            </span>
                                        @endif
                                        @if($doc->completion_period)
                                            <span>{{ $doc->completion_period }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Financial + Actions --}}
                                <div class="flex flex-col items-end gap-3 shrink-0">
                                    @if($doc->contract_value)
                                        <div class="text-right">
                                            <p class="text-xs text-slate-400">Contract Value</p>
                                            <p class="text-base font-bold text-slate-800">₹{{ number_format($doc->contract_value, 0) }}</p>
                                        </div>
                                    @endif
                                    @if($doc->bid_rate_percentage)
                                        <span class="text-xs font-semibold text-blue-700 bg-blue-50 px-2 py-1 rounded-lg">
                                            {{ $doc->bid_rate_percentage }}% Above
                                        </span>
                                    @endif

                                    <div class="flex items-center gap-2 mt-1">
                                        @if($doc->status === 'confirmed')
                                            <a href="{{ route('documents.show', $doc) }}"
                                                class="text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-1.5 rounded-lg transition-colors">
                                                View Details
                                            </a>
                                        @else
                                            <a href="{{ route('documents.review', $doc) }}"
                                                class="text-xs font-semibold bg-amber-500 hover:bg-amber-600 text-white px-3.5 py-1.5 rounded-lg transition-colors">
                                                Review →
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('documents.destroy', $doc) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Delete this tender document?')"
                                                class="text-xs font-medium text-slate-400 hover:text-red-500 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors border border-transparent hover:border-red-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $documents->links() }}
        </div>
    @endif
@endsection
