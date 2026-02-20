<?php

namespace App\Models;

use App\Enums\WaitingListStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitingList extends Model
{
    protected $fillable = [
        'customer_id',
        'book_id',
        'status',
    ];

    protected function casts()
    {
        return [
            'status' => WaitingListStatusEnum::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
