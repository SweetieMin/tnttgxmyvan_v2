<div>

    <x-app-action-modal name="action-{{ moduleKebab }}" :dismissible="false"
        title="{{ $isEdit{{ module }}Mode ? 'Cập nhật {{ vietnameseName }}' : 'Tạo mới {{ vietnameseName }}' }}"
        subheading="Quản lý thông tin {{ vietnameseName }}" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit='{{ $isEdit{{ module }}Mode ? 'update{{ module }}' : 'create{{ module }}' }}' class="space-y-6">
            

            
            
            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEdit{{ module }}Mode ? 'Cập nhật' : 'Thêm mới' }} 
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-{{ moduleKebab }}" :width="600" title="Xác nhận xóa {{ vietnameseName }}"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến {{ vietnameseName }}." warning-title="Cảnh báo xóa dữ liệu"
        message="Bạn có chắc chắn muốn xóa {{ vietnameseName }} này không?" :warnings="['Tất cả thông tin {{ vietnameseName }} sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="delete{{ module }}Confirm" />

</div>
