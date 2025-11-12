@props([
    'years' => [],
    'items' => [], // Hạng mục cho tiền quỹ
    'searchPlaceholder' => 'Tìm kiếm...',
    'bulkActions' => false,
    'fillDate' => false,
    'exportData' => false,
    'count' => 1,
])

<flux:card class="space-y-6 mb-4">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 ">

        {{-- LEFT: Search + Filters --}}
        <div class="flex-1">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Search --}}
                <div class="flex flex-col">
                    <flux:input icon="magnifying-glass" wire:model.live.debounce.500ms="search" type="text"
                        placeholder="{{ $searchPlaceholder }}" label="Tìm kiếm" />
                </div>


                @if (!empty($items) && count($items) > 0)
                    <div class="flex flex-col">
                        <flux:select wire:model.live="itemFilter" variant="listbox" searchable clearable multiple
                            placeholder="Chọn hạng mục..." label="Hạng mục">
                            @foreach ($items as $item)
                                <flux:select.option value="{{ $item->id }}">{{ $item->name }} -
                                    {{ $item->description }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                @endif

                {{-- Filter: Niên khoá --}}
                @if (!empty($years) && count($years) > 0)
                    <div class="flex flex-col">

                        <flux:select wire:model.live="yearFilter" variant="listbox" searchable  indicator="checkbox" clear="close"
                            placeholder="Chọn niên khoá..." label="Niên khoá">
                            @foreach ($years as $year)
                                <flux:select.option value="{{ $year->id }}">{{ $year->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                @endif

                @if ($fillDate)
                    <div class="flex flex-col">
                        <flux:date-picker wire:model.live="range" mode="range" label="Lọc theo ngày" selectable-header
                            fixed-weeks locale="vi-VN" clearable placeholder="Chọn khoảng thời gian..." />
                    </div>
                @endif

                @if ($bulkActions)
                    <div class="flex flex-col">
                        <flux:field>
                            <flux:label>Hàng loạt</flux:label>
                            <flux:dropdown>
                                <flux:button icon:trailing="chevron-down">Chọn thao tác</flux:button>
                                <flux:menu>
                                    <flux:menu.item wire:click='bulkActionEdit()' icon="pencil-square">Chỉnh sửa</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item wire:click='bulkActionDelete()' variant="danger" icon="trash">Xoá</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:field>
                        

                    </div>
                @endif

            </div>
        </div>

        {{-- RIGHT: Dòng/trang --}}
        @if ($count > 10)
            <div class="flex-shrink-0 md:w-40">
                <flux:select wire:model.live="perPage" variant="listbox"  placeholder="Dòng/trang" label="Dòng/trang">
                    <flux:select.option value="10">10</flux:select.option>
                    <flux:select.option value="25">25</flux:select.option>
                    <flux:select.option value="50">50</flux:select.option>
                    <flux:select.option value="100">100</flux:select.option>
                    <flux:select.option value="">Tất cả</flux:select.option>
                </flux:select>
            </div>
        @endif

        @if ($exportData)
            <flux:button wire:click='exportData' icon="arrow-down-tray">Xuất Excel</flux:button>
        @endif
    </div>
</flux:card>
