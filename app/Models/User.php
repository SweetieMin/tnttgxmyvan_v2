<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'christian_name',
        'last_name',
        'name',
        'birthday',
        'account_code',
        'email',
        'password',
        'status_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
        'christian_full_name',
        'short_name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'birthday' => 'date',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the full name of the user.
     * Example: "Nguyễn Văn A"
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->last_name} {$this->name}");
    }

    /**
     * Get the Christian full name of the user.
     * Example: "Phêrô Nguyễn Văn A"
     */
    public function getChristianFullNameAttribute(): string
    {
        return trim("{$this->christian_name} {$this->last_name} {$this->name}");
    }

    /**
     * Get the short name of the user.
     * Example: "Văn A"
     */
    public function getShortNameAttribute(): string
    {
        return trim($this->name);
    }

    /**
     * Relationship with User Setting
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    /** 
     * Relationship with User Detail
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function details()
    {
        return $this->hasOne(UserDetail::class);
    }

    /**
     * Relationship with User Parent
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parents()
    {
        return $this->hasOne(UserParent::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class)->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
}
