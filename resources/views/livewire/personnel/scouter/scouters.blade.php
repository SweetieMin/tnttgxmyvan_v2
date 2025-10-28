<div>
    <x-contents.layout heading="Huynh-Dự-Đội Trưởng" subheading="Quản lý danh sách và thông tin Huynh-Dự-Đội Trưởng" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Huynh-Dự-Đội Trưởng']]" :count="$scouters->total() ?? 0" buttonLabel="Thêm Huynh-Dự-Đội Trưởng" buttonAction="addScouter">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Huynh-Dự-Đội Trưởng..." wire:model.live.debounce.300ms="search"
                    :count="$scouters->total() ?? 0" />
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
                            @forelse ($scouters as $scouter)
                            <tr>
                                <td>
                                    <flux:dropdown position="bottom" align="end">
                                        <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                            variant="subtle" />
                                        <flux:menu>
                                            <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                wire:click='editScouter({{ $scouter->id }})'>
                                                Sửa
                                            </flux:menu.item>

                                            <flux:menu.item class="cursor-pointer" icon="trash" variant="danger"
                                                wire:click='deleteScouter({{ $scouter->id }})'>
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


                @if ($scouters->hasPages())
                    <div class="py-4 px-5">
                        {{ $scouters->links('vendor.pagination.tailwind') }}
                    </div>
                @endif

            </div>
        </div>

    </x-contents.layout>

    <livewire:personnel.scouter.actions-scouter />

</div>
