<div>

    <x-app-action-modal name="action-spiritual" :dismissible="false"
        title="{{ $isEditSpiritualMode ? 'Cập nhật Linh hướng' : 'Tạo mới Linh hướng' }}"
        subheading="Quản lý thông tin Linh hướng" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditSpiritualMode ? 'updateSpiritual' : 'createSpiritual' }}' class="space-y-6">
            

            
            
            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditSpiritualMode ? 'Cập nhật' : 'Thêm mới' }} 
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-spiritual" :width="600" title="Xác nhận xóa Linh hướng"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Linh hướng." warning-title="Cảnh báo xóa dữ liệu"
        message="Bạn có chắc chắn muốn xóa Linh hướng này không?" :warnings="['Tất cả thông tin Linh hướng sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteSpiritualConfirm" />

</div>
