<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenderDocument extends Model
{
    protected $fillable = [
        'user_id',
        'original_filename',
        'file_path',
        'status',
        'railway_zone',
        'division',
        'office',
        'letter_number',
        'letter_date',
        'contractor_name',
        'contractor_address',
        'tender_number',
        'tender_closing_date',
        'work_description',
        'bid_id',
        'bid_date',
        'negotiation_bid_ids',
        'contract_value',
        'contract_value_words',
        'earnest_money',
        'ireps_reference_id',
        'performance_guarantee',
        'net_bid_value',
        'bid_rate_percentage',
        'rebate_on_total_value',
        'total_advertised_value',
        'completion_period',
        'signed_by',
        'notes',
        'extracted_at',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'letter_date' => 'date',
            'tender_closing_date' => 'datetime',
            'bid_date' => 'datetime',
            'contract_value' => 'decimal:2',
            'earnest_money' => 'decimal:2',
            'performance_guarantee' => 'decimal:2',
            'net_bid_value' => 'decimal:2',
            'bid_rate_percentage' => 'decimal:2',
            'rebate_on_total_value' => 'decimal:2',
            'total_advertised_value' => 'decimal:2',
            'extracted_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<TenderWorkItem, $this> */
    public function workItems(): HasMany
    {
        return $this->hasMany(TenderWorkItem::class);
    }

    /** @return HasMany<TenderWorkItem, $this> */
    public function scheduleAItems(): HasMany
    {
        return $this->hasMany(TenderWorkItem::class)
            ->where('schedule_name', 'Schedule A')
            ->where('is_sub_item', false);
    }

    /** @return HasMany<TenderWorkItem, $this> */
    public function scheduleBItems(): HasMany
    {
        return $this->hasMany(TenderWorkItem::class)
            ->where('schedule_name', 'Schedule B')
            ->where('is_sub_item', false);
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isExtracted(): bool
    {
        return in_array($this->status, ['extracted', 'confirmed']);
    }
}
