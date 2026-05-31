@extends('layouts.app')
@section('title', 'Submit Bid')
@section('page-title', 'Submit Bid')

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
        {{-- Tender Context Banner --}}
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-900">{{ $tender->title }}</p>
                <p class="text-xs text-blue-600 mt-0.5">
                    <span class="font-mono">{{ $tender->tender_number }}</span>
                    <span class="mx-1.5">·</span>
                    Budget: <span class="font-semibold">${{ number_format($tender->budget, 2) }}</span>
                    <span class="mx-1.5">·</span>
                    Deadline: <span class="font-semibold">{{ $tender->submission_deadline->format('d M Y') }}</span>
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('bids.store', $tender) }}" class="space-y-6">
            @csrf

            {{-- Bid Amount --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                        Bid Amount
                    </h2>
                </div>
                <div class="p-6">
                    <label for="amount" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Your Bid Amount (USD) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative max-w-xs">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold">$</span>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" required min="1" step="0.01"
                            class="w-full border @error('amount') border-red-400 @else border-slate-300 @enderror rounded-lg pl-7 pr-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-slate-400 transition-colors font-semibold text-lg"
                            placeholder="0.00">
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-400 mt-2">Tender budget: ${{ number_format($tender->budget, 2) }}</p>
                </div>
            </div>

            {{-- Proposals --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                        Proposals
                    </h2>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label for="technical_proposal" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Technical Proposal <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-slate-400 mb-2">
                            Describe your technical approach, methodology, and relevant experience. (min. 50 characters)
                        </p>
                        <textarea id="technical_proposal" name="technical_proposal" rows="6" required minlength="50"
                            class="w-full border @error('technical_proposal') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-slate-400 transition-colors resize-none"
                            placeholder="Describe your technical approach, methodology, team expertise, and relevant past experience...">{{ old('technical_proposal') }}</textarea>
                        @error('technical_proposal')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="financial_proposal" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Financial Proposal <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-slate-400 mb-2">
                            Provide a breakdown of costs, payment schedule, and financial terms. (min. 50 characters)
                        </p>
                        <textarea id="financial_proposal" name="financial_proposal" rows="6" required minlength="50"
                            class="w-full border @error('financial_proposal') border-red-400 @else border-slate-300 @enderror rounded-lg px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-slate-400 transition-colors resize-none"
                            placeholder="Provide cost breakdown, payment milestones, and financial terms...">{{ old('financial_proposal') }}</textarea>
                        @error('financial_proposal')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm"
                    onclick="return confirm('Submit your bid? You can edit it later if the tender is still open.')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Submit Bid
                </button>
                <a href="{{ route('tenders.show', $tender) }}"
                    class="inline-flex items-center gap-2 border border-slate-300 text-slate-600 hover:bg-slate-50 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
