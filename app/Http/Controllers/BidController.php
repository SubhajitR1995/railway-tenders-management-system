<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Tender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BidController extends Controller
{
    public function index(Request $request): View
    {
        $query = Bid::with(['tender.category', 'bidder']);

        if ($request->user()->isBidder()) {
            $query->where('user_id', $request->user()->id);
        }

        $bids = $query->latest()->paginate(10)->withQueryString();

        return view('bids.index', compact('bids'));
    }

    public function create(Tender $tender, Request $request): View|RedirectResponse
    {
        if (! $tender->isOpen()) {
            return redirect()->route('tenders.show', $tender)
                ->with('error', 'This tender is not open for bidding.');
        }

        $existingBid = $tender->bids()->where('user_id', $request->user()->id)->first();
        if ($existingBid) {
            return redirect()->route('tenders.show', $tender)
                ->with('error', 'You have already submitted a bid for this tender.');
        }

        return view('bids.create', compact('tender'));
    }

    public function store(Request $request, Tender $tender): RedirectResponse
    {
        if (! $tender->isOpen()) {
            return redirect()->route('tenders.show', $tender)
                ->with('error', 'This tender is not open for bidding.');
        }

        $existingBid = $tender->bids()->where('user_id', $request->user()->id)->first();
        if ($existingBid) {
            return redirect()->route('tenders.show', $tender)
                ->with('error', 'You have already submitted a bid for this tender.');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'technical_proposal' => ['required', 'string', 'min:50'],
            'financial_proposal' => ['required', 'string', 'min:50'],
        ]);

        Bid::create([
            ...$validated,
            'tender_id' => $tender->id,
            'user_id' => $request->user()->id,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()->route('tenders.show', $tender)
            ->with('success', 'Your bid has been submitted successfully.');
    }

    public function show(Bid $bid, Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isBidder() && $bid->user_id !== $user->id) {
            abort(403);
        }

        $bid->load(['tender.category', 'bidder']);

        return view('bids.show', compact('bid'));
    }

    public function edit(Bid $bid, Request $request): View|RedirectResponse
    {
        if ($bid->user_id !== $request->user()->id) {
            abort(403);
        }

        if (! $bid->isPending()) {
            return redirect()->route('bids.show', $bid)
                ->with('error', 'Only pending bids can be edited.');
        }

        if (! $bid->tender->isOpen()) {
            return redirect()->route('bids.show', $bid)
                ->with('error', 'The tender is no longer open for bid edits.');
        }

        return view('bids.edit', compact('bid'));
    }

    public function update(Request $request, Bid $bid): RedirectResponse
    {
        if ($bid->user_id !== $request->user()->id) {
            abort(403);
        }

        if (! $bid->isPending() || ! $bid->tender->isOpen()) {
            return back()->with('error', 'This bid cannot be updated.');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'technical_proposal' => ['required', 'string', 'min:50'],
            'financial_proposal' => ['required', 'string', 'min:50'],
        ]);

        $bid->update($validated);

        return redirect()->route('bids.show', $bid)
            ->with('success', 'Bid updated successfully.');
    }

    public function destroy(Bid $bid, Request $request): RedirectResponse
    {
        if ($bid->user_id !== $request->user()->id) {
            abort(403);
        }

        if (! $bid->isPending()) {
            return back()->with('error', 'Only pending bids can be withdrawn.');
        }

        $bid->update(['status' => 'withdrawn']);

        return redirect()->route('bids.index')
            ->with('success', 'Bid withdrawn successfully.');
    }
}
