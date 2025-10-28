<div>

    <x-app-action-modal name="action-children" :dismissible="false"
        title="{{ $isEditChildrenMode ? 'Cập nhật Thiếu Nhi' : 'Tạo mới Thiếu Nhi' }}"
        subheading="Quản lý thông tin Thiếu Nhi" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditChildrenMode ? 'updateChildren' : 'createChildren' }}' class="space-y-6">
            

            
            
            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditChildrenMode ? 'Cập nhật' : 'Thêm mới' }} 
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-children" :width="600" title="Xác nhận xóa Thiếu Nhi"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Thiếu Nhi." warning-title="Cảnh báo xóa dữ liệu"
        message="Bạn có chắc chắn muốn xóa Thiếu Nhi này không?" :warnings="['Tất cả thông tin Thiếu Nhi sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteChildrenConfirm" />

</div>
