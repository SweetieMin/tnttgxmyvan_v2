<div>

    <x-contents.layout heading="Nội quy" subheading="Quản lý danh sách và thông tin nội quy" icon="squares-plus"
        :breadcrumb="[
            ['label' => 'Bảng điều khiển', 'url' => route('dashboard')],
            ['label' => 'Nội quy', 'url' => route('admin.management.regulations')],
            ['label' => 'Thêm nội quy'],
        ]" buttonLabelBack="Quay lại" buttonBackAction="backRegulation">


        <form wire:submit.prevent='{{ $isEditRegulationMode ? 'updateRegulation' : 'createRegulation' }}'
            class=" bg-accent-background rounded-2xl p-4">



            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

                <div class="space-y-4">
                    {{-- Hàng 1: Niên khoá --}}
                    <div>
                        <label for="academic_year_id" class="block text-sm font-medium mb-1">Niên khoá</label>
                        <flux:select wire:model.lazy="academic_year_id" placeholder="Chọn niên khoá">
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
                            <flux:select wire:model.lazy="type" placeholder="Chọn loại">
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
                    <flux:textarea label="Mô tả chức vụ" placeholder="Cộng tác, Giáo viên, Học sinh,..."
                        wire:model='description' />
                </div>

            </div>

            <flux:separator text="Áp dụng các chức vụ" />
            <div class='mb-4'>
                <flux:checkbox.group wire:model="regulationApplyRole">
                    <flux:checkbox.all label="Chọn tất cả" />
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        @foreach ($roles as $role)
                            <label
                                class="block p-2 bg-accent-card rounded-lg border border-accent/20 hover:border-accent cursor-pointer ">
                                <flux:checkbox label-class="text-red-600" value="{{ $role->id }}" label="{{ $role->name }}"
                                    description="{{ $role->description }}" />
                            </label>
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
