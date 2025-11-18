<div>
    <x-contents.layout heading="Chức vụ" subheading="Quản lý danh sách và thông tin Chức vụ" icon="squares-plus"
        :breadcrumb="[
            ['label' => 'Bảng điều khiển', 'url' => route('dashboard')],
            ['label' => 'Chức vụ', 'url' => route('admin.access.roles')],
            ['label' => 'Thêm / Chỉnh sửa Chức vụ'],
        ]" buttonLabelBack="Quay lại" buttonBackAction="backRole">

        <form wire:submit.prevent='{{ $isEditRoleMode ? 'updateRole' : 'createRole' }}'
            class=" bg-accent-background rounded-2xl p-4">



            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

                <div class="md:col-span-1 space-y-4">
                    <flux:input type="text" label="Tên chức vụ" wire:model='name' />

                    <flux:select variant="listbox" placeholder="Chọn loại chức vụ..." wire:model='type' label="Loại chức vụ">
                        <flux:select.option value="children">Thiếu nhi</flux:select.option>
                        <flux:select.option value="scouter">Huynh Dự Đội Trưởng</flux:select.option>
                        <flux:select.option value="catechist">Giáo Lý Viên</flux:select.option>
                        <flux:select.option value="spiritual">Linh Hướng</flux:select.option>
                        <flux:select.option value="system">Hệ thống</flux:select.option>
                    </flux:select>

                </div>

                <div class="md:col-span-2">
                    <flux:textarea label="Mô tả chức vụ" placeholder="Cộng tác, Giáo viên, Học sinh,..."
                        wire:model='description' class="min-h-[120px]"/>
                </div>

            </div>
            <flux:separator text="Quản lý các chức vụ" />
            <div class='mb-4'>


                <flux:checkbox.group wire:model.live="hierarchies" variant="cards" class="max-sm:flex-col">

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-h-[350px]  overflow-y-auto p-1 custom-scrollbar">

                        <flux:checkbox.all label="Chọn tất cả" class="cursor-pointer"
                            description="Đã chọn {{ count($hierarchies) ?? 0 }}" />

                        @foreach ($rolesExceptCurrentRole as $role)
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
                    {{ $isEditRoleMode ? 'Cập nhật' : 'Thêm mới' }} </flux:button>
            </div>
        </form>


    </x-contents.layout>

</div>
