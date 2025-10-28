<div>
    <x-contents.layout heading="Thiếu Nhi" subheading="Quản lý danh sách và thông tin Thiếu Nhi" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Thiếu Nhi']]" :count="$childrens->total() ?? 0" buttonLabel="Thêm Thiếu Nhi" buttonAction="addChildren">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Thiếu Nhi..." wire:model.live.debounce.300ms="search"
                    :count="$childrens->total() ?? 0" />
            </div>
        </div>


        {{-- Main content area --}}
        <div class="mt-2">
            <div class="theme-table">
                {{-- Desktop Table View --}}
                <div class="hidden md:block ">
                    <table>
                        <thead>
                            <tr>
                                
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($childrens as $children)
                            <tr>
                                <td>
                                    <flux:dropdown position="bottom" align="end">
                                        <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                            variant="subtle" />
                                        <flux:menu>
                                            <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                wire:click='editChildren({{ $children->id }})'>
                                                Sửa
                                            </flux:menu.item>

                                            <flux:menu.item class="cursor-pointer" icon="trash" variant="danger"
                                                wire:click='deleteChildren({{ $children->id }})'>
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


                @if ($childrens->hasPages())
                    <div class="py-4 px-5">
                        {{ $childrens->links('vendor.pagination.tailwind') }}
                    </div>
                @endif

            </div>
        </div>

    </x-contents.layout>

    <livewire:personnel.children.actions-children />

</div>
