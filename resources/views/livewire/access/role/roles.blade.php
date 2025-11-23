<div>
    <x-contents.layout heading="Chức vụ" subheading="Quản lý danh sách và thông tin Chức vụ" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Chức vụ']]"  buttonLabel="Thêm Chức vụ" buttonAction="addRole">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Chức vụ..." :count="$roles->total() ?? 0"
                     />
            </div>
        </div>


        {{-- Main content area --}}

            <div x-data="{
                initSortable() {
                    // Desktop sortable
                    const desktopEl = document.getElementById('sortable-roles');
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
                                    $wire.updateRolesOrdering(orderedIds);
                                }
                            }
                        });
                    }
                    
                    // Mobile sortable
                    const mobileEl = document.getElementById('sortable-roles-mobile');
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
                                    $wire.updateRolesOrdering(orderedIds);
                                }
                            }
                        });
                    }
                }
            }" x-init="initSortable()">
                <div class="theme-table">
                    {{-- Desktop Table View --}}
                    <div class="hidden md:block ">
                        <flux:card class="overflow-hidden border border-accent/20 rounded-xl shadow-sm">
                    <flux:table container:class=" {{ $roles->hasPages() ? 'max-h-[calc(100vh-541px)]' : 'max-h-[calc(100vh-455px)]' }}"
                                class="w-full transition [&>tbody>tr]:transition-colors [&>tbody>tr:hover>td]:text-accent-content/70 [&>tbody>tr:hover]:scale-[0.998] [&>tbody>tr:hover]:bg-transparent">
                                <flux:table.columns sticky class="bg-white dark:bg-zinc-700">
                                    <flux:table.column class="w-16">STT</flux:table.column>
                                    <flux:table.column align="center" class="w-40">Tên chức vụ</flux:table.column>
                                    <flux:table.column align="center" class="w-50">Loại chức vụ</flux:table.column>
                                    <flux:table.column align="left">Mô tả</flux:table.column>
                                    <flux:table.column class="w-20"></flux:table.column>
                                </flux:table.columns>

                                <flux:table.rows id="sortable-roles">
                                    @forelse ($roles as $role)
                                        <flux:table.row wire:key="role-desktop-{{ $role->id }}" data-id="{{ $role->id }}">
                                            <flux:table.cell align="center" class="drag-handle cursor-move">
                                                {{ $role->ordering }}
                                            </flux:table.cell>
                                            <flux:table.cell align="center">
                                                {{ $role->name }}
                                            </flux:table.cell>
                                            <flux:table.cell align="center">
                                                <flux:badge color="{{ $role->type_color }}">{{ $role->type_label }}</flux:badge>
                                            </flux:table.cell>
                                            <flux:table.cell align="left">
                                                {{ $role->description }}
                                            </flux:table.cell>
                                            <flux:table.cell class="text-right">
                                                <flux:dropdown position="bottom" align="end">
                                                    <flux:button class="cursor-pointer" icon="ellipsis-horizontal" variant="subtle" />
                                                    <flux:menu>
                                                        <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                            wire:click='editRole({{ $role->id }})'>
                                                            Sửa
                                                        </flux:menu.item>
                                                        <flux:menu.item class="cursor-pointer" icon="trash"
                                                            variant="danger" wire:click='deleteRole({{ $role->id }})'>
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
                    <div class="md:hidden space-y-3" id="sortable-roles-mobile">
                        @forelse ($roles as $role)
                            <flux:accordion wire:key="role-mobile-{{ $role->id }}" transition variant="reverse" data-id="{{ $role->id }}">
                                <flux:card class="space-y-6">
                                    <flux:accordion.item>
                                        <flux:accordion.heading>
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-center gap-2">
                                                    <span class="drag-handle cursor-move inline-flex items-center justify-center min-w-8 min-h-8 rounded-full bg-accent text-sm font-semibold">
                                                        {{ $role->ordering }}
                                                    </span>
                                                    <div class="flex flex-col text-left">
                                                        <span class="font-semibold text-accent-text">{{ $role->name }}</span>
                                                        <span class="text-xs text-accent-text/70">{{ $role->description }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </flux:accordion.heading>

                                        <flux:accordion.content>
                                            <div class="space-y-3 text-sm text-accent-text/90 mt-2">

                                                <div class="flex justify-between">
                                                    <span>Loại chức vụ:</span>
                                                    <span><flux:badge color="{{ $role->type_color }}">{{ $role->type_label }}</flux:badge></span>
                                                </div>

                                                <div class="pt-3 border-t border-accent/10 flex gap-2">
                                                    <flux:button wire:click='editRole({{ $role->id }})'
                                                        icon="pencil-square" variant="filled" class="flex-1">
                                                        Sửa
                                                    </flux:button>
                                                    <flux:button wire:click='deleteRole({{ $role->id }})' icon="trash"
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

                    @if ($roles->hasPages())
                        <div class="py-4 px-5">
                            {{ $roles->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>
            </div>

    </x-contents.layout>

    <x-app-delete-modal name="delete-role" :width="600" title="Xác nhận xóa chức vụ"
    description="Hành động này sẽ xóa toàn bộ dữ liệu liên quan đến chức vụ." warning-title="Cảnh báo xóa dữ liệu"
    message="Bạn có chắc chắn muốn xóa chức vụ này không?" :warnings="['Tất cả thông tin chức vụ sẽ bị xóa vĩnh viễn.', 'Hành động này không thể hoàn tác.']" action="deleteRoleConfirm" />

</div>
