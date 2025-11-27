<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_date',
        'transaction_item_id',
        'description',
        'type',
        'amount',
        'file_name',
        'in_charge',
        'status',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'integer',
    ];

    // Format số tiền hiển thị
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', '.') . ' ₫';
    }

    public function getFormTransactionDateAttribute(): string
    {
        return optional($this->transaction_date)->format('Y-m-d');
    }

    // Format số tiền hiển thị
    public function getFormattedTransactionDateAttribute(): string
    {
        return $this->transaction_date
            ? $this->transaction_date->format('d/m/Y')
            : '-';
    }

    // Hiển thị nhãn loại giao dịch (thu / chi)
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'income' ? 'Thu' : 'Chi';
    }

    // Màu hiển thị (nếu dùng trong bảng Livewire)
    public function getTypeColorAttribute(): string
    {
        return $this->type === 'income' ? 'text-green-600' : 'text-red-600';
    }

    public function item()
    {
        return $this->belongsTo(TransactionItem::class, 'transaction_item_id');
    }
}
