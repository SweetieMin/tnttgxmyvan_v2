<div class="space-y-6 py-4">

    <flux:fieldset>
        <flux:legend>Rửa Tội</flux:legend>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <flux:date-picker wire:model="baptism_date" label="Ngày"
                    placeholder="Chọn ngày" locale="vi-VN" selectable-header
                    with-today clearable />
            </div>
            <div class="md:col-span-2">
                <flux:input wire:model="baptismal_sponsor" label="Linh Mục cử hành"
                    placeholder="Nhập tên Linh Mục" />
            </div>
            <div class="md:col-span-1">
                <flux:input wire:model="baptism_place" label="Địa điểm"
                    placeholder="Nhập địa điểm" />
            </div>
        </div>
    </flux:fieldset>

    <flux:fieldset>
        <flux:legend>Xưng Tội - Rước Lễ lần đầu</flux:legend>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <flux:date-picker wire:model="baptism_date" label="Ngày"
                    placeholder="Chọn ngày" locale="vi-VN" selectable-header
                    with-today clearable />
            </div>
            <div class="md:col-span-2">
                <flux:input wire:model="baptismal_sponsor" label="Linh Mục cử hành"
                    placeholder="Nhập tên Linh Mục" />
            </div>
            <div class="md:col-span-1">
                <flux:input wire:model="baptism_place" label="Địa điểm"
                    placeholder="Nhập địa điểm" />
            </div>
        </div>
    </flux:fieldset>

    <flux:fieldset>
        <flux:legend>Thêm Sức</flux:legend>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <flux:date-picker wire:model="confirmation_date" label="Ngày"
                placeholder="Chọn ngày" locale="vi-VN" selectable-header with-today clearable/>
            </div>
            <div class="md:col-span-2">
                <flux:input wire:model="confirmation_bishop" label="Giám Mục cử hành"
                    placeholder="Nhập tên Giám Mục" />
            </div>
            <div class="md:col-span-1">
                <flux:input wire:model="confirmation_place" label="Địa điểm"
                    placeholder="Nhập địa điểm" />
            </div>
        </div>
    </flux:fieldset>

    <flux:fieldset>
        <flux:legend>Tuyên Hứa Bao Đồng</flux:legend>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <flux:date-picker wire:model="pledge_date" label="Ngày"
                placeholder="Chọn ngày" locale="vi-VN" selectable-header with-today clearable/>
            </div>
            <div class="md:col-span-2">
                <flux:input wire:model="pledge_sponsor" label="Linh Mục cử hành"
                    placeholder="Nhập tên Linh Mục" />
            </div>
            <div class="md:col-span-1">
                <flux:input wire:model="pledge_place" label="Địa điểm"
                    placeholder="Nhập địa điểm" />
            </div>
        </div>
    </flux:fieldset>

    <flux:separator text="..." />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <flux:spacer />
        <div class="md:col-span-1">
            <flux:select wire:model='status_religious' variant="listbox" placeholder="Trạng thái công giáo" label="Trạng thái Công Giáo">
                <flux:select.option value="in_course" selected>Đang học</flux:select.option>
                <flux:select.option value="graduated">Đã tốt nghiệp</flux:select.option>
            </flux:select>
        </div>
        <div class="md:col-span-1">
            <flux:select wire:model='is_attendance' variant="listbox" placeholder="Trạng thái công giáo" label="Trạng thái Công Giáo">
                <flux:select.option value="true" selected>Điểm danh</flux:select.option>
                <flux:select.option value="false">Không điểm danh</flux:select.option>
            </flux:select>
        </div>
    </div>

    <flux:separator class="my-4" />

    <div class="flex">
        <flux:spacer />
        <flux:button type="submit" class="cursor-pointer" variant="primary">
            Lưu </flux:button>
    </div>

</div>