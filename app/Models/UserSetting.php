<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $table = 'user_settings';

    protected $fillable = [
        'user_id',
        'notification_sound',
    ];

    protected $casts = [
        'notification_sound' => 'boolean',
    ];

    /**
     * Relationship with User
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
