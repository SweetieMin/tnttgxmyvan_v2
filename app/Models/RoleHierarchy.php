<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHierarchy extends Model
{
    protected $fillable = [
        'role_id',
        'manages_role_id',
        'note',
    ];

    public function parentRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function childRole()
    {
        return $this->belongsTo(Role::class, 'manages_role_id');
    }
}
