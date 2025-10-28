<div>

    <x-app-action-modal name="action-transaction" :dismissible="false"
        title="{{ $isEditTransactionMode ? 'Cập nhật Tiền Quỹ' : 'Tạo mới Tiền Quỹ' }}"
        subheading="Quản lý thông tin Tiền Quỹ" icon="squares-plus" width="600px">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditTransactionMode ? 'updateTransaction' : 'createTransaction' }}'
            class="space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="transaction_item_id" class="block text-sm font-medium mb-1">Hạng mục</label>
                    <flux:select wire:model.lazy="transaction_item_id" placeholder="Chọn hạng mục">
                        @foreach ($items as $item)
                            <flux:select.option value="{{ $item->id }}">{{ $item->name }}</flux:select.option>
                        @endforeach

                    </flux:select>
                    @error('transaction_item_id')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>
                <div>
                    <flux:input type="date" max="2999-12-31" label="Ngày" wire:model.lazy='transaction_date' />
                </div>
            </div>

            <flux:textarea label="Mô tả chi tiết" placeholder="Chi tiền Tết 2026" wire:model='description' />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Cộng / Trừ --}}
                <div>
                    <label for="type" class="block text-sm font-medium mb-1">Thu / Chi</label>
                    <flux:select wire:model.lazy="type" placeholder="Chọn loại">
                        <flux:select.option value="expense">Chi</flux:select.option>
                        <flux:select.option value="income">Thu</flux:select.option>
                    </flux:select>
                    @error('type')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>

                {{-- Điểm --}}
                <div>
                    <label for="amount" class="block text-sm font-medium mb-1">Tổng số tiền</label>
                    <flux:input mask:dynamic="$money($input)" wire:model="amount" placeholder="Nhập số tiền" autocomplete="off"/>
                    @error('amount')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>
            </div>

            {{-- <flux:input type="file" wire:model="attachments" label="File PDF" multiple /> --}}

            <livewire:dropzone wire:model="file" :rules="['mimes:pdf']" :multiple="false" :key="'dropzone-two'" />

            @if ($existingFile)
                <div
                    class="mt-3 flex items-center justify-between rounded-lg border border-accent/20 bg-accent-card/10 p-3">
                    <div class="flex items-center gap-3">

                        <a href="{{ $existingFile['url'] }}" target="_blank" class="text-accent hover:underline">
                            {{ $existingFile['name'] }}
                        </a>
                    </div>
                    <span class="text-xs text-accent-text/60">File đã lưu</span>
                </div>
            @endif

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
