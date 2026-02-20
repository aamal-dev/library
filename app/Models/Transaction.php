<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'bill_id',
        'book_id',
        'price',
        'mortgage',
        'extra_price',
        'delivered_at',
        'due_date',
        'returned_at',
        'customer_return_amount',
        'status',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
        'price' => 'decimal:2',
        'mortgage' => 'decimal:2',
        'extra_price' => 'decimal:2',
    ];

    public function processOverdueStep($dailyFineAmount)
    {
        $this->extra_price += $dailyFineAmount;

        if($this->extra_price >= $this->mortgage){
            $this->status = 'expired';

            $this->book()->decrement('total_copies');
        }

        $this->save();
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}