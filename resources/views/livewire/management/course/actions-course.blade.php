<div>

    <x-app-action-modal name="action-course" :dismissible="false"
        title="{{ $isEditCourseMode ? 'Cập nhật Lớp Giáo Lý' : 'Tạo mới Lớp Giáo Lý' }}"
        subheading="Quản lý thông tin Lớp Giáo Lý" icon="squares-plus" class="md:max-w-[550px]">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditCourseMode ? 'updateCourse' : 'createCourse' }}' class="space-y-6">


            <label for="perPage" class="block text-sm font-medium mb-1 "> Niên khoá
            </label>
            <flux:select wire:model="academic_year_id" placeholder="Chọn niên khoá" variant="listbox" >
                @foreach ($years as $year)
                    <flux:select.option value="{{ $year->id }}">{{ $year->name }}</flux:select.option>
                @endforeach

            </flux:select>

            @error('academic_year_id')
                <x-app-error-message :message="$message" />
            @enderror

            <label for="perPage" class="block text-sm font-medium mb-1 "> Chương trình học
            </label>
            <flux:select wire:model.lazy="program_id" placeholder="Chọn lớp" variant="listbox" searchable>
                @foreach ($programs as $program)
                    <flux:select.option value="{{ $program->id }}">{{ $program->course }}</flux:select.option>
                @endforeach

            </flux:select>

            @error('program_id')
                <x-app-error-message :message="$message" />
            @enderror

            <flux:input type="text" label="Tên lớp giáo lý" name="course"
                wire:model.lazy='course' autocomplete="off" />

            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditCourseMode ? 'Cập nhật' : 'Thêm mới' }}
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-course" :width="600" title="Xác nhận xóa Lớp Giáo Lý"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Lớp Giáo Lý."
        warning-title="Cảnh báo xóa dữ liệu" message="Bạn có chắc chắn muốn xóa Lớp Giáo Lý này không?"
        :warnings="['Tất cả thông tin Lớp Giáo Lý sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteCourseConfirm" />

</div>
