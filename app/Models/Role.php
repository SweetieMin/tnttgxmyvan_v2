<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
        'ordering',
        'type'
    ];

    protected $casts = [
        'ordering' => 'integer',
    ];

    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'system' => 'Hệ thống',
            'spiritual' => 'Linh Hướng',
            'catechist' => 'Giáo Lý Viên',
            'scouter' => 'Huynh Dự Đội Trưởng',
            'children' => 'Thiếu Nhi',
        };
    }

    public function getTypeColorAttribute()
    {
        return match ($this->type) {
            'system' => 'zinc',
            'spiritual' => 'purple',
            'catechist' => 'orange',
            'scouter' => 'rose',
            'children' => 'emerald',
        };
    }

    /**
     * Những vai trò CẤP DƯỚI mà vai trò này quản lý.
     * Ví dụ: Xứ Đoàn Trưởng → Trưởng Ngành, Huynh Trưởng, Thiếu Nhi
     */
    public function subRoles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_hierarchies',
            'role_id',
            'manages_role_id'
        );
    }

    /**
     * Những vai trò CẤP TRÊN quản lý vai trò này.
     * Ví dụ: Huynh Trưởng → được Trưởng Ngành và Xứ Đoàn Trưởng quản lý
     */
    public function superRoles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_hierarchies',
            'manages_role_id',
            'role_id'
        );
    }

    /**
     * Mối quan hệ trung gian (RoleHierarchy) – dùng nếu cần thêm chi tiết
     * Ví dụ: note, cấp độ quản lý, vùng, ...
     */
    public function roleHierarchies()
    {
        return $this->belongsToMany(
            Role::class,
            'role_hierarchies',     // tên bảng pivot
            'role_id',              // khóa ngoại trỏ từ vai trò hiện tại
            'manages_role_id'       // khóa ngoại trỏ đến vai trò mà nó quản lý
        )->withTimestamps();
    }

    public function managedByHierarchies()
    {
        return $this->hasMany(RoleHierarchy::class, 'manages_role_id');
    }

    /**
     * Mỗi vai trò có thể có nhiều quy định
     */

    public function regulations()
    {
        return $this->belongsToMany(
            Regulation::class,   // model liên quan
            'regulation_roles',  // tên bảng pivot
            'role_id',           // khóa ngoại trỏ từ bảng role
            'regulation_id'      // khóa ngoại trỏ tới bảng regulation
        )->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
