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
            'regulation_roles',
            'regulation_id',   // khóa ngoại của Regulation trong pivot
            'role_id'          // khóa ngoại của Role trong pivot
        )->withTimestamps();
    }

    public function regulationApplyRole()
    {
        return $this->belongsToMany(
            Role::class,
            'regulation_roles',    
            'regulation_id',
            'role_id',         
        )->withTimestamps();
    }

    public function regulationRoles()
    {
        return $this->hasMany(RegulationRole::class);
    }
}
