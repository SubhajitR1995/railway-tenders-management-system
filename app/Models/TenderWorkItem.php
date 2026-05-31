<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenderWorkItem extends Model
{
    protected $fillable = [
        'tender_document_id',
        'schedule_name',
        'item_number',
        'item_code',
        'description',
        'quantity',
        'item_quantity',
        'unit',
        'escl_rate',
        'advised_value',
        'bid_rate_unit_rate',
        'bid_amount',
        'is_sub_item',
        'parent_item_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'item_quantity' => 'decimal:3',
            'escl_rate' => 'decimal:4',
            'advised_value' => 'decimal:2',
            'bid_rate_unit_rate' => 'decimal:4',
            'bid_amount' => 'decimal:2',
            'is_sub_item' => 'boolean',
        ];
    }

    /** @return BelongsTo<TenderDocument, $this> */
    public function tenderDocument(): BelongsTo
    {
        return $this->belongsTo(TenderDocument::class);
    }

    /** @return BelongsTo<TenderWorkItem, $this> */
    public function parentItem(): BelongsTo
    {
        return $this->belongsTo(TenderWorkItem::class, 'parent_item_id');
    }

    /** @return HasMany<TenderWorkItem, $this> */
    public function subItems(): HasMany
    {
        return $this->hasMany(TenderWorkItem::class, 'parent_item_id');
    }
}
