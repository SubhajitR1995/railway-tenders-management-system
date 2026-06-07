<?php

namespace App\Models;

use Database\Factories\TenderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tender extends Model
{
    /** @use HasFactory<TenderFactory> */
    use HasFactory;

    protected $fillable = [
        'tender_number',
        'title',
        'description',
        'requirements',
        'category_id',
        'created_by',
        'budget',
        'submission_deadline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'submission_deadline' => 'date',
        ];
    }

    /** @return BelongsTo<TenderCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TenderCategory::class, 'category_id');
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isOpen(): bool
    {
        return $this->status === 'published' && $this->submission_deadline->isFuture();
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function isAwarded(): bool
    {
        return $this->status === 'awarded';
    }

    public static function generateTenderNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;

        return sprintf('RLW-%s-%04d', $year, $count);
    }
}
