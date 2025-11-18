@props([
    'isEditSpiritualMode' => true,
    'roles' => null,
    'courses' => null,
    'sectors' => null,
    'user' => null,
])



<form wire:submit.prevent="{{ $isEditSpiritualMode ? 'updateSpiritual' : 'createSpiritual' }}" class="space-y-6 py-4">

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

        {{-- TÊN THÁNH (1 phần) --}}
        <div class="md:col-span-1">
            <flux:input
                wire:model="christian_name"
                label="Tên thánh *"
                placeholder="Nhập tên thánh"
                x-on:input="$el.value = $el.value.toLowerCase().replace(/(^|\s)\S/g, c => c.toUpperCase())"
            />
        </div>

        {{-- HỌ VÀ TÊN (3 phần) --}}
        <div class="md:col-span-3">
            <flux:input
                wire:model.lazy="full_name"
                label="Họ và tên *"
                placeholder="Nhập họ và tên"
                x-on:input="$el.value = $el.value.toLowerCase().replace(/(^|\s)\S/g, c => c.toUpperCase())"
            />
        </div>

        {{-- GIỚI TÍNH (1 phần) --}}
        <div class="md:col-span-1">
            <flux:field>
                <flux:label>Giới tính</flux:label>

                <flux:radio.group wire:model="gender" class="ml-2">
                    <flux:radio value="male" label="Nam" checked />
                    <flux:radio value="female" label="Nữ" />
                </flux:radio.group>

                <flux:error name="gender" />
            </flux:field>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <flux:date-picker wire:model.lazy="birthday" label="Ngày sinh *" placeholder="Chọn ngày sinh" locale="vi-VN"
                selectable-header :disabled="$isEditSpiritualMode" />
        </div>
        <div class="md:col-span-1">
            <flux:input wire:model="account_code" label="Mã tài khoản" placeholder="Nhập mã tài khoản" disabled />
        </div>

        <div class="md:col-span-1">
            <flux:select variant="listbox" wire:model="status_login" label="Trạng thái đăng nhập"
                placeholder="Chọn trạng thái">
                <flux:select.option value="active">Hoạt động</flux:select.option>
                <flux:select.option value="locked">Khóa</flux:select.option>
                <flux:select.option value="inactive">Không hoạt động
                </flux:select.option>
            </flux:select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        @if (collect($roles)->isNotEmpty())
            <div class="md:col-span-1">
                <flux:select variant="listbox" wire:model="position" label="Chức vụ" placeholder="Chọn chức vụ">
                    @foreach ($roles as $role)
                        <flux:select.option value="{{ $role->id }}">{{ $role->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
        @endIf

        @if (collect($courses)->isNotEmpty())
            <div class="md:col-span-1">
                <flux:select variant="listbox" wire:model="catechism_class" label="Lớp Giáo Lý"
                    placeholder="Chọn lớp giáo lý">
                    @foreach ($courses as $course)
                        <flux:select.option value="{{ $course->id }}">{{ $course->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
        @endIf
        @if (collect($sectors)->isNotEmpty())
            <div class="md:col-span-1">
                <flux:input wire:model="activity_branch" label="Ngành Sinh hoạt" placeholder="Nhập ngành sinh hoạt" />
                <flux:select variant="listbox" wire:model="activity_branch" label="Ngành Sinh hoạt"
                    placeholder="Chọn ngành Sinh hoạt">
                    @foreach ($sectors as $sector)
                        <flux:select.option value="{{ $sector->id }}">{{ $sector->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
        @endIf

    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <flux:input wire:model="phone" label="Số ĐT" placeholder="Nhập số điện thoại" />
        </div>
        <div class="md:col-span-1">
            <flux:input wire:model="address" label="Địa chỉ *" placeholder="Nhập địa chỉ" />
        </div>
        <div class="md:col-span-1">
            <flux:input wire:model="email" type="email" label="Email" placeholder="Nhập email" />
        </div>
    </div>


    <flux:textarea wire:model="bio" label="Giới thiệu" placeholder="Nhập giới thiệu" rows="4"
        class="min-h-[120px]" />

    <flux:separator class="my-4" />

    <div class="flex">
        <flux:spacer />
        <flux:button type="submit" class="cursor-pointer" variant="primary">
            {{ $isEditSpiritualMode ? 'Cập nhật' : 'Thêm mới' }} </flux:button>
    </div>

</form>
