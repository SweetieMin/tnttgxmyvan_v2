<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function all();
    public function find(int|string $id);
    public function paginate(int $perPage = 15);
    public function create(array $data);
    public function update(int|string $id, array $data);
    public function delete(int|string $id);

    /**
     * Lấy danh sách course với search và filter theo năm học
     */
    public function courseWithSearchAndYear(?string $search = null, ?int $year = null): LengthAwarePaginator;

    /**
     * Lấy danh sách course theo năm học với ordering
     */
    public function getByAcademicYear(int $academicYearId): Collection;

    /**
     * Lấy course với relations
     */
    public function findWithRelations(int|string $id): ?\App\Models\Course;

    /**
     * Cập nhật ordering sau drag-drop
     */
    public function updateCourseOrdering(array $orderedIds, int $academicYearId): bool;

    /**
     * Reorder tất cả course trong một năm học
     */
    public function reorderByGroup(int $academicYearId): void;

    /**
     * Lấy ordering tiếp theo trong năm học
     */
    public function getNextOrdering(int $academicYearId): int;

    /**
     * Kiểm tra course có tồn tại trong năm học không
     */
    public function existsInAcademicYear(string $course, int $academicYearId, ?int $excludeId = null): bool;

    /**
     * Lấy danh sách course để sortable
     */
    public function getSortableCourses(int $academicYearId): Collection;

    /**
     * Di chuyển course lên trên
     */
    public function moveCourseUp(int $courseId, int $academicYearId): bool;

    /**
     * Di chuyển course xuống dưới
     */
    public function moveCourseDown(int $courseId, int $academicYearId): bool;

    /**
     * Di chuyển course đến vị trí cụ thể
     */
    public function moveCourseToPosition(int $courseId, int $newPosition, int $academicYearId): bool;

    /**
     * Validate và fix ordering nếu cần
     */
    public function validateAndFixOrdering(int $academicYearId): array;
}
