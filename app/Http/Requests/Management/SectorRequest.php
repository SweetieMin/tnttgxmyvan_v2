<?php

namespace App\Http\Requests\Management;

use App\Models\Sector;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SectorRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi request này không
     */
    public function authorize(): bool
    {
        return true; // hoặc thêm kiểm tra quyền ở đây
    }

    /**
     * Quy tắc validate dữ liệu
     */
    public function rules(): array
    {
        $sectorParam = $this->route('sector');
        $sectorId = $sectorParam instanceof Sector ? $sectorParam->id : $sectorParam;

        return [
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],

            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('sectors', 'name')
                    ->where(fn($q) => $q->where('academic_year_id', $this->input('academic_year_id')))
                    ->ignore($sectorId), // bỏ qua khi update
            ],

            'description' => ['nullable', 'string', 'min:10', 'max:255'],
        ];
    }

    /**
     * Thông báo lỗi tuỳ chỉnh
     */
    public function messages(): array
    {
        return [
            'academic_year_id.required' => 'Niên khóa là bắt buộc.',
            'academic_year_id.integer' => 'Niên khóa phải là số nguyên.',
            'academic_year_id.exists' => 'Niên khóa không tồn tại.',
            'name.required' => 'Tên ngành sinh hoạt là bắt buộc.',
            'name.string' => 'Tên ngành sinh hoạt phải là chuỗi.',
            'name.min' => 'Tên ngành sinh hoạt phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên ngành sinh hoạt phải có ít hơn 255 ký tự.',
            'name.unique' => 'Tên ngành sinh hoạt đã tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi.',
            'description.min' => 'Mô tả phải có ít nhất 10 ký tự.',
            'description.max' => 'Mô tả phải có ít hơn 255 ký tự.',
        ];
    }
}
