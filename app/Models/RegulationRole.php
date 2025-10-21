<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegulationRole extends Model
{
    protected $fillable = [
        'regulation_id',
        'role_id',
    ];

    /**
     * Mỗi RegulationRole thuộc về 1 Regulation
     */
    public function regulation()
    {
        return $this->belongsTo(Regulation::class);
    }

    /**
     * Mỗi RegulationRole thuộc về 1 Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
