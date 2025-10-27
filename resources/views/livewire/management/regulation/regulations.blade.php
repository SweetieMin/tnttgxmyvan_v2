<div>
    <x-contents.layout heading="Nội quy" subheading="Quản lý danh sách và thông tin nội quy" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Nội quy']]" buttonLabel="Thêm Nội quy" buttonAction="addRegulation">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Nội quy / điểm..." wire:model.live.debounce.300ms="search"
                    :years="$years" />
            </div>
        </div>


        {{-- Main content area --}}
        <div class="mt-2">
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
                <div class="theme-table">
                    {{-- Desktop Table View --}}
                    <div class="hidden md:block ">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th>Mô tả</th>
                                    <th class="text-center">Điểm</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="sortable-regulations" data-academic-year-id="{{ $selectedYear ?? null }}">
                                @forelse ($regulations as $regulation)
                                    <tr  wire:key="regulation-desktop-{{ $regulation->id }}" data-id="{{ $regulation->id }}">

                                        <td class="text-center w-12 drag-handle cursor-move"> {{ $regulation->ordering }} </td>
                                        <td> {{ $regulation->description }} </td>
                                        <td class="text-center">

                                            <flux:badge variant="solid"
                                                color="{{ $regulation->type === 'plus' ? 'green' : 'red' }}">
                                                {{ $regulation->points }} </flux:badge>
                                        </td>
                                        <td>
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
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
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
                    <div class="md:hidden space-y-3">

                    </div>



                </div>
            </div>
        </div>

    </x-contents.layout>

    <x-app-delete-modal name="delete-regulation" :width="600" title="Xác nhận xóa Nội quy"
    description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến Nội quy." warning-title="Cảnh báo xóa dữ liệu"
    message="Bạn có chắc chắn muốn xóa Nội quy này không?" :warnings="['Tất cả thông tin Nội quy sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteRegulationConfirm" />



</div>
