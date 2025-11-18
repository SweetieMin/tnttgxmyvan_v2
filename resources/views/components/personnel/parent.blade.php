<div class="space-y-6 py-4">

    <flux:fieldset>
        <flux:legend>Cha</flux:legend>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <flux:input wire:model="christian_name_father" label="Tên thánh"
                    placeholder="Nhập tên thánh" />
            </div>
            <div class="md:col-span-2">
                <flux:input wire:model="name_father" label="Ngày sinh"
                    placeholder="Nhập họ và tên" />
            </div>
            <div class="md:col-span-1">
                <flux:input wire:model="phone_father" label="Số ĐT"
                    placeholder="Nhập số điện thoại" />
            </div>
        </div>
    </flux:fieldset>

    <flux:fieldset>
        <flux:legend>Mẹ</flux:legend>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <flux:input wire:model="christian_name_mother" label="Tên thánh"
                    placeholder="Nhập tên thánh" />
            </div>
            <div class="md:col-span-2">
                <flux:input wire:model="name_mother" label="Ngày sinh"
                    placeholder="Nhập họ và tên" />
            </div>
            <div class="md:col-span-1">
                <flux:input wire:model="phone_mother" label="Số ĐT"
                    placeholder="Nhập số điện thoại" />
            </div>
        </div>
    </flux:fieldset>

    <flux:fieldset>
        <flux:legend>Người đỡ đầu</flux:legend>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <flux:input wire:model="christian_name_god_parent" label="Tên thánh"
                    placeholder="Nhập tên thánh" />
            </div>
            <div class="md:col-span-2">
                <flux:input wire:model="name_god_parent" label="Ngày sinh"
                    placeholder="Nhập họ và tên" />
            </div>
            <div class="md:col-span-1">
                <flux:input wire:model="phone_god_parent" label="Số ĐT"
                    placeholder="Nhập số điện thoại" />
            </div>
        </div>
    </flux:fieldset>

    <flux:separator class="my-4" />

    <div class="flex">
        <flux:spacer />
        <flux:button type="submit" class="cursor-pointer" variant="primary">
            Lưu </flux:button>
    </div>

</div>