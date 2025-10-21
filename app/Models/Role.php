<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
        'ordering',
    ];

    protected $casts = [
        'ordering' => 'integer',
    ];

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
        return $this->hasMany(RoleHierarchy::class, 'role_id');
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
            Regulation::class,
            'regulation_role',
            'role_id',
            'regulation_id'
        );
    }
}
