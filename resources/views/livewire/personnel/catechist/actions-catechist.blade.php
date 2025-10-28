<div>

    <x-app-action-modal name="action-catechist" :dismissible="false"
        title="{{ $isEditCatechistMode ? 'Cập nhật Giáo Lý Viên' : 'Tạo mới Giáo Lý Viên' }}"
        subheading="Quản lý thông tin Giáo Lý Viên" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditCatechistMode ? 'updateCatechist' : 'createCatechist' }}' class="space-y-6">
            

            
            
            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditCatechistMode ? 'Cập nhật' : 'Thêm mới' }} 
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-catechist" :width="600" title="Xác nhận xóa Giáo Lý Viên"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Giáo Lý Viên." warning-title="Cảnh báo xóa dữ liệu"
        message="Bạn có chắc chắn muốn xóa Giáo Lý Viên này không?" :warnings="['Tất cả thông tin Giáo Lý Viên sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteCatechistConfirm" />

</div>
