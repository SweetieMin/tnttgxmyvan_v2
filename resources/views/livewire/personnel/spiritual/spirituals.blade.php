<div>
    <x-contents.layout heading="Người linh hướng" subheading="Quản lý danh sách và thông tin Người linh hướng"
        icon="squares-plus" :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Người linh hướng']]" buttonLabel="Thêm Linh hướng" buttonAction="addSpiritual">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm người linh hướng..." />
            </div>
        </div>


        {{-- Desktop Table View --}}
        <div class="hidden md:block mt-4">
            <flux:card class="overflow-hidden border border-accent/20 rounded-xl shadow-sm">
                <flux:table 
                    class="w-full transition [&>tbody>tr]:transition-colors [&>tbody>tr:hover>td]:text-accent-content/70 [&>tbody>tr]:hover:bg-transparent">
                    <flux:table.columns>
                        <flux:table.column class="w-10 text-center">STT</flux:table.column>
                        <flux:table.column align="center" class="w-35 text-center">Mã tài khoản</flux:table.column>
                        <flux:table.column align="center" class="w-40">Họ và tên</flux:table.column>
                        <flux:table.column align="center">Chức vụ</flux:table.column>
                        <flux:table.column align="center">Số điện thoại</flux:table.column>
                        <flux:table.column align="center">Email</flux:table.column>
                        <flux:table.column></flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse ($spirituals as $index => $spiritual)
                            <flux:table.row :key="$spiritual->id">
                                <flux:table.cell align="center">
                                    {{ $spirituals->firstItem() + $index }}
                                </flux:table.cell>
                                <flux:table.cell align="center">{{ $spiritual->account_code }}</flux:table.cell>
                                <flux:table.cell align="center" class="flex items-center gap-3">

                                    <flux:avatar badge badge:color="{{ $spiritual->status_color }}" badge:circle
                                        size="xl" circle src="{{ $spiritual->details?->picture }}" />
                                    {{ $spiritual->full_name }}

                                </flux:table.cell>

                                <flux:table.cell align="center">
                                    <flux:badge color="{{ $spiritual->roles?->first()?->type_color }}">
                                        {{ $spiritual->roles?->first()?->name ?? 'Chưa phân công' }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell align="center">{{ $spiritual->details?->phone ?? 'Chưa cập nhật' }}</flux:table.cell>
                                <flux:table.cell align="center">{{ $spiritual->email ?? 'Chưa cập nhật' }}</flux:table.cell>
                                <flux:table.cell class="text-right">
                                    <flux:dropdown position="bottom" align="end">
                                        <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                            variant="subtle" />
                                        <flux:menu>
                                            <flux:menu.item icon="pencil-square"
                                                wire:click='editSpiritual({{ $spiritual->id }})'>
                                                Sửa
                                            </flux:menu.item>
                                            <flux:menu.item icon="trash" variant="danger"
                                                wire:click='deleteSpiritual({{ $spiritual->id }})'>
                                                Xoá
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="7">
                                    <div class="py-6 text-center text-accent-text/70">
                                        <flux:icon.squares-plus class="w-8 h-8 mx-auto mb-2" />
                                        Không có dữ liệu
                                    </div>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </flux:card>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden space-y-3 mt-4">
            @forelse ($spirituals as $spiritual)
                <flux:accordion wire:key="spiritual-{{ $spiritual->id }}" transition variant="reverse">
                    <flux:card class="space-y-6">
                        <flux:accordion.item>
                            <flux:accordion.heading>
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-3 flex-1">
                                        <flux:avatar badge badge:color="{{ $spiritual->status_color }}" badge:circle
                                            size="xl" circle src="{{ $spiritual->details?->picture }}"
                                            :name="$spiritual->full_name" />
                                        <div class="flex flex-col text-left">

                                            <span
                                                class="text-sm text-accent-text/70">{{ $spiritual->christian_name ?? '—' }}</span>
                                            <span
                                                class="font-semibold text-accent-text">{{ $spiritual->full_name }}</span>
                                        </div>
                                    </div>

                                    <flux:badge color="{{ $spiritual->roles?->first()?->type_color }}">
                                        {{ $spiritual->roles?->first()?->name ?? 'Chưa phân công' }}
                                    </flux:badge>

                                </div>
                            </flux:accordion.heading>

                            <flux:accordion.content>
                                <div class="space-y-3 text-sm text-accent-text/90 mt-2">

                                    <div class="flex justify-between">
                                        <span>Mã tài khoản:</span>
                                        <span>{{ $spiritual->account_code }}</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span>Số điện thoại:</span>
                                        <span>{{ $spiritual->details?->phone ?? 'Chưa cập nhật' }}</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span>Email:</span>
                                        <span>{{ $spiritual->email ?? 'Chưa cập nhật' }}</span>
                                    </div>

                                    <div class="pt-3 border-t border-accent/10 flex gap-2">
                                        <flux:button wire:click='editSpiritual({{ $spiritual->id }})'
                                            icon="pencil-square" variant="filled" class="flex-1">
                                            Sửa
                                        </flux:button>
                                        <flux:button wire:click='deleteSpiritual({{ $spiritual->id }})' icon="trash"
                                            variant="danger" class="flex-1">
                                            Xóa
                                        </flux:button>
                                    </div>
                                </div>
                            </flux:accordion.content>
                        </flux:accordion.item>
                    </flux:card>
                </flux:accordion>

            @empty
                <flux:card class="p-6 text-center">
                    <flux:icon.squares-plus class="w-8 h-8 mb-2 text-muted-foreground" />
                    <flux:text>Không có dữ liệu</flux:text>
                </flux:card>
            @endforelse
        </div>


        @if ($spirituals->hasPages())
            <div class="py-4 px-5">
                {{ $spirituals->links('vendor.pagination.tailwind') }}
            </div>
        @endif

    </x-contents.layout>

    <livewire:personnel.spiritual.action-spiritual />

</div>
