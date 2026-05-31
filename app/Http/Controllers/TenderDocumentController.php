<?php

namespace App\Http\Controllers;

use App\Models\TenderDocument;
use App\Models\TenderWorkItem;
use App\Services\LoaExtractionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TenderDocumentController extends Controller
{
    public function __construct(private LoaExtractionService $extractionService) {}

    public function index(Request $request): View
    {
        $documents = TenderDocument::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('documents.index', compact('documents'));
    }

    public function upload(): View
    {
        return view('documents.upload');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        $file = $request->file('pdf_file');
        $filename = $file->getClientOriginalName();
        $path = $file->store('documents/'.$request->user()->id, 'local');

        // Create the document record
        $document = TenderDocument::create([
            'user_id' => $request->user()->id,
            'original_filename' => $filename,
            'file_path' => $path,
            'status' => 'uploaded',
        ]);

        // Try automatic extraction
        $fullPath = Storage::disk('local')->path($path);
        $extracted = $this->extractionService->extract($fullPath);

        // Check if we got any useful data
        $hasData = collect($extracted)->except('work_items')->filter()->isNotEmpty();

        if ($hasData) {
            $workItems = $extracted['work_items'] ?? [];
            unset($extracted['work_items']);

            $document->update(array_merge($extracted, [
                'status' => 'extracted',
                'extracted_at' => now(),
            ]));

            // Save extracted work items
            foreach ($workItems as $item) {
                TenderWorkItem::create(array_merge($item, [
                    'tender_document_id' => $document->id,
                ]));
            }

            return redirect()->route('documents.review', $document)
                ->with('success', 'PDF uploaded and data extracted successfully. Please review and confirm the details below.');
        }

        // Scanned PDF — redirect to manual review form
        return redirect()->route('documents.review', $document)
            ->with('info', 'PDF uploaded. We could not extract text automatically (scanned PDF). Please fill in the details manually.');
    }

    public function review(TenderDocument $document, Request $request): View|RedirectResponse
    {
        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        $document->load('workItems');

        return view('documents.review', compact('document'));
    }

    public function confirm(Request $request, TenderDocument $document): RedirectResponse
    {
        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'railway_zone' => ['nullable', 'string', 'max:100'],
            'division' => ['nullable', 'string', 'max:150'],
            'office' => ['nullable', 'string', 'max:255'],
            'letter_number' => ['nullable', 'string', 'max:255'],
            'letter_date' => ['nullable', 'date'],
            'contractor_name' => ['nullable', 'string', 'max:255'],
            'contractor_address' => ['nullable', 'string'],
            'tender_number' => ['nullable', 'string', 'max:100'],
            'tender_closing_date' => ['nullable', 'date'],
            'work_description' => ['nullable', 'string'],
            'bid_id' => ['nullable', 'string', 'max:50'],
            'bid_date' => ['nullable', 'date'],
            'negotiation_bid_ids' => ['nullable', 'string', 'max:255'],
            'contract_value' => ['nullable', 'numeric', 'min:0'],
            'contract_value_words' => ['nullable', 'string'],
            'earnest_money' => ['nullable', 'numeric', 'min:0'],
            'ireps_reference_id' => ['nullable', 'string', 'max:100'],
            'performance_guarantee' => ['nullable', 'numeric', 'min:0'],
            'net_bid_value' => ['nullable', 'numeric', 'min:0'],
            'bid_rate_percentage' => ['nullable', 'numeric'],
            'rebate_on_total_value' => ['nullable', 'numeric', 'min:0'],
            'total_advertised_value' => ['nullable', 'numeric', 'min:0'],
            'completion_period' => ['nullable', 'string', 'max:100'],
            'signed_by' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],

            // Work items
            'items' => ['nullable', 'array'],
            'items.*.id' => ['nullable', 'integer'],
            'items.*.schedule_name' => ['nullable', 'string', 'max:50'],
            'items.*.item_number' => ['nullable', 'string', 'max:20'],
            'items.*.item_code' => ['nullable', 'string', 'max:20'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity' => ['nullable', 'numeric'],
            'items.*.unit' => ['nullable', 'string', 'max:30'],
            'items.*.escl_rate' => ['nullable', 'numeric'],
            'items.*.advised_value' => ['nullable', 'numeric'],
            'items.*.bid_rate_unit_rate' => ['nullable', 'numeric'],
            'items.*.bid_amount' => ['nullable', 'numeric'],
        ]);

        $items = $validated['items'] ?? [];
        unset($validated['items']);

        $document->update(array_merge($validated, [
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]));

        // Sync work items
        $document->workItems()->delete();
        foreach ($items as $item) {
            if (! empty(array_filter($item))) {
                unset($item['id']);
                TenderWorkItem::create(array_merge($item, [
                    'tender_document_id' => $document->id,
                ]));
            }
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Tender details confirmed and saved successfully!');
    }

    public function show(TenderDocument $document, Request $request): View
    {
        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        $document->load('workItems');

        return view('documents.show', compact('document'));
    }

    public function destroy(TenderDocument $document, Request $request): RedirectResponse
    {
        if ($document->user_id !== $request->user()->id) {
            abort(403);
        }

        Storage::disk('local')->delete($document->file_path);
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Tender document deleted successfully.');
    }
}
