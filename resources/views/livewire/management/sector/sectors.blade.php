<div>
    <x-contents.layout heading="Ngành Sinh Hoạt" subheading="Quản lý danh sách và thông tin ngành sinh hoạt" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Ngành Sinh Hoạt']]"  buttonLabel="Thêm ngành sinh hoạt" buttonAction="addSector">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm ngành sinh hoạt..." wire:model.live.debounce.300ms="search"  :years="$years"/>
            </div>
        </div>


        {{-- Main content area --}}
        <div class="mt-2">
            <div x-data="{
                initSortable() {
                    // Desktop sortable
                    const el = document.getElementById('sortable-sectors');
                    if (el && !el.sortableInstance) {
                        el.sortableInstance = new Sortable(el, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: function() {
                                let orderedIds = [];
                                el.querySelectorAll('[data-id]').forEach(item => { orderedIds.push(item.getAttribute('data-id')); });
                                $wire.updateSectorsOrdering(orderedIds);
                            }
                        });
                    }
                    
                    // Mobile sortable
                    const mobileEl = document.getElementById('sortable-sectors-mobile');
                    if (mobileEl && !mobileEl.sortableInstance) {
                        mobileEl.sortableInstance = new Sortable(mobileEl, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: function() {
                                let orderedIds = [];
                                mobileEl.querySelectorAll('[data-id]').forEach(item => { orderedIds.push(item.getAttribute('data-id')); });
                                $wire.updateSectorsOrdering(orderedIds);
                            }
                        });
                    }
                }
            }" x-init="initSortable()">
                <div class="theme-table">
                    {{-- Desktop Table View --}}
                    <div class="hidden md:block ">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-center w-12">STT</th>
                                    <th class="text-center">Ngành sinh hoạt</th>
                                    <th class="text-center">Niên khoá</th>
                                    <th class="text-center">Chương trình</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-sectors">
                                @forelse ($sectors as $sector)
                                    <tr wire:key="sector-desktop-{{ $sector->id }}" data-id="{{ $sector->id }}">
                                        <td class="text-center w-12 drag-handle cursor-move">{{ $sector->ordering }}</td>
                                        <td class="text-center">{{ $sector->sector }}</td>
                                        <td class="text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $sector->academicYear->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $sector->program->sector ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <flux:dropdown position="bottom" align="end">
                                                <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                                    variant="subtle" />
                                                <flux:menu>
                                                    <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                        wire:click='editSector({{ $sector->id }})'>Sửa
                                                    </flux:menu.item>
                                                    <flux:menu.item class="cursor-pointer" icon="trash"
                                                        variant="danger"
                                                        wire:click='deleteSector({{ $sector->id }})'> Xoá
                                                    </flux:menu.item>
                                                </flux:menu>
                                            </flux:dropdown>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state flex flex-col items-center">
                                                <flux:icon.squares-plus class="w-8 h-8 mb-2" />
                                                <div class="text-sm">Không có dữ liệu</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View --}}
                    <div class="md:hidden space-y-3" id="sortable-sectors-mobile">
                        @forelse ($sectors as $sector)
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm" wire:key="sector-mobile-{{ $sector->id }}" data-id="{{ $sector->id }}">
                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center cursor-move drag-handle">
                                            <span class="text-xs font-bold text-purple-600">{{ $sector->ordering }}</span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $sector->sector }}</div>
                                            <div class="text-sm text-gray-500">{{ $sector->program->course ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button wire:click="editSector({{ $sector->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-md">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="deleteSector({{ $sector->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-md">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-lg border border-gray-200 p-8">
                                <div class="empty-state flex flex-col items-center">
                                    <flux:icon.squares-plus class="w-8 h-8 mb-2 text-gray-400" />
                                    <div class="text-sm text-gray-500">Không có ngành sinh hoạt nào</div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if ($sectors->hasPages())
                        <div class="py-4 px-5">
                            {{ $sectors->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </x-contents.layout>

    <livewire:management.sector.actions-sector />

</div>
