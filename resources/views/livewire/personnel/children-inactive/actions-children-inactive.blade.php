<div>

    <x-app-action-modal name="action-children-inactive" :dismissible="false"
        title="{{ $isEditChildrenInactiveMode ? 'Cập nhật Thiếu Nhi đã tốt nghiệp' : 'Tạo mới Thiếu Nhi đã tốt nghiệp' }}"
        subheading="Quản lý thông tin Thiếu Nhi đã tốt nghiệp" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditChildrenInactiveMode ? 'updateChildrenInactive' : 'createChildrenInactive' }}' class="space-y-6">
            

            
            
            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditChildrenInactiveMode ? 'Cập nhật' : 'Thêm mới' }} 
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-children-inactive" :width="600" title="Xác nhận xóa Thiếu Nhi đã tốt nghiệp"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Thiếu Nhi đã tốt nghiệp." warning-title="Cảnh báo xóa dữ liệu"
        message="Bạn có chắc chắn muốn xóa Thiếu Nhi đã tốt nghiệp này không?" :warnings="['Tất cả thông tin Thiếu Nhi đã tốt nghiệp sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteChildrenInactiveConfirm" />

</div>
