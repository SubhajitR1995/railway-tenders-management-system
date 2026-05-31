@extends('layouts.app')
@section('title', $document->tender_number ?? 'Tender Details')

@section('content')

    {{-- Breadcrumb + Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('documents.index') }}" class="text-slate-500 hover:text-slate-700 transition-colors">My Tenders</a>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-800 font-medium truncate max-w-64">{{ $document->tender_number ?? 'Tender Details' }}</span>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('documents.review', $document) }}"
                class="inline-flex items-center gap-1.5 text-sm font-medium border border-slate-300 text-slate-600 hover:bg-slate-50 px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form method="POST" action="{{ route('documents.destroy', $document) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                    onclick="return confirm('Delete this tender document? This cannot be undone.')"
                    class="inline-flex items-center gap-1.5 text-sm font-medium border border-red-200 text-red-500 hover:bg-red-50 px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    {{-- Hero Card --}}
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 rounded-2xl p-6 mb-6 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="30" height="30" patternUnits="userSpaceOnUse"><path d="M 30 0 L 0 0 0 30" fill="none" stroke="white" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(#grid)"/></svg>
        </div>
        <div class="relative flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 mb-3 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-500/20 text-blue-300 text-xs font-semibold rounded-lg border border-blue-500/30">
                        <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span>
                        {{ $document->railway_zone ?? 'Railway' }}
                    </span>
                    @if($document->division)
                        <span class="text-slate-400 text-sm">{{ $document->division }}</span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">
                    Tender No: {{ $document->tender_number ?? 'N/A' }}
                </h1>
                @if($document->work_description)
                    <p class="text-slate-300 text-sm leading-relaxed max-w-2xl">{{ $document->work_description }}</p>
                @endif
            </div>
            <div class="flex flex-row sm:flex-col gap-6 sm:gap-4 sm:text-right shrink-0">
                @if($document->contract_value)
                    <div>
                        <p class="text-slate-400 text-xs mb-1">Contract Value</p>
                        <p class="text-xl font-bold text-white">₹{{ number_format($document->contract_value, 0) }}</p>
                    </div>
                @endif
                @if($document->bid_rate_percentage)
                    <div>
                        <p class="text-slate-400 text-xs mb-1">Bid Rate</p>
                        <p class="text-lg font-bold text-blue-300">{{ $document->bid_rate_percentage }}% Above</p>
                    </div>
                @endif
                @if($document->completion_period)
                    <div>
                        <p class="text-slate-400 text-xs mb-1">Completion</p>
                        <p class="text-sm font-semibold text-slate-200">{{ $document->completion_period }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 3-column Info Grid --}}
    <div class="grid lg:grid-cols-3 gap-5 mb-6">

        {{-- Contractor --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Contractor</h3>
            </div>
            <p class="font-bold text-slate-800 text-sm">{{ $document->contractor_name ?? '—' }}</p>
            @if($document->contractor_address)
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">{{ $document->contractor_address }}</p>
            @endif
        </div>

        {{-- Tender Info --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tender Info</h3>
            </div>
            <dl class="space-y-2.5 text-sm">
                @foreach([
                    ['Letter No', $document->letter_number],
                    ['Letter Date', $document->letter_date?->format('d M Y')],
                    ['Closing Date', $document->tender_closing_date?->format('d M Y')],
                    ['Bid ID', $document->bid_id],
                    ['Bid Date', $document->bid_date?->format('d M Y')],
                    ['Neg. Bid IDs', $document->negotiation_bid_ids],
                    ['IREPS Ref ID', $document->ireps_reference_id],
                    ['Signed By', $document->signed_by],
                ] as [$label, $value])
                    @if($value)
                        <div class="flex justify-between items-start gap-2">
                            <dt class="text-slate-500 shrink-0">{{ $label }}</dt>
                            <dd class="font-semibold text-slate-800 text-right text-xs max-w-44 break-all">{{ $value }}</dd>
                        </div>
                    @endif
                @endforeach
            </dl>
        </div>

        {{-- Financial Summary --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Financials</h3>
            </div>
            <dl class="space-y-2.5 text-sm">
                @if($document->contract_value)
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500">Contract Value</dt>
                        <dd class="font-bold text-slate-900">₹{{ number_format($document->contract_value, 2) }}</dd>
                    </div>
                @endif
                @if($document->net_bid_value)
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500">Net Bid Value</dt>
                        <dd class="font-bold text-emerald-700">₹{{ number_format($document->net_bid_value, 2) }}</dd>
                    </div>
                @endif
                @if($document->total_advertised_value)
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500">Advertised Value</dt>
                        <dd class="font-semibold text-slate-700">₹{{ number_format($document->total_advertised_value, 2) }}</dd>
                    </div>
                @endif
                @if($document->earnest_money)
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500">Earnest Money</dt>
                        <dd class="font-semibold text-slate-700">₹{{ number_format($document->earnest_money, 2) }}</dd>
                    </div>
                @endif
                @if($document->performance_guarantee)
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500">Perf. Guarantee</dt>
                        <dd class="font-semibold text-slate-700">₹{{ number_format($document->performance_guarantee, 2) }}</dd>
                    </div>
                @endif
                @if($document->rebate_on_total_value)
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500">Rebate</dt>
                        <dd class="font-semibold text-slate-700">₹{{ number_format($document->rebate_on_total_value, 2) }}</dd>
                    </div>
                @endif
            </dl>
            @if($document->contract_value_words)
                <p class="text-xs text-slate-400 mt-4 leading-relaxed italic border-t border-slate-100 pt-3">{{ $document->contract_value_words }}</p>
            @endif
        </div>
    </div>

    {{-- Work Items Table --}}
    @if($document->workItems->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-800">Work Items</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $document->workItems->count() }} item{{ $document->workItems->count() !== 1 ? 's' : '' }} across all schedules</p>
                </div>
                @if($document->workItems->sum('advised_value') > 0)
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Total Advised</p>
                        <p class="font-bold text-slate-800">₹{{ number_format($document->workItems->sum('advised_value'), 0) }}</p>
                    </div>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-500 uppercase tracking-wider">Schedule</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500 uppercase tracking-wider">Item No</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500 uppercase tracking-wider max-w-xs">Description</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-500 uppercase tracking-wider">Qty</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500 uppercase tracking-wider">Unit</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-500 uppercase tracking-wider">Advised Value</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-500 uppercase tracking-wider">Bid Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($document->workItems as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors {{ $item->is_sub_item ? 'bg-slate-50/30' : '' }}">
                                <td class="px-4 py-3 text-slate-500">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs font-medium">{{ $item->schedule_name }}</span>
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-700">{{ $item->item_number ?? ($item->is_sub_item ? '↳' : '—') }}</td>
                                <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $item->item_code ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-700 max-w-xs">
                                    <p class="line-clamp-2 leading-relaxed">{{ $item->description ?? '—' }}</p>
                                </td>
                                <td class="px-4 py-3 text-right text-slate-700 font-medium">{{ $item->quantity ? number_format($item->quantity, 0) : '—' }}</td>
                                <td class="px-4 py-3 text-slate-500 font-medium">{{ $item->unit ?? '—' }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-slate-800">
                                    {{ $item->advised_value ? '₹'.number_format($item->advised_value, 2) : '—' }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-700">
                                    {{ $item->bid_amount ? '₹'.number_format($item->bid_amount, 2) : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if($document->workItems->sum('advised_value') > 0)
                        <tfoot>
                            <tr class="bg-slate-900 text-white">
                                <td colspan="6" class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wide text-slate-300">Total</td>
                                <td class="px-4 py-3 text-right font-bold text-sm">₹{{ number_format($document->workItems->sum('advised_value'), 2) }}</td>
                                <td class="px-4 py-3 text-right font-bold text-sm text-emerald-400">
                                    {{ $document->workItems->sum('bid_amount') > 0 ? '₹'.number_format($document->workItems->sum('bid_amount'), 2) : '—' }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    @endif

    {{-- Notes --}}
    @if($document->notes)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <h3 class="text-sm font-bold text-amber-800">Notes</h3>
            </div>
            <p class="text-sm text-amber-700 leading-relaxed">{{ $document->notes }}</p>
        </div>
    @endif

@endsection
