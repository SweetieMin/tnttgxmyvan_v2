<div>
    <x-contents.layout heading="Chương trình học" subheading="Quản lý danh sách và thông tin chương trình học"
        icon="squares-plus" :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Chương trình học']]" :count="$programs->total() ?? 0" buttonLabel="Thêm chương trình học"
        buttonAction="addProgram()">

        {{-- Main content area --}}
        <div class="mt-2">
            <div class="theme-table">
                {{-- Desktop Table View --}}
                <div class="hidden md:block ">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-center w-12">STT</th>
                                <th class="text-center">Niên khóa</th>
                                <th class="text-center">Giáo lý</th>
                                <th class="text-center">Sinh hoạt</th>
                                <th class="text-center">Mô tả</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($programs as $program)
                                <tr>
                                    <td class="text-center w-12">
                                        {{ $programs->ordering }}
                                    </td>
                                    <td class="text-center">{{ $program->catechism }}</td>
                                    <td class="text-center">{{ $program->activity }}</td>
                                    <td>{{ $program->description }}</td>
                                    <td>
                                        <flux:dropdown position="bottom" align="end">
                                            <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                                variant="subtle" />
                                            <flux:menu>
                                                <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                    wire:click='editProgram({{ $program->id }})'>Sửa
                                                </flux:menu.item>
                                                <flux:menu.item class="cursor-pointer" icon="trash" variant="danger"
                                                    wire:click='deleteProgram({{ $program->id }})'>
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
                                            <flux:icon.bookmark-square class="w-8 h-8 mb-2" />
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


                    <div class="p-4">
                        <div class="empty-state flex flex-col items-center">
                            <flux:icon.squares-plus class="w-8 h-8 mb-2 " />
                            <div class="text-sm">Không có dữ liệu</div>
                        </div>
                    </div>


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
