<div>

    <x-app-action-modal name="action-academic-year" :dismissible="false"
        title="{{ $isEditAcademicYearMode ? 'Cập nhật niên khoá' : 'Tạo mới niên khoá' }}"
        subheading="Quản lý niên khoá, thời gian và điểm số" icon="squares-plus" width="700px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditAcademicYearMode ? 'updateAcademicYear' : 'createAcademicYear' }}' class="space-y-6">
            <div class="flex gap-2">
                <div class=" w-2/3">
                    <flux:input disabled variant="filled" label="Tên niên khoá" wire:model='name' />
                </div>
                <div class=" w-1/3"> <label for="perPage" class="block text-sm font-medium mb-1 opacity-70 "> Trạng
                        thái </label>
                    <flux:select variant="listbox" wire:model="status_academic" placeholder="Chọn trạng thái">
                        <flux:select.option value="upcoming">Sắp diễn ra</flux:select.option>
                        <flux:select.option value="ongoing">Đang diễn ra</flux:select.option>
                        <flux:select.option value="finished">Đã hoàn thành</flux:select.option>
                    </flux:select>
                </div>
            </div>
            <flux:separator text="Giáo Lý" />
            <div class="flex gap-2">
                <div class=" w-1/2">
                    <flux:date-picker type="date" max="2999-12-31" label="Ngày bắt đầu"
                        wire:model.lazy='catechism_start_date' placeholder="Chọn ngày" locale="vi-VN" selectable-header clearable/>
                </div>
                <div class=" w-1/2">
                    <flux:date-picker type="date" max="2999-12-31" label="Ngày kết thúc"
                        wire:model.lazy='catechism_end_date' placeholder="Chọn ngày" locale="vi-VN" selectable-header clearable />
                </div>
            </div>
            <flux:separator text="Sinh hoạt" />
            <div class="flex gap-2">
                <div class=" w-1/2">
                    <flux:date-picker type="date" max="2999-12-31" label="Ngày bắt đầu"
                        wire:model.lazy='activity_start_date' placeholder="Chọn ngày" locale="vi-VN" selectable-header clearable/>
                </div>
                <div class=" w-1/2">
                    <flux:date-picker type="date" max="2999-12-31" label="Ngày kết thúc"
                        wire:model.lazy='activity_end_date' placeholder="Chọn ngày" locale="vi-VN" selectable-header clearable/>
                </div>
            </div>
            <flux:separator text="Quy định điểm" />
            <div class="flex gap-2">
                <div class=" w-1/3">
                    <flux:input type="number" min="0" max="10" label="Điểm Giáo Lý"
                        placeholder="Điểm chuẩn" wire:model.lazy='catechism_avg_score' />
                </div>
                <div class=" w-1/3">
                    <flux:input type="number" min="0" max="10" label="Điểm Chuyên Cần"
                        placeholder="Điểm chuẩn" wire:model.lazy='catechism_training_score' />
                </div>
                <div class=" w-1/3">
                    <flux:input type="number" min="0" max="1000" label="Điểm Sinh Hoạt"
                        placeholder="Điểm chuẩn" wire:model.lazy='activity_score' />
                </div>
            </div>
            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditAcademicYearMode ? 'Cập nhật' : 'Thêm mới' }} </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-academic-year" :width="600" title="Xác nhận xóa niên khoá"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến niên khoá." warning-title="Cảnh báo xóa dữ liệu"
        message="Bạn có chắc chắn muốn xóa niên khoá này không?" :warnings="['Tất cả thông tin niên khoá sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteAcademicYearConfirm" />

</div>
