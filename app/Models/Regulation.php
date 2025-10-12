<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    protected $fillable=[
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
}
