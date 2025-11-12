<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\Interfaces\ProgramRepositoryInterface;

class CourseSectorNameService
{
    protected ProgramRepositoryInterface $programRepository;

    public function __construct(ProgramRepositoryInterface $programRepository)
    {
        $this->programRepository = $programRepository;
    }

    public function generateCourseName(?int $programId = null): ?string
    {
        if (empty($programId)) {
            return null;
        }

        $program = $this->programRepository->find($programId);

        return $program?->course;
    }

    /**
     * Sinh tên lớp không trùng trong cùng niên khoá & chương trình.
     */
    public function generateUniqueCourseName(string $baseName, ?int $academicYearId, ?int $programId, ?int $excludeCourseId = null): string
    {
        if (empty($academicYearId) || empty($programId)) {
            return $baseName;
        }

        $query = Course::where('academic_year_id', $academicYearId)
            ->where('program_id', $programId);

        if ($excludeCourseId) {
            $query->where('id', '!=', $excludeCourseId);
        }

        $existingNames = $query->pluck('course')->toArray();

        if (! in_array($baseName, $existingNames, true)) {
            return $baseName;
        }

        $suffixes = $this->buildSuffixCandidates();

        foreach ($suffixes as $suffix) {
            $candidate = $baseName . $suffix;
            if (! in_array($candidate, $existingNames, true)) {
                return $candidate;
            }
        }

        // Nếu vẫn trùng, fallback bằng cách thêm timestamp tránh lỗi.
        return $baseName . ' ' . now()->format('His');
    }

    /**
     * Danh sách hậu tố: chỉ từ A đến F.
     */
    protected function buildSuffixCandidates(): array
    {
        return ['A', 'B', 'C', 'D', 'E', 'F'];
    }
}