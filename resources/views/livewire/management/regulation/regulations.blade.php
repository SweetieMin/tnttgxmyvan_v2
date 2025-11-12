<div>
    <x-contents.layout heading="Nội quy" subheading="Quản lý danh sách và thông tin nội quy" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Nội quy']]" buttonLabel="Thêm Nội quy" buttonAction="addRegulation">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Nội quy / điểm..."
                    :years="$years" />
            </div>
        </div>
        
        {{-- Main content area --}}

            <div x-data="{
                initSortable() {
                    // Desktop sortable
                    const desktopEl = document.getElementById('sortable-regulations');
                    if (desktopEl && !desktopEl.sortableInstance) {
                        desktopEl.sortableInstance = new Sortable(desktopEl, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: function() {
                                let orderedIds = [];
                                desktopEl.querySelectorAll('[data-id]').forEach(item => {
                                    orderedIds.push(item.getAttribute('data-id'));
                                });
                                const academicYearId = desktopEl.getAttribute('data-academic-year-id');
                                if (academicYearId && orderedIds.length > 0) {
                                    $wire.updateRegulationOrdering(orderedIds, academicYearId);
                                }
                            }
                        });
                    }
            
                    // Mobile sortable
                    const mobileEl = document.getElementById('sortable-regulations-mobile');
                    if (mobileEl && !mobileEl.sortableInstance) {
                        mobileEl.sortableInstance = new Sortable(mobileEl, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: function() {
                                let orderedIds = [];
                                mobileEl.querySelectorAll('[data-id]').forEach(item => {
                                    orderedIds.push(item.getAttribute('data-id'));
                                });
                                const academicYearId = mobileEl.getAttribute('data-academic-year-id');
                                if (academicYearId && orderedIds.length > 0) {
                                    $wire.updateRegulationOrdering(orderedIds, academicYearId);
                                }
                            }
                        });
                    }
                }
            }" x-init="initSortable()">

                    {{-- Desktop Table View --}}
                    <div class="hidden md:block ">
                        <flux:card class="overflow-hidden border border-accent/20 rounded-xl shadow-sm">
                            <flux:table container:class="max-h-[55vh] overflow-y-auto custom-scrollbar"
                                class="w-full transition [&>tbody>tr]:transition-colors [&>tbody>tr:hover>td]:text-accent-content/70 [&>tbody>tr:hover]:scale-[0.998] [&>tbody>tr:hover]:bg-transparent">
                                {{-- ===== HEADER ===== --}}
                                <flux:table.columns sticky class="bg-white dark:bg-zinc-700">
                                    <flux:table.column class="w-12 text-center">STT</flux:table.column>
                                    <flux:table.column>Mô tả</flux:table.column>
                                    <flux:table.column align="center" class="w-32">Điểm</flux:table.column>
                                    <flux:table.column class="w-16"></flux:table.column>
                                </flux:table.columns>

                                {{-- ===== BODY ===== --}}
                                <flux:table.rows id="sortable-regulations" data-academic-year-id="{{ $selectedYear ?? null }}">
                                    @forelse ($regulations as $regulation)
                                        <flux:table.row wire:key="regulation-desktop-{{ $regulation->id }}" data-id="{{ $regulation->id }}">
                                            <flux:table.cell align="center" class="drag-handle cursor-move">
                                                {{ $regulation->ordering }}
                                            </flux:table.cell>
                                            <flux:table.cell>
                                                {{ $regulation->description }}
                                            </flux:table.cell>
                                            <flux:table.cell align="center">
                                                <flux:badge variant="solid"
                                                    color="{{ $regulation->type === 'plus' ? 'green' : 'red' }}">
                                                    {{ $regulation->points }}
                                                </flux:badge>
                                            </flux:table.cell>
                                            <flux:table.cell class="text-right">
                                                <flux:dropdown position="bottom" align="end">
                                                    <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                                        variant="subtle" />
                                                    <flux:menu>
                                                        <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                            wire:click='editRegulation({{ $regulation->id }})'>
                                                            Sửa
                                                        </flux:menu.item>
                                                        <flux:menu.item class="cursor-pointer" icon="trash"
                                                            variant="danger"
                                                            wire:click='deleteRegulation({{ $regulation->id }})'>
                                                            Xoá
                                                        </flux:menu.item>
                                                    </flux:menu>
                                                </flux:dropdown>
                                            </flux:table.cell>
                                        </flux:table.row>
                                    @empty
                                        <flux:table.row>
                                            <flux:table.cell colspan="4">
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
                    <div class="md:hidden space-y-3" id="sortable-regulations-mobile" data-academic-year-id="{{ $selectedYear ?? null }}">
                        @forelse ($regulations as $regulation)
                            <flux:accordion wire:key="regulation-mobile-{{ $regulation->id }}" transition variant="reverse" data-id="{{ $regulation->id }}">
                                <flux:card class="space-y-6">
                                    <flux:accordion.item>
                                        <flux:accordion.heading>
                                            <div class="flex items-center justify-between gap-3">
                                                <span class="font-semibold text-accent-text">{{ $regulation->ordering }}. {{ $regulation->description }}</span>
                                                <flux:badge variant="solid" class="drag-handle cursor-move"
                                                    color="{{ $regulation->type === 'plus' ? 'green' : 'red' }}">
                                                    {{ $regulation->points }}
                                                </flux:badge>
                                            </div>
                                        </flux:accordion.heading>

                                        <flux:accordion.content>
                                            <div class="space-y-3 text-sm text-accent-text/90 mt-2">
                                                <div class="pt-3 border-t border-accent/10 flex gap-2">
                                                    <flux:button wire:click='editRegulation({{ $regulation->id }})'
                                                        icon="pencil-square" variant="filled" class="flex-1">
                                                        Sửa
                                                    </flux:button>
                                                    <flux:button wire:click='deleteRegulation({{ $regulation->id }})' icon="trash"
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
            </div>

    </x-contents.layout>

    <x-app-delete-modal name="delete-regulation" :width="600" title="Xác nhận xóa Nội quy"
    description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Nội quy." warning-title="Cảnh báo xóa dữ liệu"
    message="Bạn có chắc chắn muốn xóa Nội quy này không?" :warnings="['Tất cả thông tin Nội quy sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteRegulationConfirm" />



</div>
