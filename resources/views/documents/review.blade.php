@extends('layouts.app')
@section('title', 'Review Extracted Data')

@section('content')
    <div class="mb-6">
        <a href="{{ route('documents.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to My Tenders</a>
    </div>

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Review & Confirm Tender Details</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $document->original_filename }}</p>
        </div>
        <span class="px-3 py-1 rounded-full text-sm font-medium
            @if($document->status === 'extracted') bg-yellow-100 text-yellow-700
            @elseif($document->status === 'confirmed') bg-green-100 text-green-700
            @else bg-blue-100 text-blue-700 @endif">
            {{ ucfirst($document->status) }}
        </span>
    </div>

    @if($document->status === 'extracted')
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-green-700">Data has been automatically extracted from your PDF. Please review the fields below and make any corrections, then click <strong>Confirm & Save</strong>.</p>
        </div>
    @elseif($document->status === 'uploaded')
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-blue-700">This is a scanned PDF — automatic extraction was not possible. Please fill in the fields manually from your document.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('documents.confirm', $document) }}" id="reviewForm">
        @csrf

        {{-- Section: Railway / Office --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-5">
            <h2 class="text-base font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Railway & Office Details</h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Railway Zone</label>
                    <input type="text" name="railway_zone" value="{{ old('railway_zone', $document->railway_zone) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. Eastern Railway">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Division</label>
                    <input type="text" name="division" value="{{ old('division', $document->division) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. Howrah Division - ENGG">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Office</label>
                    <input type="text" name="office" value="{{ old('office', $document->office) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Office of the Sr. Divl. Engineer">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Letter Number</label>
                    <input type="text" name="letter_number" value="{{ old('letter_number', $document->letter_number) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Letter Date</label>
                    <input type="date" name="letter_date" value="{{ old('letter_date', $document->letter_date?->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Signed By</label>
                    <input type="text" name="signed_by" value="{{ old('signed_by', $document->signed_by) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. SHIVRATAN KUMAR, Sr.DEN1HWH">
                </div>
            </div>
        </div>

        {{-- Section: Contractor --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-5">
            <h2 class="text-base font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Contractor Details</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Contractor Name</label>
                    <input type="text" name="contractor_name" value="{{ old('contractor_name', $document->contractor_name) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="M/s ...">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Contractor Address</label>
                    <textarea name="contractor_address" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Full address...">{{ old('contractor_address', $document->contractor_address) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section: Tender Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-5">
            <h2 class="text-base font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Tender & Bid Information</h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tender Number</label>
                    <input type="text" name="tender_number" value="{{ old('tender_number', $document->tender_number) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. 117_2024-25">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tender Closing Date</label>
                    <input type="date" name="tender_closing_date" value="{{ old('tender_closing_date', $document->tender_closing_date?->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Completion Period</label>
                    <input type="text" name="completion_period" value="{{ old('completion_period', $document->completion_period) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. 6 months">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Bid ID</label>
                    <input type="text" name="bid_id" value="{{ old('bid_id', $document->bid_id) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. 17076902">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Bid Date</label>
                    <input type="date" name="bid_date" value="{{ old('bid_date', $document->bid_date?->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Negotiation Bid IDs</label>
                    <input type="text" name="negotiation_bid_ids" value="{{ old('negotiation_bid_ids', $document->negotiation_bid_ids) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. 17163489,17234299">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Work Description</label>
                    <textarea name="work_description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Description of the work...">{{ old('work_description', $document->work_description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section: Financial --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-5">
            <h2 class="text-base font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Financial Details</h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Contract Value (₹)</label>
                    <input type="number" step="0.01" name="contract_value" value="{{ old('contract_value', $document->contract_value) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Net Bid Value (₹)</label>
                    <input type="number" step="0.01" name="net_bid_value" value="{{ old('net_bid_value', $document->net_bid_value) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Total Advertised Value (₹)</label>
                    <input type="number" step="0.01" name="total_advertised_value" value="{{ old('total_advertised_value', $document->total_advertised_value) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Earnest Money (₹)</label>
                    <input type="number" step="0.01" name="earnest_money" value="{{ old('earnest_money', $document->earnest_money) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Performance Guarantee (₹)</label>
                    <input type="number" step="0.01" name="performance_guarantee" value="{{ old('performance_guarantee', $document->performance_guarantee) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Bid Rate (%)</label>
                    <input type="number" step="0.01" name="bid_rate_percentage" value="{{ old('bid_rate_percentage', $document->bid_rate_percentage) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. 12.98">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Rebate on Total Value (₹)</label>
                    <input type="number" step="0.01" name="rebate_on_total_value" value="{{ old('rebate_on_total_value', $document->rebate_on_total_value) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">IREPS Reference ID</label>
                    <input type="text" name="ireps_reference_id" value="{{ old('ireps_reference_id', $document->ireps_reference_id) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. NE945423246865">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Contract Value in Words</label>
                    <textarea name="contract_value_words" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Rupees One Crore...">{{ old('contract_value_words', $document->contract_value_words) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section: Work Items --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-5">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-700">Work Items (Schedule A & B)</h2>
                <button type="button" onclick="addWorkItem()"
                    class="text-xs bg-blue-700 text-white px-3 py-1.5 rounded-lg hover:bg-blue-800 transition-colors">
                    + Add Item
                </button>
            </div>

            <div id="workItemsContainer" class="space-y-3">
                @forelse($document->workItems as $index => $item)
                    <div class="work-item grid grid-cols-12 gap-2 bg-gray-50 p-3 rounded-lg items-start" data-index="{{ $index }}">
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                        <div class="col-span-2">
                            <label class="text-xs text-gray-500">Schedule</label>
                            <select name="items[{{ $index }}][schedule_name]" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="Schedule A" @selected($item->schedule_name === 'Schedule A')>Sch A</option>
                                <option value="Schedule B" @selected($item->schedule_name === 'Schedule B')>Sch B</option>
                                <option value="Schedule B1" @selected($item->schedule_name === 'Schedule B1')>Sch B1</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="text-xs text-gray-500">Item No</label>
                            <input type="text" name="items[{{ $index }}][item_number]" value="{{ $item->item_number }}"
                                class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="1">
                        </div>
                        <div class="col-span-1">
                            <label class="text-xs text-gray-500">Code</label>
                            <input type="text" name="items[{{ $index }}][item_code]" value="{{ $item->item_code }}"
                                class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="135011">
                        </div>
                        <div class="col-span-3">
                            <label class="text-xs text-gray-500">Description</label>
                            <textarea name="items[{{ $index }}][description]" rows="2"
                                class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="Work description...">{{ $item->description }}</textarea>
                        </div>
                        <div class="col-span-1">
                            <label class="text-xs text-gray-500">Qty</label>
                            <input type="number" step="0.001" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}"
                                class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div class="col-span-1">
                            <label class="text-xs text-gray-500">Unit</label>
                            <input type="text" name="items[{{ $index }}][unit]" value="{{ $item->unit }}"
                                class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="TRM">
                        </div>
                        <div class="col-span-2">
                            <label class="text-xs text-gray-500">Advised Value (₹)</label>
                            <input type="number" step="0.01" name="items[{{ $index }}][advised_value]" value="{{ $item->advised_value }}"
                                class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div class="col-span-1 flex items-end justify-end pb-1">
                            <button type="button" onclick="removeItem(this)" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4" id="noItemsMsg">No work items yet. Click "+ Add Item" to add manually.</p>
                @endforelse
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-700 mb-3">Notes</h2>
            <textarea name="notes" rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Any additional notes about this tender...">{{ old('notes', $document->notes) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-8 py-2.5 rounded-lg text-sm font-medium transition-colors">
                ✓ Confirm & Save
            </button>
            <a href="{{ route('documents.index') }}"
                class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                Save Later
            </a>
        </div>
    </form>

    <script>
        let itemIndex = {{ $document->workItems->count() }};

        function addWorkItem() {
            const noMsg = document.getElementById('noItemsMsg');
            if (noMsg) noMsg.remove();

            const container = document.getElementById('workItemsContainer');
            const div = document.createElement('div');
            div.className = 'work-item grid grid-cols-12 gap-2 bg-gray-50 p-3 rounded-lg items-start';
            div.innerHTML = `
                <div class="col-span-2">
                    <label class="text-xs text-gray-500">Schedule</label>
                    <select name="items[${itemIndex}][schedule_name]" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="Schedule A">Sch A</option>
                        <option value="Schedule B">Sch B</option>
                        <option value="Schedule B1">Sch B1</option>
                    </select>
                </div>
                <div class="col-span-1">
                    <label class="text-xs text-gray-500">Item No</label>
                    <input type="text" name="items[${itemIndex}][item_number]" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="1">
                </div>
                <div class="col-span-1">
                    <label class="text-xs text-gray-500">Code</label>
                    <input type="text" name="items[${itemIndex}][item_code]" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="135011">
                </div>
                <div class="col-span-3">
                    <label class="text-xs text-gray-500">Description</label>
                    <textarea name="items[${itemIndex}][description]" rows="2" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Work description..."></textarea>
                </div>
                <div class="col-span-1">
                    <label class="text-xs text-gray-500">Qty</label>
                    <input type="number" step="0.001" name="items[${itemIndex}][quantity]" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="col-span-1">
                    <label class="text-xs text-gray-500">Unit</label>
                    <input type="text" name="items[${itemIndex}][unit]" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="TRM">
                </div>
                <div class="col-span-2">
                    <label class="text-xs text-gray-500">Advised Value (₹)</label>
                    <input type="number" step="0.01" name="items[${itemIndex}][advised_value]" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="col-span-1 flex items-end justify-end pb-1">
                    <button type="button" onclick="removeItem(this)" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                </div>
            `;
            container.appendChild(div);
            itemIndex++;
        }

        function removeItem(btn) {
            btn.closest('.work-item').remove();
        }
    </script>
@endsection
