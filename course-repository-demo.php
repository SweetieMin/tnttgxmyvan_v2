<?php

/**
 * 🎯 Demo Sử Dụng CourseRepository
 * 
 * File này minh họa cách sử dụng CourseRepository với đầy đủ tính năng:
 * - Auto ordering theo academic_year_id
 * - Drag-drop sortable
 * - Search & filter
 * - Validation
 */

namespace App\Demos;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Models\Course;

class CourseRepositoryDemo
{
    protected CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * 🎯 Demo 1: Tạo Course Mới với Auto Ordering
     */
    public function demoCreateCourse()
    {
        echo "=== Demo 1: Tạo Course Mới ===\n";

        // Dữ liệu course mới
        $data = [
            'academic_year_id' => 1,
            'program_id' => 2,
            'course' => 'Lớp Giáo Lý A'
        ];

        // Tạo course - ordering sẽ được gán tự động
        $course = $this->courseRepository->create($data);
        
        echo "✅ Course tạo thành công:\n";
        echo "- ID: {$course->id}\n";
        echo "- Tên: {$course->course}\n";
        echo "- Năm học: {$course->academic_year_id}\n";
        echo "- Ordering: {$course->ordering}\n\n";
    }

    /**
     * 🎯 Demo 2: Tìm Kiếm và Lọc Course
     */
    public function demoSearchAndFilter()
    {
        echo "=== Demo 2: Tìm Kiếm và Lọc ===\n";

        // Tìm kiếm course theo tên
        $courses = $this->courseRepository->courseWithSearchAndYear(
            search: 'Giáo Lý',
            year: 1
        );

        echo "✅ Kết quả tìm kiếm:\n";
        foreach ($courses as $course) {
            echo "- {$course->course} (Ordering: {$course->ordering})\n";
        }
        echo "\n";
    }

    /**
     * 🎯 Demo 3: Drag-Drop Sortable
     */
    public function demoSortableOrdering()
    {
        echo "=== Demo 3: Drag-Drop Sortable ===\n";

        $academicYearId = 1;
        
        // Lấy danh sách course hiện tại
        $courses = $this->courseRepository->getSortableCourses($academicYearId);
        
        echo "📋 Thứ tự hiện tại:\n";
        foreach ($courses as $course) {
            echo "- {$course->ordering}. {$course->course}\n";
        }

        // Giả lập drag-drop: đổi thứ tự
        $orderedIds = [3, 1, 2]; // ID theo thứ tự mới
        
        echo "\n🔄 Cập nhật thứ tự...\n";
        $success = $this->courseRepository->updateCourseOrdering($orderedIds, $academicYearId);
        
        if ($success) {
            echo "✅ Thứ tự đã được cập nhật!\n";
            
            // Kiểm tra thứ tự mới
            $newCourses = $this->courseRepository->getSortableCourses($academicYearId);
            echo "\n📋 Thứ tự mới:\n";
            foreach ($newCourses as $course) {
                echo "- {$course->ordering}. {$course->course}\n";
            }
        } else {
            echo "❌ Không thể cập nhật thứ tự!\n";
        }
        echo "\n";
    }

    /**
     * 🎯 Demo 4: Di Chuyển Course
     */
    public function demoMoveCourse()
    {
        echo "=== Demo 4: Di Chuyển Course ===\n";

        $courseId = 1;
        $academicYearId = 1;

        // Di chuyển lên trên
        echo "⬆️ Di chuyển course lên trên...\n";
        $success = $this->courseRepository->moveCourseUp($courseId, $academicYearId);
        
        if ($success) {
            echo "✅ Đã di chuyển lên trên!\n";
        } else {
            echo "❌ Không thể di chuyển!\n";
        }

        // Di chuyển xuống dưới
        echo "\n⬇️ Di chuyển course xuống dưới...\n";
        $success = $this->courseRepository->moveCourseDown($courseId, $academicYearId);
        
        if ($success) {
            echo "✅ Đã di chuyển xuống dưới!\n";
        } else {
            echo "❌ Không thể di chuyển!\n";
        }

        // Di chuyển đến vị trí cụ thể
        echo "\n🎯 Di chuyển đến vị trí 2...\n";
        $success = $this->courseRepository->moveCourseToPosition($courseId, 2, $academicYearId);
        
        if ($success) {
            echo "✅ Đã di chuyển đến vị trí 2!\n";
        } else {
            echo "❌ Không thể di chuyển!\n";
        }
        echo "\n";
    }

    /**
     * 🎯 Demo 5: Validation và Maintenance
     */
    public function demoValidation()
    {
        echo "=== Demo 5: Validation và Maintenance ===\n";

        $academicYearId = 1;

        // Kiểm tra course tồn tại
        $exists = $this->courseRepository->existsInAcademicYear(
            course: 'Lớp Giáo Lý A',
            academicYearId: $academicYearId
        );

        echo "🔍 Kiểm tra course tồn tại:\n";
        echo "- 'Lớp Giáo Lý A' trong năm học {$academicYearId}: " . ($exists ? 'Có' : 'Không') . "\n";

        // Validate ordering
        $issues = $this->courseRepository->validateAndFixOrdering($academicYearId);
        
        echo "\n🔧 Kiểm tra ordering:\n";
        if (empty($issues)) {
            echo "✅ Ordering không có vấn đề!\n";
        } else {
            echo "⚠️ Phát hiện " . count($issues) . " vấn đề:\n";
            foreach ($issues as $issue) {
                echo "- {$issue}\n";
            }
        }

        // Lấy ordering tiếp theo
        $nextOrdering = $this->courseRepository->getNextOrdering($academicYearId);
        echo "\n📊 Ordering tiếp theo: {$nextOrdering}\n";
        echo "\n";
    }

    /**
     * 🎯 Demo 6: Xóa Course và Auto Reorder
     */
    public function demoDeleteAndReorder()
    {
        echo "=== Demo 6: Xóa Course và Auto Reorder ===\n";

        $academicYearId = 1;

        // Lấy danh sách trước khi xóa
        $coursesBefore = $this->courseRepository->getSortableCourses($academicYearId);
        echo "📋 Trước khi xóa:\n";
        foreach ($coursesBefore as $course) {
            echo "- {$course->ordering}. {$course->course}\n";
        }

        // Xóa course ở giữa
        $courseToDelete = $coursesBefore->where('ordering', 2)->first();
        if ($courseToDelete) {
            echo "\n🗑️ Xóa course: {$courseToDelete->course}\n";
            $this->courseRepository->delete($courseToDelete->id);
            
            // Kiểm tra thứ tự sau khi xóa
            $coursesAfter = $this->courseRepository->getSortableCourses($academicYearId);
            echo "\n📋 Sau khi xóa (đã auto reorder):\n";
            foreach ($coursesAfter as $course) {
                echo "- {$course->ordering}. {$course->course}\n";
            }
        } else {
            echo "❌ Không tìm thấy course để xóa!\n";
        }
        echo "\n";
    }

    /**
     * 🎯 Demo 7: Cập Nhật Course với Reorder
     */
    public function demoUpdateWithReorder()
    {
        echo "=== Demo 7: Cập Nhật Course với Reorder ===\n";

        $courseId = 1;
        $oldAcademicYearId = 1;
        $newAcademicYearId = 2;

        // Lấy course hiện tại
        $course = $this->courseRepository->find($courseId);
        if (!$course) {
            echo "❌ Không tìm thấy course!\n";
            return;
        }

        echo "📋 Course hiện tại:\n";
        echo "- Tên: {$course->course}\n";
        echo "- Năm học: {$course->academic_year_id}\n";
        echo "- Ordering: {$course->ordering}\n";

        // Cập nhật course sang năm học khác
        $data = [
            'academic_year_id' => $newAcademicYearId,
            'program_id' => $course->program_id,
            'course' => $course->course
        ];

        echo "\n🔄 Cập nhật sang năm học {$newAcademicYearId}...\n";
        $success = $this->courseRepository->update($courseId, $data);

        if ($success) {
            echo "✅ Course đã được cập nhật!\n";
            
            // Kiểm tra ordering trong năm học cũ
            $oldYearCourses = $this->courseRepository->getSortableCourses($oldAcademicYearId);
            echo "\n📋 Năm học cũ (đã reorder):\n";
            foreach ($oldYearCourses as $c) {
                echo "- {$c->ordering}. {$c->course}\n";
            }

            // Kiểm tra ordering trong năm học mới
            $newYearCourses = $this->courseRepository->getSortableCourses($newAcademicYearId);
            echo "\n📋 Năm học mới:\n";
            foreach ($newYearCourses as $c) {
                echo "- {$c->ordering}. {$c->course}\n";
            }
        } else {
            echo "❌ Không thể cập nhật course!\n";
        }
        echo "\n";
    }

    /**
     * 🎯 Chạy Tất Cả Demo
     */
    public function runAllDemos()
    {
        echo "🚀 Bắt đầu Demo CourseRepository\n";
        echo "================================\n\n";

        $this->demoCreateCourse();
        $this->demoSearchAndFilter();
        $this->demoSortableOrdering();
        $this->demoMoveCourse();
        $this->demoValidation();
        $this->demoDeleteAndReorder();
        $this->demoUpdateWithReorder();

        echo "🎉 Hoàn thành tất cả demo!\n";
        echo "================================\n";
    }
}

/**
 * 🎯 Cách Sử Dụng Demo
 * 
 * 1. Trong Controller hoặc Command:
 * ```php
 * $demo = new CourseRepositoryDemo($courseRepository);
 * $demo->runAllDemos();
 * ```
 * 
 * 2. Chạy demo riêng lẻ:
 * ```php
 * $demo = new CourseRepositoryDemo($courseRepository);
 * $demo->demoCreateCourse();
 * $demo->demoSortableOrdering();
 * ```
 * 
 * 3. Trong Test:
 * ```php
 * public function testCourseRepository()
 * {
 *     $demo = new CourseRepositoryDemo($this->courseRepository);
 *     $demo->runAllDemos();
 * }
 * ```
 */
