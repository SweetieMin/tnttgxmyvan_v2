<div>

    <x-app-action-modal name="action-scouter" :dismissible="false"
        title="{{ $isEditScouterMode ? 'Cập nhật Huynh-Dự-Đội Trưởng' : 'Tạo mới Huynh-Dự-Đội Trưởng' }}"
        subheading="Quản lý thông tin Huynh-Dự-Đội Trưởng" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditScouterMode ? 'updateScouter' : 'createScouter' }}' class="space-y-6">
            

            
            
            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditScouterMode ? 'Cập nhật' : 'Thêm mới' }} 
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-scouter" :width="600" title="Xác nhận xóa Huynh-Dự-Đội Trưởng"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Huynh-Dự-Đội Trưởng." warning-title="Cảnh báo xóa dữ liệu"
        message="Bạn có chắc chắn muốn xóa Huynh-Dự-Đội Trưởng này không?" :warnings="['Tất cả thông tin Huynh-Dự-Đội Trưởng sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteScouterConfirm" />

</div>
