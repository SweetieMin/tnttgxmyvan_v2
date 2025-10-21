<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    protected $fillable = [
        'academic_year_id',
        'ordering',
        'description',
        'type',
        'points',
    ];

    protected $casts = [
        'points' => 'integer',
        'ordering' => 'integer',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'regulation_role',
            'regulation_id',
            'role_id'
        );
    }

    public function regulationRoles()
    {
        return $this->hasMany(RegulationRole::class);
    }
}
