@extends('layouts.app')
@section('title', 'Bid Details')
@section('page-title', 'Bid Details')

@section('content')
    <div class="mb-5">
        <a href="{{ route('bids.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Bids
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Bid Header --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Bid Submission</h2>
                        <p class="text-sm text-slate-400 mt-0.5">Submitted {{ $bid->submitted_at?->format('d M Y, H:i') }}</p>
                    </div>
                    <span @class([
                        'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold',
                        'bg-blue-100 text-blue-700' => $bid->status === 'pending',
                        'bg-emerald-100 text-emerald-700' => $bid->status === 'accepted',
                        'bg-red-100 text-red-700' => $bid->status === 'rejected',
                        'bg-slate-100 text-slate-600' => !in_array($bid->status, ['pending', 'accepted', 'rejected']),
                    ])>
                        @if($bid->status === 'accepted')
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @elseif($bid->status === 'rejected')
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @endif
                        {{ ucfirst($bid->status) }}
                    </span>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Technical Proposal</h3>
                        <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line bg-slate-50/50 rounded-xl p-4 border border-slate-100">{{ $bid->technical_proposal }}</p>
                    </div>

                    <div>
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Financial Proposal</h3>
                        <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line bg-slate-50/50 rounded-xl p-4 border border-slate-100">{{ $bid->financial_proposal }}</p>
                    </div>

                    @if($bid->notes)
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <h3 class="text-sm font-semibold text-amber-800">Reviewer Notes</h3>
                            </div>
                            <p class="text-amber-700 text-sm leading-relaxed">{{ $bid->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- Bid Info Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Bid Information</h3>
                <dl class="space-y-4">

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">Bid Amount</dt>
                            <dd class="font-bold text-2xl text-slate-900 mt-0.5">${{ number_format($bid->amount, 2) }}</dd>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 pt-3 border-t border-slate-100">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">Tender</dt>
                            <dd class="mt-0.5">
                                <a href="{{ route('tenders.show', $bid->tender) }}"
                                    class="font-semibold text-blue-600 hover:text-blue-800 text-sm font-mono">
                                    {{ $bid->tender->tender_number }}
                                </a>
                                <p class="text-xs text-slate-500 mt-0.5 leading-tight">{{ $bid->tender->title }}</p>
                            </dd>
                        </div>
                    </div>

                    @if(auth()->user()->canManageTenders())
                        <div class="flex items-start gap-3 pt-3 border-t border-slate-100">
                            <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400">Bidder</dt>
                                <dd class="font-semibold text-slate-900 mt-0.5 text-sm">{{ $bid->bidder->company_name ?? $bid->bidder->name }}</dd>
                                <dd class="text-xs text-slate-400">{{ $bid->bidder->email }}</dd>
                            </div>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Bidder Actions --}}
            @if(auth()->user()->isBidder() && $bid->isPending() && $bid->tender->isOpen())
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-2.5">
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Actions</h3>
                    <a href="{{ route('bids.edit', $bid) }}"
                        class="flex items-center justify-center gap-2 w-full border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Bid
                    </a>
                    <form method="POST" action="{{ route('bids.destroy', $bid) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center justify-center gap-2 w-full border border-red-200 text-red-600 hover:bg-red-50 px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors"
                            onclick="return confirm('Withdraw this bid? This action cannot be undone.')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Withdraw Bid
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
