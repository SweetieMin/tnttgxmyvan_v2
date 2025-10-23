<div>

    <x-app-action-modal name="action-role" :dismissible="false"
        title="{{ $isEditRoleMode ? 'Cập nhật Chức vụ' : 'Tạo mới Chức vụ' }}"
        subheading="Quản lý thông tin Chức vụ" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditRoleMode ? 'updateRole' : 'createRole' }}' class="space-y-6">
            

            
            
            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditRoleMode ? 'Cập nhật' : 'Thêm mới' }} 
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-role" :width="600" title="Xác nhận xóa Chức vụ"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Chức vụ." warning-title="Cảnh báo xóa dữ liệu"
        message="Bạn có chắc chắn muốn xóa Chức vụ này không?" :warnings="['Tất cả thông tin Chức vụ sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteRoleConfirm" />

</div>
