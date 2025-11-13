<div>

    <x-app-action-modal name="action-transaction-item" :dismissible="false"
        title="{{ $isEditTransactionItemMode ? 'Cập nhật Hạng mục chi' : 'Tạo mới Hạng mục chi' }}"
        subheading="Quản lý thông tin Hạng mục chi" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditTransactionItemMode ? 'updateTransactionItem' : 'createTransactionItem' }}'
            class="space-y-6">


            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <flux:input type="text" label="Hạng mục" wire:model="name" placeholder="Nhập tên hạng mục" />

                {{-- Cộng / Trừ --}}
                <div>
                    <label for="is_system" class="block text-sm font-medium mb-1">Loại</label>
                    <flux:select wire:model.lazy="is_system" placeholder="Chọn loại" variant="listbox">
                        <flux:select.option value="0">Tùy chỉnh</flux:select.option>
                        <flux:select.option value="1">Hệ thống</flux:select.option>
                    </flux:select>
                    @error('is_system')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>


            </div>

            <flux:textarea label="Mô tả hạng mục" placeholder="Thi chi các hạng động...Tết/Giáng Sinh/Trại hè"
                wire:model='description' class="min-h-[120px]"/>

            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditTransactionItemMode ? 'Cập nhật' : 'Thêm mới' }}
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-transaction-item" :width="600" title="Xác nhận xóa Hạng mục chi"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Hạng mục chi."
        warning-title="Cảnh báo xóa dữ liệu" message="Bạn có chắc chắn muốn xóa Hạng mục chi này không?"
        :warnings="['Tất cả thông tin Hạng mục chi sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteTransactionItemConfirm" />

</div>
