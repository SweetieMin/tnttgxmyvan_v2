<?php

namespace App\Repositories\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * 📊 Trait: HasSortableOrdering
 * 
 * Trait này cung cấp cơ chế drag-drop sortable cho các model có ordering.
 * Được thiết kế riêng cho Course với group theo academic_year_id.
 * 
 * ✅ Tính năng chính:
 * - Hỗ trợ drag-drop sortable
 * - Group ordering theo academic_year_id
 * - Batch update ordering
 * - Validation ordering
 * - Rollback khi có lỗi
 *
 * 👉 Sử dụng trong CourseRepository để hỗ trợ sortable UI.
 */
trait HasSortableOrdering
{
    /**
     * Cập nhật ordering từ drag-drop với group
     */
    public function updateSortableOrdering(array $orderedIds, int $academicYearId): bool
    {
        if (empty($orderedIds)) {
            return true;
        }

        try {
            DB::beginTransaction();

            // Validate tất cả IDs thuộc cùng academic_year_id
            $validIds = $this->model->where('academic_year_id', $academicYearId)
                                   ->whereIn('id', $orderedIds)
                                   ->pluck('id')
                                   ->toArray();

            if (count($validIds) !== count($orderedIds)) {
                throw new \Exception('Một số course không thuộc năm học này.');
            }

            // Cập nhật ordering
            foreach ($orderedIds as $index => $id) {
                $this->model->where('id', $id)
                           ->where('academic_year_id', $academicYearId)
                           ->update(['ordering' => $index + 1]);
            }

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();
            
            Log::error("Lỗi khi cập nhật sortable ordering: " . $e->getMessage(), [
                'repository' => static::class,
                'model' => get_class($this->model),
                'academic_year_id' => $academicYearId,
                'ordered_ids' => $orderedIds,
            ]);
            
            return false;
        }
    }

    /**
     * Lấy danh sách course theo năm học để sortable
     */
    public function getSortableCourses(int $academicYearId): \Illuminate\Database\Eloquent\Collection
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
    public function moveUp(int $courseId, int $academicYearId): bool
    {
        return $this->safeExecute(function () use ($courseId, $academicYearId) {
            $course = $this->model->where('id', $courseId)
                                 ->where('academic_year_id', $academicYearId)
                                 ->first();

            if (!$course) {
                throw new \Exception('Không tìm thấy course.');
            }

            $previousCourse = $this->model->where('academic_year_id', $academicYearId)
                                         ->where('ordering', '<', $course->ordering)
                                         ->orderBy('ordering', 'desc')
                                         ->first();

            if (!$previousCourse) {
                return true; // Đã ở vị trí đầu
            }

            // Swap ordering
            $tempOrdering = $course->ordering;
            $course->update(['ordering' => $previousCourse->ordering]);
            $previousCourse->update(['ordering' => $tempOrdering]);

            return true;
        }, 'Không thể di chuyển course lên trên.');
    }

    /**
     * Di chuyển course xuống dưới
     */
    public function moveDown(int $courseId, int $academicYearId): bool
    {
        return $this->safeExecute(function () use ($courseId, $academicYearId) {
            $course = $this->model->where('id', $courseId)
                                 ->where('academic_year_id', $academicYearId)
                                 ->first();

            if (!$course) {
                throw new \Exception('Không tìm thấy course.');
            }

            $nextCourse = $this->model->where('academic_year_id', $academicYearId)
                                     ->where('ordering', '>', $course->ordering)
                                     ->orderBy('ordering', 'asc')
                                     ->first();

            if (!$nextCourse) {
                return true; // Đã ở vị trí cuối
            }

            // Swap ordering
            $tempOrdering = $course->ordering;
            $course->update(['ordering' => $nextCourse->ordering]);
            $nextCourse->update(['ordering' => $tempOrdering]);

            return true;
        }, 'Không thể di chuyển course xuống dưới.');
    }

    /**
     * Di chuyển course đến vị trí cụ thể
     */
    public function moveToPosition(int $courseId, int $newPosition, int $academicYearId): bool
    {
        return $this->safeExecute(function () use ($courseId, $newPosition, $academicYearId) {
            $course = $this->model->where('id', $courseId)
                                 ->where('academic_year_id', $academicYearId)
                                 ->first();

            if (!$course) {
                throw new \Exception('Không tìm thấy course.');
            }

            $totalCourses = $this->model->where('academic_year_id', $academicYearId)->count();
            
            if ($newPosition < 1 || $newPosition > $totalCourses) {
                throw new \Exception('Vị trí không hợp lệ.');
            }

            $oldPosition = $course->ordering;

            if ($oldPosition === $newPosition) {
                return true; // Không cần thay đổi
            }

            DB::beginTransaction();

            try {
                if ($oldPosition < $newPosition) {
                    // Di chuyển xuống: giảm ordering của các course ở giữa
                    $this->model->where('academic_year_id', $academicYearId)
                               ->where('ordering', '>', $oldPosition)
                               ->where('ordering', '<=', $newPosition)
                               ->decrement('ordering');
                } else {
                    // Di chuyển lên: tăng ordering của các course ở giữa
                    $this->model->where('academic_year_id', $academicYearId)
                               ->where('ordering', '>=', $newPosition)
                               ->where('ordering', '<', $oldPosition)
                               ->increment('ordering');
                }

                $course->update(['ordering' => $newPosition]);

                DB::commit();
                return true;

            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }, 'Không thể di chuyển course đến vị trí mới.');
    }

    /**
     * Validate ordering trong năm học
     */
    public function validateOrdering(int $academicYearId): array
    {
        $issues = [];
        
        $courses = $this->model->where('academic_year_id', $academicYearId)
                              ->orderBy('ordering')
                              ->get();

        $expectedOrdering = 1;
        foreach ($courses as $course) {
            if ($course->ordering !== $expectedOrdering) {
                $issues[] = "Course ID {$course->id} có ordering {$course->ordering}, mong đợi {$expectedOrdering}";
            }
            $expectedOrdering++;
        }

        return $issues;
    }

    /**
     * Fix ordering nếu có vấn đề
     */
    public function fixOrdering(int $academicYearId): bool
    {
        return $this->safeExecute(function () use ($academicYearId) {
            $courses = $this->model->where('academic_year_id', $academicYearId)
                                  ->orderBy('ordering')
                                  ->get();

            foreach ($courses as $index => $course) {
                $course->update(['ordering' => $index + 1]);
            }

            return true;
        }, "Không thể fix ordering cho năm học {$academicYearId}.");
    }
}
