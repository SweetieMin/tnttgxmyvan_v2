<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Models\Course;
use App\Repositories\BaseRepository;
use App\Repositories\Traits\HasSortableOrdering;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    use HasSortableOrdering;

    /**
     * CourseRepository riêng với ordering theo academic_year_id
     * 
     * ✅ Tính năng:
     * - Tự động ordering theo academic_year_id khi create/delete
     * - Hỗ trợ drag-drop sortable
     * - Search và filter theo năm học
     * - Group ordering theo academic_year_id
     */
    protected ?string $groupColumn = 'academic_year_id';

    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    /**
     * Lấy danh sách course với search và filter theo năm học
     */
    public function courseWithSearchAndYear(?string $search = null, ?int $year = null): LengthAwarePaginator
    {
        return $this->safeExecute(function () use ($search, $year) {
            $query = $this->model->with(['academicYear', 'program']);

            // Filter theo năm học
            if ($year) {
                $query->where('academic_year_id', $year);
            }

            // Search theo tên course
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('course', 'like', "%{$search}%");
                });
            }



            // Sắp xếp theo ordering trong từng năm học
            return $query->orderBy('academic_year_id')
                ->orderBy('ordering')
                ->paginate(15);
        }, 'Không thể lấy danh sách course với tìm kiếm và lọc.');
    }

    /**
     * Lấy danh sách course theo năm học với ordering
     */
    public function getByAcademicYear(int $academicYearId): Collection
    {
        return $this->safeExecute(function () use ($academicYearId) {
            return $this->model->where('academic_year_id', $academicYearId)
                ->orderBy('ordering')
                ->get();
        }, "Không thể lấy danh sách course theo năm học {$academicYearId}.");
    }

    /**
     * Lấy course với relations
     */
    public function findWithRelations(int|string $id): ?Course
    {
        return $this->safeExecute(function () use ($id) {
            return $this->model->with(['academicYear', 'program'])->find($id);
        }, "Không thể lấy course với quan hệ (ID: {$id}).");
    }

    /**
     * Tạo course mới với auto ordering
     */
    public function create(array $data): Course
    {
        return $this->safeExecute(function () use ($data) {
            $data = $this->prepareData($data);

            // Tự động gán ordering trong năm học
            $data = $this->autoOrdering($data, $this->groupColumn);

            return $this->model->create($data);
        }, 'Không thể tạo course mới.');
    }

    /**
     * Cập nhật course
     */
    public function update(int|string $id, array $data): bool
    {
        return $this->safeExecute(function () use ($id, $data) {
            $data = $this->prepareData($data);

            $course = $this->find($id);
            if (!$course) {
                throw new \Exception("Không tìm thấy course để cập nhật (ID: {$id}).");
            }

            // Nếu thay đổi academic_year_id, cần reorder
            $oldAcademicYearId = $course->academic_year_id;
            $newAcademicYearId = $data['academic_year_id'] ?? $oldAcademicYearId;

            $updated = $course->update($data);

            // Nếu thay đổi năm học, reorder cả 2 năm
            if ($updated && $oldAcademicYearId != $newAcademicYearId) {
                $this->reorder($this->groupColumn);
            }

            return $updated;
        }, 'Không thể cập nhật course.');
    }

    /**
     * Xóa course và reorder
     */
    public function delete(int|string $id): bool
    {
        return $this->safeExecute(function () use ($id) {
            $course = $this->find($id);
            if (!$course) {
                throw new \Exception("Không tìm thấy course để xóa (ID: {$id}).");
            }

            $academicYearId = $course->academic_year_id;
            $deleted = (bool) $course->delete();

            // Sau khi xóa: reorder trong năm học đó
            if ($deleted) {
                $this->reorderByGroup($academicYearId);
            }

            return $deleted;
        }, 'Không thể xóa course.');
    }

    /**
     * Cập nhật ordering sau drag-drop
     */
    public function updateCourseOrdering(array $orderedIds, int $academicYearId): bool
    {
        return $this->updateSortableOrdering($orderedIds, $academicYearId);
    }

    /**
     * Reorder tất cả course trong một năm học
     */
    public function reorderByGroup(int $academicYearId): void
    {
        $this->safeExecute(function () use ($academicYearId) {
            $courses = $this->model->where('academic_year_id', $academicYearId)
                ->orderBy('ordering')
                ->get();

            foreach ($courses as $index => $course) {
                $course->update(['ordering' => $index + 1]);
            }
        }, "Không thể reorder course trong năm học {$academicYearId}.");
    }

    /**
     * Lấy ordering tiếp theo trong năm học
     */
    public function getNextOrdering(int $academicYearId): int
    {
        return $this->safeExecute(function () use ($academicYearId) {
            $maxOrdering = $this->model->where('academic_year_id', $academicYearId)
                ->max('ordering') ?? 0;
            return $maxOrdering + 1;
        }, "Không thể lấy ordering tiếp theo cho năm học {$academicYearId}.");
    }

    /**
     * Kiểm tra course có tồn tại trong năm học không
     */
    public function existsInAcademicYear(string $course, int $academicYearId, ?int $excludeId = null): bool
    {
        return $this->safeExecute(function () use ($course, $academicYearId, $excludeId) {
            $query = $this->model->where('course', $course)
                ->where('academic_year_id', $academicYearId);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            return $query->exists();
        }, 'Không thể kiểm tra course tồn tại.');
    }

    /**
     * Chuẩn hóa dữ liệu trước khi lưu
     */
    protected function prepareData(array $data): array
    {
        return [
            'academic_year_id' => (int) ($data['academic_year_id'] ?? 0),
            'program_id' => (int) ($data['program_id'] ?? 0),
            'course' => trim($data['course'] ?? ''),
        ];
    }

    /**
     * Lấy danh sách course để sortable
     */
    public function getSortableCourses(int $academicYearId): Collection
    {
        return $this->safeExecute(function () use ($academicYearId) {
            return $this->model->where('academic_year_id', $academicYearId)
                ->orderBy('ordering')
                ->get(['id', 'course', 'ordering', 'academic_year_id']);
        }, "Không thể lấy danh sách course để sortable cho năm học {$academicYearId}.");
    }

    /**
     * Di chuyển course lên trên
     */
    public function moveCourseUp(int $courseId, int $academicYearId): bool
    {
        return $this->moveUp($courseId, $academicYearId);
    }

    /**
     * Di chuyển course xuống dưới
     */
    public function moveCourseDown(int $courseId, int $academicYearId): bool
    {
        return $this->moveDown($courseId, $academicYearId);
    }

    /**
     * Di chuyển course đến vị trí cụ thể
     */
    public function moveCourseToPosition(int $courseId, int $newPosition, int $academicYearId): bool
    {
        return $this->moveToPosition($courseId, $newPosition, $academicYearId);
    }

    /**
     * Validate và fix ordering nếu cần
     */
    public function validateAndFixOrdering(int $academicYearId): array
    {
        $issues = $this->validateOrdering($academicYearId);

        if (!empty($issues)) {
            $this->fixOrdering($academicYearId);
        }

        return $issues;
    }
}
