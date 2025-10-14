<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionFile extends Model
{
    protected $fillable = [
        'transaction_id',
        'file_path',
        'file_name',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getFilePathAttribute($value)
    {
        return asset('storage/' . $value);
    }

    public function getStoragePathAttribute()
    {
        return 'transactions/' . basename($this->file_path);
    }

    public function getFullStoragePathAttribute()
    {
        return storage_path('app/public/transactions/' . basename($this->file_path));
    }
}
