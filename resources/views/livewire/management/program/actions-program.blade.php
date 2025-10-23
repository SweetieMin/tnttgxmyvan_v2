<div>

    <x-app-action-modal name="action-program" :dismissible="false"
        title="{{ $isEditProgramMode ? 'Cập nhật Chương trình học' : 'Tạo mới Chương trình học' }}"
        subheading="Quản lý thông tin Chương trình học" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit='{{ $isEditProgramMode ? 'updateProgram' : 'createProgram' }}' class="space-y-6">


            <flux:input type="text" label="Lớp Giáo Lý" wire:model='course' autocomplete="off"/>

            <flux:input type="text" label="Ngành sinh hoạt" wire:model='sector' autocomplete="off"/>


            <flux:textarea label="Mô tả" placeholder="Phù hợp cho lứa tuổi" wire:model='description' />

            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditProgramMode ? 'Cập nhật' : 'Thêm mới' }}
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-program" :width="600" title="Xác nhận xóa Chương trình học"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Chương trình học."
        warning-title="Cảnh báo xóa dữ liệu" message="Bạn có chắc chắn muốn xóa Chương trình học này không?"
        :warnings="['Tất cả thông tin Chương trình học sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteProgramConfirm" />

</div>
