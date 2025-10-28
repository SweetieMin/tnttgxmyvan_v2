<div>

    <x-app-action-modal name="action-transaction" :dismissible="false"
        title="{{ $isEditTransactionMode ? 'Cập nhật Tiền Quỹ' : 'Tạo mới Tiền Quỹ' }}"
        subheading="Quản lý thông tin Tiền Quỹ" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditTransactionMode ? 'updateTransaction' : 'createTransaction' }}'
            class="space-y-6">


            <div>
                <label for="category" class="block text-sm font-medium mb-1">Hạng mục</label>
                <flux:select wire:model.lazy="category" placeholder="Chọn hạng mục">
                    <flux:select.option value="plus">Cộng điểm</flux:select.option>
                    <flux:select.option value="minus">Trừ điểm</flux:select.option>
                </flux:select>
                @error('category')
                    <x-app-error-message :message="$message" />
                @enderror
            </div>

            <flux:textarea label="Mô tả chi tiết" placeholder="Chi tiền Tết 2026" wire:model='description' />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Cộng / Trừ --}}
                <div>
                    <label for="type" class="block text-sm font-medium mb-1">Thu / Chi</label>
                    <flux:select wire:model.lazy="type" placeholder="Chọn loại">
                        <flux:select.option value="income">Thu</flux:select.option>
                        <flux:select.option value="expense">Chi</flux:select.option>
                    </flux:select>
                    @error('type')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>

                {{-- Điểm --}}
                <div>
                    <label for="points" class="block text-sm font-medium mb-1">Tổng số tiền</label>
                    <flux:input mask:dynamic="$money($input)" wire:model="amount" placeholder="Nhập số tiền" />
                    @error('amount')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>
            </div>

            {{-- <flux:input type="file" wire:model="attachments" label="File PDF" multiple /> --}}

            <livewire:dropzone wire:model="files" :rules="['mimes:pdf']" :multiple="true" :key="'dropzone-two'" />



            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditTransactionMode ? 'Cập nhật' : 'Thêm mới' }}
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>


    <x-app-delete-modal name="delete-transaction" :width="600" title="Xác nhận xóa Tiền Quỹ"
        description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Tiền Quỹ." warning-title="Cảnh báo xóa dữ liệu"
        message="Bạn có chắc chắn muốn xóa Tiền Quỹ này không?" :warnings="['Tất cả thông tin Tiền Quỹ sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteTransactionConfirm" />

</div>
