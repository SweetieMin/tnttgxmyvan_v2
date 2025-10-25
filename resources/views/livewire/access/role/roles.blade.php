<div>
    <x-contents.layout heading="Chức vụ" subheading="Quản lý danh sách và thông tin Chức vụ" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Chức vụ']]"  buttonLabel="Thêm Chức vụ" buttonAction="addRole">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Chức vụ..." wire:model.live.debounce.300ms="search"
                     />
            </div>
        </div>


        {{-- Main content area --}}
        <div class="mt-2">
            <div x-data="{
                initSortable() {
                    const el = document.getElementById('sortable-roles');
                    if (el && !el.sortableInstance) {
                        el.sortableInstance = new Sortable(el, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: function() {
                                let orderedIds = [];
                                el.querySelectorAll('[data-id]').forEach(item => { orderedIds.push(item.getAttribute('data-id')); });
                                $wire.updateRolesOrdering(orderedIds);
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
                                    <th class="text-center">Tên chức vụ</th>
                                    <th >Mô tả</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="sortable-roles">
                                @forelse ($roles as $role)
                                <tr wire:key="role-desktop-{{ $role->id }}" data-id="{{ $role->id }}">
                                    <td class="text-center w-12 drag-handle cursor-move">{{ $role->ordering }}</td>
                                    <td class="text-center">{{ $role->name }}</td>
                                    <td >{{ $role->description }}</td>

                                    <td>
                                        <flux:dropdown position="bottom" align="end">
                                            <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                                variant="subtle" />
                                            <flux:menu>
                                                <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                    wire:click='editRole({{ $role->id }})'>
                                                    Sửa
                                                </flux:menu.item>

                                                <flux:menu.item class="cursor-pointer" icon="trash" variant="danger"
                                                    wire:click='deleteRole({{ $role->id }})'>
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

                    @if ($roles->hasPages())
                        <div class="py-4 px-5">
                            {{ $roles->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </x-contents.layout>

    <x-app-delete-modal name="delete-role" :width="600" title="Xác nhận xóa chức vụ"
    description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến chức vụ." warning-title="Cảnh báo xóa dữ liệu"
    message="Bạn có chắc chắn muốn xóa chức vụ này không?" :warnings="['Tất cả thông tin chức vụ sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteRoleConfirm" />

</div>
