<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $fillable = [
        'academic_year_id',
        'program_id',
        'ordering',
        'sector',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
