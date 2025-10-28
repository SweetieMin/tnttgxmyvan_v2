<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'ordering',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function getSourceLabelAttribute(): string
    {
        return $this->is_system ? 'Hệ thống' : 'Tuỳ chỉnh';
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transaction_item_id');
    }
}
