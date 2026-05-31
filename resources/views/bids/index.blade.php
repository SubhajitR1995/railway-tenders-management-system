@extends('layouts.app')
@section('title', auth()->user()->isBidder() ? 'My Bids' : 'All Bids')
@section('page-title', auth()->user()->isBidder() ? 'My Bids' : 'All Bids')

@section('content')
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    @if(!auth()->user()->isBidder())
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bidder</th>
                    @endif
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tender</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Submitted</th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bids as $bid)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        @if(!auth()->user()->isBidder())
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-800">{{ $bid->bidder->company_name ?? $bid->bidder->name }}</p>
                                <p class="text-xs text-slate-400">{{ $bid->bidder->email }}</p>
                            </td>
                        @endif
                        <td class="px-6 py-4">
                            <a href="{{ route('tenders.show', $bid->tender) }}"
                                class="font-semibold text-slate-800 hover:text-blue-600 transition-colors block font-mono text-xs">
                                {{ $bid->tender->tender_number }}
                            </a>
                            <p class="text-xs text-slate-400 truncate max-w-48 mt-0.5">{{ $bid->tender->title }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-900">${{ number_format($bid->amount, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span @class([
                                'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                                'bg-blue-100 text-blue-700' => $bid->status === 'pending',
                                'bg-emerald-100 text-emerald-700' => $bid->status === 'accepted',
                                'bg-red-100 text-red-700' => $bid->status === 'rejected',
                                'bg-slate-100 text-slate-600' => !in_array($bid->status, ['pending', 'accepted', 'rejected']),
                            ])>
                                {{ ucfirst($bid->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs hidden sm:table-cell">
                            {{ $bid->submitted_at?->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('bids.show', $bid) }}"
                                    class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-800 px-2.5 py-1.5 rounded-lg hover:bg-blue-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </a>
                                @if(auth()->user()->isBidder() && $bid->isPending() && $bid->tender->isOpen())
                                    <a href="{{ route('bids.edit', $bid) }}"
                                        class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 hover:text-slate-800 px-2.5 py-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <p class="text-slate-500 font-medium">No bids found</p>
                            @if(auth()->user()->isBidder())
                                <a href="{{ route('tenders.index') }}"
                                    class="inline-flex items-center gap-1 mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    Browse open tenders
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $bids->links() }}</div>
@endsection
