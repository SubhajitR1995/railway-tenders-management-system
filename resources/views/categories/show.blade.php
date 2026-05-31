@extends('layouts.app')
@section('title', $category->name)
@section('page-title', $category->name)

@section('page-actions')
    <a href="{{ route('categories.edit', $category) }}"
        class="inline-flex items-center gap-2 border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit Category
    </a>
@endsection

@section('content')
    <div class="mb-5">
        <a href="{{ route('categories.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Categories
        </a>
    </div>

    {{-- Category Header --}}
    @if($category->description)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6 flex items-start gap-4">
            <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $category->description }}</p>
            </div>
        </div>
    @endif

    {{-- Tenders Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
            <h2 class="font-semibold text-slate-800 text-sm">
                Tenders in this Category
                <span class="text-slate-400 font-normal">({{ $tenders->total() }})</span>
            </h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tender</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Budget</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Deadline</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($tenders as $tender)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('tenders.show', $tender) }}"
                                class="font-semibold text-slate-800 hover:text-blue-600 transition-colors">
                                {{ $tender->title }}
                            </a>
                            <p class="text-xs text-slate-400 mt-0.5 font-mono">{{ $tender->tender_number }}</p>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            <span class="font-semibold text-slate-800">${{ number_format($tender->budget, 0) }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-sm hidden md:table-cell">
                            {{ $tender->submission_deadline->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span @class([
                                'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                                'bg-slate-100 text-slate-600' => $tender->status === 'draft',
                                'bg-emerald-100 text-emerald-700' => $tender->status === 'published',
                                'bg-amber-100 text-amber-700' => $tender->status === 'closed',
                                'bg-purple-100 text-purple-700' => $tender->status === 'awarded',
                            ])>
                                {{ ucfirst($tender->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-slate-500 font-medium">No tenders in this category</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tenders->links() }}</div>
@endsection
