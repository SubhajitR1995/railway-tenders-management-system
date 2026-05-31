<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Tender;
use App\Models\TenderCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tender::with(['category', 'creator']);

        if ($request->user()->isBidder()) {
            $query->where('status', 'published');
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request): void {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('tender_number', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status') && ! $request->user()->isBidder()) {
            $query->where('status', $request->status);
        }

        $tenders = $query->latest()->paginate(10)->withQueryString();
        $categories = TenderCategory::orderBy('name')->get();

        return view('tenders.index', compact('tenders', 'categories'));
    }

    public function create(): View
    {
        $categories = TenderCategory::orderBy('name')->get();

        return view('tenders.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'requirements' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:tender_categories,id'],
            'budget' => ['required', 'numeric', 'min:0'],
            'submission_deadline' => ['required', 'date', 'after:today'],
        ]);

        $tender = Tender::create([
            ...$validated,
            'tender_number' => Tender::generateTenderNumber(),
            'created_by' => $request->user()->id,
            'status' => 'draft',
        ]);

        return redirect()->route('tenders.show', $tender)
            ->with('success', 'Tender created successfully.');
    }

    public function show(Tender $tender, Request $request): View
    {
        $tender->load(['category', 'creator', 'bids.bidder', 'awardedBid.bidder']);

        $userBid = null;
        if ($request->user()->isBidder()) {
            $userBid = $tender->bids()->where('user_id', $request->user()->id)->first();
        }

        return view('tenders.show', compact('tender', 'userBid'));
    }

    public function edit(Tender $tender): View
    {
        $categories = TenderCategory::orderBy('name')->get();

        return view('tenders.edit', compact('tender', 'categories'));
    }

    public function update(Request $request, Tender $tender): RedirectResponse
    {
        if (! in_array($tender->status, ['draft', 'published'])) {
            return back()->with('error', 'Only draft or published tenders can be edited.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'requirements' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:tender_categories,id'],
            'budget' => ['required', 'numeric', 'min:0'],
            'submission_deadline' => ['required', 'date'],
        ]);

        $tender->update($validated);

        return redirect()->route('tenders.show', $tender)
            ->with('success', 'Tender updated successfully.');
    }

    public function destroy(Tender $tender): RedirectResponse
    {
        if ($tender->status !== 'draft') {
            return back()->with('error', 'Only draft tenders can be deleted.');
        }

        $tender->delete();

        return redirect()->route('tenders.index')
            ->with('success', 'Tender deleted successfully.');
    }

    public function publish(Tender $tender): RedirectResponse
    {
        if ($tender->status !== 'draft') {
            return back()->with('error', 'Only draft tenders can be published.');
        }

        $tender->update(['status' => 'published']);

        return back()->with('success', 'Tender published successfully.');
    }

    public function close(Tender $tender): RedirectResponse
    {
        if ($tender->status !== 'published') {
            return back()->with('error', 'Only published tenders can be closed.');
        }

        $tender->update(['status' => 'closed']);

        return back()->with('success', 'Tender closed for submissions.');
    }

    public function award(Request $request, Tender $tender): RedirectResponse
    {
        if ($tender->status !== 'closed') {
            return back()->with('error', 'Only closed tenders can be awarded.');
        }

        $validated = $request->validate([
            'bid_id' => ['required', 'exists:bids,id'],
        ]);

        $bid = Bid::findOrFail($validated['bid_id']);

        if ($bid->tender_id !== $tender->id) {
            return back()->with('error', 'The selected bid does not belong to this tender.');
        }

        $tender->update([
            'status' => 'awarded',
            'awarded_bid_id' => $bid->id,
        ]);

        $bid->update(['status' => 'accepted']);

        $tender->bids()->where('id', '!=', $bid->id)->update(['status' => 'rejected']);

        return back()->with('success', 'Tender awarded successfully.');
    }
}
