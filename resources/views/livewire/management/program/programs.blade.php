<div>
    <x-contents.layout heading="Chương trình học" subheading="Quản lý danh sách và thông tin chương trình học"
        icon="squares-plus" :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Chương trình học']]"  buttonLabel="Thêm Chương trình học"
        buttonAction="addProgram">
        {{-- Main content area --}}
        <div class="mt-2">
            <div x-data="{
                initSortable() {
                    const desktopEl = document.getElementById('sortable-programs');
                    if (desktopEl && !desktopEl.sortableInstance) {
                        desktopEl.sortableInstance = new Sortable(desktopEl, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: function() {
                                let orderedIds = [];
                                desktopEl.querySelectorAll('[data-id]').forEach(item => {
                                    orderedIds.push(item.getAttribute('data-id'));
                                });
                                if (orderedIds.length > 0) {
                                    $wire.updateProgramsOrdering(orderedIds);
                                }
                            }
                        });
                    }

                    const mobileEl = document.getElementById('sortable-programs-mobile');
                    if (mobileEl && !mobileEl.sortableInstance) {
                        mobileEl.sortableInstance = new Sortable(mobileEl, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: function() {
                                let orderedIds = [];
                                mobileEl.querySelectorAll('[data-id]').forEach(item => {
                                    orderedIds.push(item.getAttribute('data-id'));
                                });
                                if (orderedIds.length > 0) {
                                    $wire.updateProgramsOrdering(orderedIds);
                                }
                            }
                        });
                    }
                }
            }" x-init="initSortable()">

                {{-- Desktop Table View --}}
                <div class="hidden md:block ">
                    <flux:card class="overflow-hidden border border-accent/20 rounded-xl shadow-sm">
                        <flux:table container:class="max-h-[calc(76vh-90px)] overflow-y-auto custom-scrollbar"
                            class="w-full transition [&>tbody>tr]:transition-colors [&>tbody>tr:hover>td]:text-accent-content/70 [&>tbody>tr:hover]:scale-[0.998] [&>tbody>tr:hover]:bg-transparent">
                            <flux:table.columns sticky class="bg-white dark:bg-zinc-700">
                                <flux:table.column class="w-12">STT</flux:table.column>
                                <flux:table.column align="center">Lớp Giáo Lý</flux:table.column>
                                <flux:table.column align="center">Ngành Sinh Hoạt</flux:table.column>
                                <flux:table.column class="w-30">Mô tả</flux:table.column>
                                <flux:table.column class="w-16"></flux:table.column>
                            </flux:table.columns>

                            <flux:table.rows id="sortable-programs">
                                @forelse ($programs as $program)
                                    <flux:table.row wire:key="program-desktop-{{ $program->id }}" data-id="{{ $program->id }}">
                                        <flux:table.cell align="center" class="drag-handle cursor-move">
                                            {{ $program->ordering }}
                                        </flux:table.cell>
                                        <flux:table.cell align="center">
                                            {{ $program->course }}
                                        </flux:table.cell>
                                        <flux:table.cell align="center">
                                            {{ $program->sector }}
                                        </flux:table.cell>
                                        <flux:table.cell>
                                            {{ $program->description }}
                                        </flux:table.cell>
                                        <flux:table.cell class="text-right">
                                            <flux:dropdown position="bottom" align="end">
                                                <flux:button class="cursor-pointer" icon="ellipsis-horizontal" variant="subtle" />
                                                <flux:menu>
                                                    <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                        wire:click='editProgram({{ $program->id }})'>
                                                        Sửa
                                                    </flux:menu.item>
                                                    <flux:menu.item class="cursor-pointer" icon="trash"
                                                        variant="danger" wire:click='deleteProgram({{ $program->id }})'>
                                                        Xoá
                                                    </flux:menu.item>
                                                </flux:menu>
                                            </flux:dropdown>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @empty
                                    <flux:table.row>
                                        <flux:table.cell colspan="5">
                                            <div class="empty-state flex flex-col items-center py-6">
                                                <flux:icon.squares-plus class="w-8 h-8 mb-2" />
                                                <div class="text-sm">Không có dữ liệu</div>
                                            </div>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforelse
                            </flux:table.rows>
                        </flux:table>
                    </flux:card>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-3" id="sortable-programs-mobile">
                    @forelse ($programs as $program)
                        <flux:accordion wire:key="program-mobile-{{ $program->id }}" transition variant="reverse" data-id="{{ $program->id }}">
                            <flux:card class="space-y-6">
                                <flux:accordion.item>
                                    <flux:accordion.heading>
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="drag-handle cursor-move inline-flex items-center justify-center w-8 h-8 rounded-full bg-accent text-sm font-semibold">
                                                        {{ $program->ordering }}
                                                    </span>
                                                    <div class="grid grid-cols-2 flex-1">
                                                        <span class="font-semibold text-accent-text">{{ $program->course }}</span>
                                                        <span class="font-semibold text-accent-text text-right">Ngành: {{ $program->sector }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </flux:accordion.heading>

                                    <flux:accordion.content>
                                        <div class="space-y-3 text-sm text-accent-text/90 mt-2">
                                            <div>{{ $program->description }}</div>

                                            <div class="pt-3 border-t border-accent/10 flex gap-2">
                                                <flux:button wire:click='editProgram({{ $program->id }})'
                                                    icon="pencil-square" variant="filled" class="flex-1">
                                                    Sửa
                                                </flux:button>
                                                <flux:button wire:click='deleteProgram({{ $program->id }})' icon="trash"
                                                    variant="danger" class="flex-1">
                                                    Xoá
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

                @if ($programs->hasPages())
                    <div class="py-4 px-5">
                        {{ $programs->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>

    </x-contents.layout>

    <livewire:management.program.actions-program />

</div>
