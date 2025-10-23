<div>
    <x-contents.layout heading="Chương trình học" subheading="Quản lý danh sách và thông tin chương trình học"
        icon="squares-plus" :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Chương trình học']]" :count="$programs->total() ?? 0" buttonLabel="Thêm Chương trình học"
        buttonAction="addProgram">
        {{-- Main content area --}}
        <div class="mt-2">
            <div x-data="{
                initSortable() {
                    const el = document.getElementById('sortable-programs');
                    if (el && !el.sortableInstance) {
                        el.sortableInstance = new Sortable(el, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: function() {
                                let orderedIds = [];
                                el.querySelectorAll('[data-id]').forEach(item => { orderedIds.push(item.getAttribute('data-id')); });
                                $wire.updateProgramsOrdering(orderedIds);
                            }
                        });
                    }
                }
            }" x-init="initSortable()">
                <div class="theme-table"> {{-- Desktop Table View --}} <div class="hidden md:block ">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-center w-12">STT</th>
                                    <th class="text-center">Lớp Giáo Lý</th>
                                    <th class="text-center">Ngành Sinh Hoạt</th>
                                    <th>Mô tả</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="sortable-programs">
                                @forelse ($programs as $program)
                                    <tr wire:key="program-desktop-{{ $program->id }}" data-id="{{ $program->id }}">
                                        <td class="text-center w-12 drag-handle cursor-move">{{ $program->ordering }}
                                        </td>
                                        <td class="text-center">{{ $program->course }}</td>
                                        <td class="text-center">{{ $program->sector }}</td>
                                        <td>{{ $program->description }}</td>
                                        <td>
                                            <flux:dropdown position="bottom" align="end">
                                                <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                                    variant="subtle" />
                                                <flux:menu>
                                                    <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                        wire:click='editProgram({{ $program->id }})'>Sửa
                                                    </flux:menu.item>
                                                    <flux:menu.item class="cursor-pointer" icon="trash"
                                                        variant="danger"
                                                        wire:click='deleteProgram({{ $program->id }})'> Xoá
                                                    </flux:menu.item>
                                                </flux:menu>
                                            </flux:dropdown>
                                        </td>
                                </tr> @empty <tr>
                                        <td colspan="6">
                                            <div class="empty-state flex flex-col items-center"> <flux:icon.squares-plus
                                                    class="w-8 h-8 mb-2" />
                                                <div class="text-sm">Không có dữ liệu</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> {{-- Mobile Card View --}} <div class="md:hidden space-y-3"> </div>
                    @if ($programs->hasPages())
                        <div class="py-4 px-5"> {{ $programs->links('vendor.pagination.tailwind') }} </div>
                    @endif
                </div>
            </div>
        </div>

    </x-contents.layout>

    <livewire:management.program.actions-program />

</div>
