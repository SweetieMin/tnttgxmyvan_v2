<div>

    <x-app-action-modal name="action-transaction" :dismissible="false"
        title="{{ $isEditTransactionMode ? 'Cập nhật Tiền Quỹ' : 'Tạo mới Tiền Quỹ' }}"
        subheading="Quản lý thông tin Tiền Quỹ" icon="squares-plus" class="md:max-w-[550px]">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditTransactionMode ? 'updateTransaction' : 'createTransaction' }}'
            class="space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <flux:select wire:model.lazy="transaction_item_id" placeholder="Chọn hạng mục" variant="listbox"
                        label="Hạng mục chi" searchable>
                        @foreach ($items as $item)
                            <flux:select.option value="{{ $item->id }}">{{ $item->name }}</flux:select.option>
                        @endforeach

                    </flux:select>
                    @error('transaction_item_id')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>
                <div>
                        <flux:date-picker type="date" :max="now()->toDateString()" label="Ngày"
                        wire:model.lazy='transaction_date' placeholder="Chọn ngày" locale="vi-VN" selectable-header clearable with-today/>
                </div>
            </div>

            <flux:textarea label="Mô tả chi tiết" placeholder="Chi tiền Tết 2026" wire:model='description'
                class="min-h-[120px]" />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Cộng / Trừ --}}
                <div>
                    <flux:select wire:model.lazy="type" placeholder="Chọn loại" variant="listbox" label="Thu / Chi">
                        <flux:select.option value="expense">Chi</flux:select.option>
                        <flux:select.option value="income">Thu</flux:select.option>
                    </flux:select>
                    @error('type')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>


                <div>
                    <flux:input mask:dynamic="$money($input)" wire:model="amount" placeholder="Nhập số tiền"
                        autocomplete="off" label="Tổng số tiền" />
                    @error('amount')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Cộng / Trừ --}}
                <div>
                    <flux:select wire:model.lazy="status" variant="listbox" label="Trạng thái">
                        <flux:select.option value="paid">Đã chi</flux:select.option>
                        <flux:select.option value="pending">Chưa chi</flux:select.option>
                    </flux:select>
                    @error('status')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>


                <div>
                    <flux:input wire:model="in_charge" placeholder="Tên người phụ trách" autocomplete="off"
                        label="Người phụ trách" />
                    @error('in_charge')
                        <x-app-error-message :message="$message" />
                    @enderror
                </div>
            </div>

            <flux:file-upload wire:model="file" label="Tải lên tập tin" accept=".pdf">
                <flux:file-upload.dropzone heading="Kéo thả file vào đây hoặc nhấn để chọn"
                    text="Chỉ hỗ trợ PDF (tối đa 10MB)" with-progress inline />
            </flux:file-upload>


            <div class="mt-3 flex flex-col gap-2">

                {{-- Nếu đang chỉnh sửa & có file cũ & chưa upload file mới --}}
                @if ($isEditTransactionMode && $existingFile && !$file)
                    <flux:file-item heading="File PDF" icon="document">
                        <x-slot name="actions">
                            <flux:link href="{{ $existingFile }}" target="_blank"
                                variant="ghost">
                                Xem
                            </flux:link>

                            <flux:file-item.remove wire:click="removeExistingFile" />
                        </x-slot>
                    </flux:file-item>
                @endif

                {{-- File mới được upload --}}
                @if ($file)
                    <flux:file-item :heading="$file->getClientOriginalName()" :size="$file->getSize()" icon="document">
                        <x-slot name="actions">
                            <flux:file-item.remove wire:click="removeFile" />
                        </x-slot>
                    </flux:file-item>
                @endif
            </div>



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
