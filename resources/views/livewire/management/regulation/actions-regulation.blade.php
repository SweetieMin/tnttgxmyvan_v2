<div>

    <x-contents.layout heading="Nội quy" subheading="Quản lý danh sách và thông tin nội quy" icon="squares-plus"
        :breadcrumb="[
            ['label' => 'Bảng điều khiển', 'url' => route('dashboard')],
            ['label' => 'Nội quy', 'url' => route('admin.management.regulations')],
            ['label' => 'Thêm / Chỉnh sửa nội quy'],
        ]" buttonLabelBack="Quay lại" buttonBackAction="backRegulation">

        <flux:separator text="Thông tin nội quy" />

        <form wire:submit.prevent='{{ $isEditRegulationMode ? 'updateRegulation' : 'createRegulation' }}'
            class=" bg-accent-background rounded-2xl p-4">



            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

                <div class="space-y-4">
                    {{-- Hàng 1: Niên khoá --}}
                    <div>
                        <label for="academic_year_id" class="block text-sm font-medium mb-1">Niên khoá</label>
                        <flux:select variant="listbox" wire:model.lazy="academic_year_id" placeholder="Chọn niên khoá">
                            @foreach ($years as $year)
                                <flux:select.option value="{{ $year->id }}">{{ $year->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        @error('academic_year_id')
                            <x-app-error-message :message="$message" />
                        @enderror
                    </div>

                    {{-- Hàng 2: Cộng/Trừ và Điểm --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Cộng / Trừ --}}
                        <div>
                            <label for="type" class="block text-sm font-medium mb-1">Cộng / Trừ</label>
                            <flux:select wire:model="type" placeholder="Chọn loại" variant="listbox">
                                <flux:select.option value="plus">Cộng điểm</flux:select.option>
                                <flux:select.option value="minus">Trừ điểm</flux:select.option>
                            </flux:select>
                            @error('type')
                                <x-app-error-message :message="$message" />
                            @enderror
                        </div>

                        {{-- Điểm --}}
                        <div>
                            <label for="points" class="block text-sm font-medium mb-1">Điểm</label>
                            <flux:input type="number" wire:model="points" placeholder="Nhập điểm" />
                            @error('points')
                                <x-app-error-message :message="$message" />
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <flux:textarea label="Mô tả nội quy" placeholder="Tham dự thánh lễ,...." wire:model='description'
                        class="min-h-[120px]" />
                </div>

            </div>

            <flux:separator text="Áp dụng các chức vụ" />
            <div class='my-4'>
                <flux:checkbox.group wire:model="regulationApplyRole" variant="cards" class="max-sm:flex-col">

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-h-[350px] overflow-y-auto p-1 custom-scrollbar">

                        <flux:checkbox.all label="Chọn tất cả" class="cursor-pointer" />

                        @foreach ($roles as $role)
                            <flux:checkbox value="{{ $role->id }}" label="{{ $role->name }}"
                                description="{{ $role->description }}" class="cursor-pointer" />
                        @endforeach



                    </div>
                </flux:checkbox.group>
            </div>

            <flux:separator />
            <div class="flex py-4 mx-4">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditRegulationMode ? 'Cập nhật' : 'Thêm mới' }} </flux:button>
            </div>
        </form>

    </x-contents.layout>



</div>
