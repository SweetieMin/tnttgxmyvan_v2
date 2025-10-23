<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'ordering',
        'course',
        'sector',
        'description',
    ];

    /**
     * Kiểu dữ liệu (cast) cho các trường
     */
    protected $casts = [
        'ordering' => 'integer',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
