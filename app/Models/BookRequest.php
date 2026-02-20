<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    protected $fillable = [
        'book_title',
        'author_name',
        'status',
        'admin_note',
        'customer_id',
    ];

    function customer(){
        return $this->belongsTo(Customer::class);
    }
}
