<?php

namespace App\Livewire\Personnel\Common;

use Flux\Flux;
use Livewire\Component;
use App\Repositories\Interfaces\SpiritualRepositoryInterface;

class UpdateCatechism extends Component
{

    public $spiritualID;

    public $baptism_date, $baptismal_sponsor, $baptism_place, $first_communion_date, $confirmation_bishop, $first_communion_sponsor, $first_communion_place, $confirmation_date, $confirmation_sponsor, $confirmation_place, $pledge_date, $pledge_sponsor, $pledge_place;
    public $status_religious = 'in_course'; 
    public bool $is_attendance = true;

    protected SpiritualRepositoryInterface $spiritualRepository;

    public function render()
    {
        return view('livewire.personnel.common.update-catechism');
    }

    public function mount($spiritualID)
    {
        $this->spiritualID = $spiritualID;

        $spiritual = $this->spiritualRepository->findSpiritualWithRelations($this->spiritualID);

        if (!$spiritual) {
            return;
        }

        $this->baptism_date = $spiritual->religious_profile?->baptism_date;
        $this->baptismal_sponsor = $spiritual->religious_profile?->baptismal_sponsor;
        $this->baptism_place = $spiritual->religious_profile?->baptism_place;
        $this->first_communion_date = $spiritual->religious_profile?->first_communion_date;
        $this->first_communion_sponsor = $spiritual->religious_profile?->first_communion_sponsor;
        $this->first_communion_place = $spiritual->religious_profile?->first_communion_place;
        $this->confirmation_date = $spiritual->religious_profile?->confirmation_date;
        $this->confirmation_sponsor = $spiritual->religious_profile?->confirmation_sponsor;
        $this->confirmation_place = $spiritual->religious_profile?->confirmation_place;
        $this->pledge_date = $spiritual->religious_profile?->pledge_date;
        $this->pledge_sponsor = $spiritual->religious_profile?->pledge_sponsor;
        $this->pledge_place = $spiritual->religious_profile?->pledge_place;
        $this->status_religious = $spiritual->religious_profile?->status_religious;
        $this->is_attendance = (bool) $spiritual->religious_profile?->is_attendance;

    }



    public function boot(SpiritualRepositoryInterface $spiritualRepository)
    {
        $this->spiritualRepository = $spiritualRepository;
    }

    public function rules()
    {
        return [
            'baptism_date' => 'nullable|date',
            'baptismal_sponsor' => 'nullable|string|max:255',
            'baptism_place' => 'nullable|string|max:255',
            'first_communion_date' => 'nullable|date',
            'first_communion_sponsor' => 'nullable|string|max:255',
            'first_communion_place' => 'nullable|string|max:255',
            'confirmation_date' => 'nullable|date',
            'confirmation_bishop' => 'nullable|string|max:255',
            'confirmation_place' => 'nullable|string|max:255',
            'pledge_date' => 'nullable|date',
            'pledge_sponsor' => 'nullable|string|max:255',
            'pledge_place' => 'nullable|string|max:255',
            'status_religious' => 'required|in:in_course,graduated',
            'is_attendance' => 'required',
        ];
    }

    public function messages()
    {
        return [
            // 🔹 BAPTISM
            'baptism_date.date' => 'Ngày rửa tội phải là ngày hợp lệ.',
            'baptismal_sponsor.string' => 'Tên người đỡ đầu rửa tội phải là chuỗi ký tự.',
            'baptismal_sponsor.max' => 'Tên người đỡ đầu rửa tội tối đa :max ký tự.',
            'baptism_place.string' => 'Nơi rửa tội phải là chuỗi ký tự.',
            'baptism_place.max' => 'Nơi rửa tội tối đa :max ký tự.',

            // 🔹 FIRST COMMUNION
            'first_communion_date.date' => 'Ngày rước lễ lần đầu phải là ngày hợp lệ.',
            'first_communion_sponsor.string' => 'Tên người đỡ đầu rước lễ phải là chuỗi ký tự.',
            'first_communion_sponsor.max' => 'Tên người đỡ đầu rước lễ tối đa :max ký tự.',
            'first_communion_place.string' => 'Nơi rước lễ phải là chuỗi ký tự.',
            'first_communion_place.max' => 'Nơi rước lễ tối đa :max ký tự.',

            // 🔹 CONFIRMATION
            'confirmation_date.date' => 'Ngày Thêm Sức phải là ngày hợp lệ.',
            'confirmation_bishop.string' => 'Tên Giám mục phải là chuỗi ký tự.',
            'confirmation_bishop.max' => 'Tên Giám mục tối đa :max ký tự.',
            'confirmation_place.string' => 'Nơi lãnh nhận bí tích Thêm Sức phải là chuỗi ký tự.',
            'confirmation_place.max' => 'Nơi lãnh nhận bí tích Thêm Sức tối đa :max ký tự.',

            // 🔹 PLEDGE
            'pledge_date.date' => 'Ngày tuyên hứa phải là ngày hợp lệ.',
            'pledge_sponsor.string' => 'Tên người đỡ đầu tuyên hứa phải là chuỗi ký tự.',
            'pledge_sponsor.max' => 'Tên người đỡ đầu tuyên hứa tối đa :max ký tự.',
            'pledge_place.string' => 'Nơi tuyên hứa phải là chuỗi ký tự.',
            'pledge_place.max' => 'Nơi tuyên hứa tối đa :max ký tự.',

            // 🔹 STATUS RELIGIOUS
            'status_religious.required' => 'Vui lòng chọn trạng thái tôn giáo.',
            'status_religious.in'       => 'Giá trị trạng thái tôn giáo không hợp lệ.',

            // 🔹 IS ATTENDANCE
            'is_attendance.required' => 'Vui lòng chọn tùy chọn điểm danh.',
            'is_attendance.in'       => 'Giá trị điểm danh không hợp lệ.',
        ];
    }

    public function updateCatechism()
    {
        $this->validate();

        try {
            $spiritual = $this->spiritualRepository->findSpiritualWithRelations($this->spiritualID);

            $spiritual->religious_profile()->updateOrCreate(
                ['user_id' => $spiritual->id], // điều kiện để tìm
                [
                    'baptism_date' => $this->baptism_date,
                    'baptismal_sponsor' => $this->baptismal_sponsor,
                    'baptism_place' => $this->baptism_place,
                    'first_communion_date' => $this->first_communion_date,
                    'first_communion_sponsor' => $this->first_communion_sponsor,
                    'first_communion_place' => $this->first_communion_place,
                    'confirmation_date' => $this->confirmation_date,
                    'confirmation_bishop' => $this->confirmation_bishop,
                    'confirmation_place' => $this->confirmation_place,
                    'pledge_date' => $this->pledge_date,
                    'pledge_sponsor' => $this->pledge_sponsor,
                    'pledge_place' => $this->pledge_place,
                    'status_religious' => $this->status_religious,
                    'is_attendance' => (int) $this->is_attendance,
                ]
            );

            Flux::toast(
                heading: 'Thành công',
                text: 'Cập nhật thông tin công giáo thành công.',
                variant: 'success',
            );
            
            $url = route('admin.personnel.spirituals.action', [
                'parameter'   => 'editSpiritual',
                'spiritualID' => $this->spiritualID,
                'tab'         => 'catechism',
            ]) . '#section';
            
            $this->redirect($url, navigate: true);

        } catch (\Exception $e) {
            Flux::toast(
                heading: 'Thất bại',
                text: 'Cập nhật thông tin công giáo thất bại. ' . $e->getMessage(),
                variant: 'danger',
            );
        }
    }
}
