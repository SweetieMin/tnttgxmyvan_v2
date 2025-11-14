<div>

    <x-app-action-modal name="action-sector" :dismissible="false"
        title="{{ $isEditSectorMode ? 'Cập nhật Ngành Sinh Hoạt' : 'Tạo mới Ngành Sinh Hoạt' }}"
        subheading="Quản lý thông tin Ngành Sinh Hoạt" icon="squares-plus" class="md:max-w-[550px]">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditSectorMode ? 'updateSector' : 'createSector' }}' class="space-y-6">

            <label for="perPage" class="block text-sm font-medium mb-1 "> Niên khoá
            </label>
            <flux:select wire:model.lazy="academic_year_id" placeholder="Chọn niên khoá" variant="listbox">

                @foreach ($years as $year)
                    <flux:select.option value="{{ $year->id }}">{{ $year->name }}</flux:select.option>
                @endforeach
            </flux:select>

            @error('academic_year_id')
                <x-app-error-message :message="$message" />
            @enderror

            <label for="perPage" class="block text-sm font-medium mb-1 "> Chương trình học
            </label>
            <flux:select wire:model.lazy="program_id" placeholder="Chọn chương trình" variant="listbox" searchable>

                @foreach ($programs as $program)
                    <flux:select.option value="{{ $program->id }}">{{ $program->sector }}</flux:select.option>
                @endforeach
            </flux:select>

            @error('program_id')
                <x-app-error-message :message="$message" />
            @enderror

            <flux:input type="text" label="Tên ngành sinh hoạt" name="sector"
                wire:model.lazy='sector' autocomplete="off" />

            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditSectorMode ? 'Cập nhật' : 'Thêm mới' }}
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-sector" :width="600" title="Xác nhận xóa Ngành Sinh Hoạt"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Ngành Sinh Hoạt." warning-title="Cảnh báo xóa dữ liệu"
        message="Bạn có chắc chắn muốn xóa Ngành Sinh Hoạt này không?" :warnings="['Tất cả thông tin Ngành Sinh Hoạt sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteSectorConfirm" />

</div>
