<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Ở đây bạn định nghĩa tất cả các channel mà ứng dụng có thể broadcast đến.
| Mỗi channel đều có hàm callback để xác định user nào được phép truy cập.
|
*/

/**
 * 🔹 Channel riêng cho từng user
 * Dành cho thông báo cá nhân, tin nhắn riêng, v.v.
 * Ví dụ: private-user.5
 */
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


/**
 * 🔹 Channel cho role (nhiều-nhiều)
 * Dành cho broadcast theo quyền hạn, ví dụ "BOD", "Teacher", "Thieu Nhi"
 * Ví dụ: private-role.BOD
 */
Broadcast::channel('role.{roleName}', function ($user, $roleName) {
    // Nếu bạn dùng Spatie Role
    return $user->roles()->where('name', $roleName)->exists();
});


/**
 * 🔹 Channel cho course (nhiều-nhiều)
 * Dành cho broadcast đến toàn bộ học viên và giáo viên trong 1 lớp học
 * Ví dụ: private-course.12
 */
Broadcast::channel('course.{courseId}', function ($user, $courseId) {
    return $user->courses()->where('id', $courseId)->exists();
});


/**
 * 🔹 Channel cho sector (nhiều-nhiều)
 * Dành cho broadcast đến toàn bộ thành viên thuộc một ngành / khu vực
 * Ví dụ: private-sector.Nghia hoặc private-sector.3
 */
Broadcast::channel('sector.{sectorId}', function ($user, $sectorId) {
    return $user->sectors()->where('id', $sectorId)->exists();
});
