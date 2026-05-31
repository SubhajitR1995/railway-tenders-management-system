@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('page-actions')
    @if(auth()->user()->canManageTenders())
        <a href="{{ route('tenders.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-md shadow-blue-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Tender
        </a>
    @endif
@endsection

@section('content')
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-blue-600 via-indigo-700 to-violet-800 rounded-2xl px-6 py-5 mb-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dots)"/>
            </svg>
        </div>
        <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative">
            <p class="text-blue-200 text-sm font-medium">Welcome back,</p>
            <h2 class="text-2xl font-bold mt-0.5">{{ auth()->user()->name }}</h2>
            <p class="text-blue-200 text-sm mt-1">
                {{ auth()->user()->isAdmin() ? 'System Administrator' : 'Tender Manager' }} · {{ now()->format('l, d F Y') }}
            </p>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

        <div class="bg-gradient-to-br from-white to-blue-50/50 rounded-2xl p-5 border border-blue-100 shadow-sm col-span-1">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-3 shadow-md shadow-blue-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_tenders'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Total Tenders</p>
        </div>

        <div class="bg-gradient-to-br from-white to-emerald-50/50 rounded-2xl p-5 border border-emerald-100 shadow-sm col-span-1">
            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mb-3 shadow-md shadow-emerald-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['published_tenders'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Published</p>
        </div>

        <div class="bg-gradient-to-br from-white to-purple-50/50 rounded-2xl p-5 border border-purple-100 shadow-sm col-span-1">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center mb-3 shadow-md shadow-purple-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['awarded_tenders'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Awarded</p>
        </div>

        <div class="bg-gradient-to-br from-white to-amber-50/50 rounded-2xl p-5 border border-amber-100 shadow-sm col-span-1">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center mb-3 shadow-md shadow-amber-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_bids'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Total Bids</p>
        </div>

        <div class="bg-gradient-to-br from-white to-indigo-50/50 rounded-2xl p-5 border border-indigo-100 shadow-sm col-span-1">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center mb-3 shadow-md shadow-indigo-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_bidders'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Bidders</p>
        </div>

        <div class="bg-gradient-to-br from-white to-pink-50/50 rounded-2xl p-5 border border-pink-100 shadow-sm col-span-1">
            <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl flex items-center justify-center mb-3 shadow-md shadow-pink-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_categories'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Categories</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Recent Tenders --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"></div>
                    <h2 class="font-semibold text-slate-800 text-sm">Recent Tenders</h2>
                </div>
                <a href="{{ route('tenders.index') }}"
                    class="text-xs font-medium text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center gap-1">
                    View all
                    <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentTenders as $tender)
                    <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gradient-to-r hover:from-slate-50/50 hover:to-blue-50/20 transition-colors">
                        <div class="min-w-0 flex-1 mr-4">
                            <a href="{{ route('tenders.show', $tender) }}"
                                class="text-sm font-medium text-slate-800 hover:text-transparent hover:bg-clip-text hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 transition-colors block truncate">
                                {{ $tender->title }}
                            </a>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $tender->tender_number }} · {{ $tender->category->name }}</p>
                        </div>
                        <span @class([
                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border',
                            'bg-gradient-to-r from-slate-100 to-gray-100 text-slate-600 border-slate-200' => $tender->status === 'draft',
                            'bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-700 border-emerald-200' => $tender->status === 'published',
                            'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-700 border-amber-200' => $tender->status === 'closed',
                            'bg-gradient-to-r from-purple-100 to-violet-100 text-purple-700 border-purple-200' => $tender->status === 'awarded',
                        ])>
                            {{ ucfirst($tender->status) }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm text-slate-400">No tenders yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Bids --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    <h2 class="font-semibold text-slate-800 text-sm">Recent Bids</h2>
                </div>
                <a href="{{ route('bids.index') }}"
                    class="text-xs font-medium text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center gap-1">
                    View all
                    <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentBids as $bid)
                    <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gradient-to-r hover:from-slate-50/50 hover:to-amber-50/20 transition-colors">
                        <div class="min-w-0 flex-1 mr-4">
                            <a href="{{ route('bids.show', $bid) }}"
                                class="text-sm font-medium text-slate-800 hover:text-transparent hover:bg-clip-text hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 transition-colors block truncate">
                                {{ $bid->bidder->company_name ?? $bid->bidder->name }}
                            </a>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $bid->tender->tender_number }} · ${{ number_format($bid->amount, 2) }}</p>
                        </div>
                        <span @class([
                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border',
                            'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border-blue-200' => $bid->status === 'pending',
                            'bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-700 border-emerald-200' => $bid->status === 'accepted',
                            'bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border-red-200' => $bid->status === 'rejected',
                            'bg-gradient-to-r from-slate-100 to-gray-100 text-slate-600 border-slate-200' => !in_array($bid->status, ['pending', 'accepted', 'rejected']),
                        ])>
                            {{ ucfirst($bid->status) }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                        <p class="text-sm text-slate-400">No bids yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    @if(auth()->user()->isAdmin())
        <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">Quick Actions</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('tenders.create') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-md shadow-blue-500/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Tender
                </a>
                <a href="{{ route('categories.create') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-md shadow-pink-500/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Category
                </a>
                <a href="{{ route('tenders.index') }}"
                    class="inline-flex items-center gap-2 border border-slate-200 bg-gradient-to-r from-white to-slate-50 hover:from-slate-50 hover:to-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    All Tenders
                </a>
            </div>
        </div>
    @endif
@endsection
