<div>
    <x-contents.layout heading="Giáo Lý Viên" subheading="Quản lý danh sách và thông tin Giáo Lý Viên" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Giáo Lý Viên']]"  buttonLabel="Thêm Giáo Lý Viên" buttonAction="addCatechist">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Giáo Lý Viên..." wire:model.live.debounce.300ms="search"
                     />
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
                            @forelse ($catechists as $catechist)
                            <tr>
                                <td>
                                    <flux:dropdown position="bottom" align="end">
                                        <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                            variant="subtle" />
                                        <flux:menu>
                                            <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                wire:click='editCatechist({{ $catechist->id }})'>
                                                Sửa
                                            </flux:menu.item>

                                            <flux:menu.item class="cursor-pointer" icon="trash" variant="danger"
                                                wire:click='deleteCatechist({{ $catechist->id }})'>
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


                @if ($catechists->hasPages())
                    <div class="py-4 px-5">
                        {{ $catechists->links('vendor.pagination.tailwind') }}
                    </div>
                @endif

            </div>
        </div>

    </x-contents.layout>

    <livewire:personnel.catechist.actions-catechist />

</div>
