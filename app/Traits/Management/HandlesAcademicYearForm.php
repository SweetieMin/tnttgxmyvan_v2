<?php

namespace App\Traits\Management;

use Carbon\Carbon;

trait HandlesAcademicYearForm
{
    protected function resetForm()
    {
        $this->reset([
            'name',
            'catechism_start_date',
            'catechism_end_date',
            'catechism_avg_score',
            'catechism_training_score',
            'activity_start_date',
            'activity_end_date',
            'activity_score',
            'status_academic',
        ]);

        $this->isEditAcademicYearMode=false;

        $this->resetErrorBag();
    }

    // =========================
    // 🔹 Cập nhật ngày giáo lý
    // =========================

    public function updatedCatechismStartDate($value)
    {
        $this->validateCatechismDates();
        $this->resetErrorBag('catechism_start_date');

        $this->activity_start_date = $value;
        if($value)
        {
            $this->catechism_end_date = $this->activity_end_date = Carbon::parse($value)
            ->addDays(250)
            ->format('Y-m-d');
        }

        $this->generateAcademicYearName();
        $this->checkOngoingStatus();
    }

    public function updatedCatechismEndDate($value)
    {
        $this->validateCatechismDates();
        $this->resetErrorBag('catechism_end_date');
        $this->generateAcademicYearName();
        $this->checkOngoingStatus();
        $this->activity_end_date = $value;
    }

    protected function validateCatechismDates()
    {
        // Nếu cả hai ngày đều có giá trị
        if ($this->catechism_start_date && $this->catechism_end_date) {
            if ($this->catechism_end_date < $this->catechism_start_date) {
                $this->addError('catechism_end_date', 'Ngày kết thúc giáo lý phải sau hoặc bằng ngày bắt đầu.');
                return;
            }
        }
    }

    protected function checkOngoingStatus()
    {
        if ($this->catechism_start_date && $this->catechism_end_date) {
            $now = Carbon::now();
            $start = Carbon::parse($this->catechism_start_date);
            $end = Carbon::parse($this->catechism_end_date);

            if ($now->between($start, $end)) {
                $this->status_academic = 'ongoing';
            } elseif ($now->lt($start)) {
                $this->status_academic = 'upcoming';
            } elseif ($now->gt($end)) {
                $this->status_academic = 'finished';
            }
        }
    }

    // =========================
    // 🔹 Cập nhật ngày sinh hoạt
    // =========================

    public function updatedActivityStartDate($value)
    {
        $this->validateActivityDates();
        $this->resetErrorBag('activity_start_date');
    }

    public function updatedActivityEndDate($value)
    {
        $this->validateActivityDates();
        $this->resetErrorBag('activity_end_date');
    }

    protected function validateActivityDates()
    {
        if ($this->activity_start_date && $this->activity_end_date) {
            if ($this->activity_end_date < $this->activity_start_date) {
                $this->addError('activity_end_date', 'Ngày kết thúc sinh hoạt phải sau hoặc bằng ngày bắt đầu.');
                return;
            }
        }
    }

    // =========================
    // 🔹 Tự động đặt tên niên khóa
    // =========================

    protected function generateAcademicYearName()
    {
        if ($this->catechism_start_date && $this->catechism_end_date) {
            try {
                $startYear = Carbon::parse($this->catechism_start_date)->year;
                $endYear   = Carbon::parse($this->catechism_end_date)->year;

                $this->name = "{$startYear} - {$endYear}";
            } catch (\Exception $e) {
                // Bỏ qua nếu không parse được
            }
        }
    }

    // =========================
    // 🔹 Cập nhật điểm
    // =========================

    public function updatedCatechismAvgScore($value)
    {
        $this->checkErrorInput($value, 'catechism_avg_score', 0, 10, 'Điểm giáo lý');
    }

    public function updatedCatechismTrainingScore($value)
    {
        $this->checkErrorInput($value, 'catechism_training_score', 0, 10, 'Điểm chuyên cần');
    }

    public function updatedActivityScore($value)
    {
        $this->checkErrorInput($value, 'activity_score', 0, 1000, 'Điểm sinh hoạt');
    }

    /**
     * Kiểm tra giá trị nhập có hợp lệ trong khoảng [min, max] không
     */
    protected function checkErrorInput($value, string $field, int|float $min, int|float $max, string $label)
    {

        $this->resetErrorBag($field);

        // Nếu rỗng thì bỏ qua (để validate chính xử lý)
        if ($value === null || $value === '') {
            $this->resetErrorBag($field);
            return;
        }

        // Nếu không phải số
        if (!is_numeric($value)) {
            $this->addError($field, "{$label} phải là số.");
            return;
        }

        // Kiểm tra phạm vi
        if ($value < $min || $value > $max) {
            $this->addError($field, "{$label} phải nằm trong khoảng {$min} đến {$max}.");
        } else {
            // Nếu có lỗi trước đó và giờ đã hợp lệ thì reset lỗi
            if ($this->getErrorBag()->has($field)) {
                $this->resetErrorBag($field);
            }
        }
    }
}
