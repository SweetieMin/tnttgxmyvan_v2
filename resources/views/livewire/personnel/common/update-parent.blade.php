<div class="space-y-6 py-4">
    <form wire:submit.prevent='updateParent()'>
        <flux:fieldset >
            <flux:legend>Cha</flux:legend>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                    <flux:input wire:model="christian_name_father" label="Tên thánh" placeholder="Nhập tên thánh" x-on:input="$el.value = $el.value.toLowerCase().replace(/(^|\s)\S/g, c => c.toUpperCase())"/>
                </div>
                <div class="md:col-span-2">
                    <flux:input wire:model="name_father" label="Họ và tên" placeholder="Nhập họ và tên" x-on:input="$el.value = $el.value.toLowerCase().replace(/(^|\s)\S/g, c => c.toUpperCase())"/>
                </div>
                <div class="md:col-span-1">
                    <flux:input wire:model="phone_father" label="Số ĐT" placeholder="Nhập số điện thoại" />
                </div>
            </div>
        </flux:fieldset>

        <flux:fieldset class="mt-2">
            <flux:legend>Mẹ</flux:legend>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                    <flux:input wire:model="christian_name_mother" label="Tên thánh" placeholder="Nhập tên thánh" x-on:input="$el.value = $el.value.toLowerCase().replace(/(^|\s)\S/g, c => c.toUpperCase())"/>
                </div>
                <div class="md:col-span-2">
                    <flux:input wire:model="name_mother" label="Họ và tên" placeholder="Nhập họ và tên" x-on:input="$el.value = $el.value.toLowerCase().replace(/(^|\s)\S/g, c => c.toUpperCase())"/>
                </div>
                <div class="md:col-span-1">
                    <flux:input wire:model="phone_mother" label="Số ĐT" placeholder="Nhập số điện thoại" />
                </div>
            </div>
        </flux:fieldset>

        <flux:fieldset class="mt-2">
            <flux:legend>Người đỡ đầu</flux:legend>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                    <flux:input wire:model="christian_name_god_parent" label="Tên thánh" placeholder="Nhập tên thánh" x-on:input="$el.value = $el.value.toLowerCase().replace(/(^|\s)\S/g, c => c.toUpperCase())"/>
                </div>
                <div class="md:col-span-2">
                    <flux:input wire:model="name_god_parent" label="Họ và tên" placeholder="Nhập họ và tên" x-on:input="$el.value = $el.value.toLowerCase().replace(/(^|\s)\S/g, c => c.toUpperCase())"/>
                </div>
                <div class="md:col-span-1">
                    <flux:input wire:model="phone_god_parent" label="Số ĐT" placeholder="Nhập số điện thoại" />
                </div>
            </div>
        </flux:fieldset>

        <flux:separator class="my-4" />

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" class="cursor-pointer" variant="primary">
                Lưu </flux:button>
        </div>
    </form>
</div>
