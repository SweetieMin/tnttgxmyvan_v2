<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_date',
        'title',
        'description',
        'type',
        'amount',
        'file_name',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'integer',
    ];

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
