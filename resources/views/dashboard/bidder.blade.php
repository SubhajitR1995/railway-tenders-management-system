@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('page-actions')
    <a href="{{ route('tenders.index') }}"
        class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-md shadow-blue-500/30">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Browse Tenders
    </a>
@endsection

@section('content')
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-indigo-600 via-blue-700 to-violet-800 rounded-2xl px-6 py-5 mb-6 text-white relative overflow-hidden">
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
            <p class="text-indigo-200 text-sm font-medium">Welcome back,</p>
            <h2 class="text-2xl font-bold mt-0.5">{{ auth()->user()->name }}</h2>
            <p class="text-indigo-200 text-sm mt-1">
                {{ auth()->user()->company_name ?? 'Independent Bidder' }} · {{ now()->format('l, d F Y') }}
            </p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-white to-blue-50/50 rounded-2xl p-5 border border-blue-100 shadow-sm">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-3 shadow-md shadow-blue-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['my_bids'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">My Bids</p>
        </div>

        <div class="bg-gradient-to-br from-white to-amber-50/50 rounded-2xl p-5 border border-amber-100 shadow-sm">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center mb-3 shadow-md shadow-amber-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['pending_bids'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Pending</p>
        </div>

        <div class="bg-gradient-to-br from-white to-emerald-50/50 rounded-2xl p-5 border border-emerald-100 shadow-sm">
            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mb-3 shadow-md shadow-emerald-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['accepted_bids'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Accepted</p>
        </div>

        <div class="bg-gradient-to-br from-white to-indigo-50/50 rounded-2xl p-5 border border-indigo-100 shadow-sm">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center mb-3 shadow-md shadow-indigo-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $stats['open_tenders'] }}</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Open Tenders</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Open Tenders --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full"></div>
                    <h2 class="font-semibold text-slate-800 text-sm">Open Tenders</h2>
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
                @forelse($openTenders as $tender)
                    <div class="px-6 py-3.5 hover:bg-gradient-to-r hover:from-slate-50/50 hover:to-emerald-50/20 transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('tenders.show', $tender) }}"
                                    class="text-sm font-medium text-slate-800 hover:text-transparent hover:bg-clip-text hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 transition-colors block truncate">
                                    {{ $tender->title }}
                                </a>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $tender->category->name }} · Budget: ${{ number_format($tender->budget, 0) }}</p>
                            </div>
                            <span class="text-xs font-semibold text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-rose-500 whitespace-nowrap shrink-0">
                                {{ $tender->submission_deadline->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm text-slate-400">No open tenders at the moment</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- My Recent Bids --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"></div>
                    <h2 class="font-semibold text-slate-800 text-sm">My Recent Bids</h2>
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
                @forelse($myBids as $bid)
                    <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gradient-to-r hover:from-slate-50/50 hover:to-blue-50/20 transition-colors">
                        <div class="min-w-0 flex-1 mr-4">
                            <a href="{{ route('bids.show', $bid) }}"
                                class="text-sm font-medium text-slate-800 hover:text-transparent hover:bg-clip-text hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 transition-colors block truncate">
                                {{ $bid->tender->title }}
                            </a>
                            <p class="text-xs text-slate-400 mt-0.5">${{ number_format($bid->amount, 2) }} · {{ $bid->tender->category->name }}</p>
                        </div>
                        <span @class([
                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border shrink-0',
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
                        <p class="text-sm text-slate-400">No bids submitted yet</p>
                        <a href="{{ route('tenders.index') }}"
                            class="inline-flex items-center gap-1 mt-3 text-xs font-medium text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                            Browse open tenders
                            <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
