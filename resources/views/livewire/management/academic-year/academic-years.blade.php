<div>
    <x-contents.layout heading="Niên Khoá" subheading="Quản lý danh sách và thông tin niên khoá" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Niên khoá']]" buttonLabel="Thêm niên khoá" buttonAction="addAcademicYear">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm niên khoá..." :count="$academic_years->total() ?? 0" />
            </div>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden md:block ">
            <flux:card class="overflow-hidden border border-accent/20 rounded-xl shadow-sm">

                <flux:table
                    container:class=" {{ $academic_years->hasPages() ? 'max-h-[calc(100vh-541px)]' : 'max-h-[calc(100vh-455px)]' }}"
                    class="w-full transition [&>tbody>tr]:transition-colors [&>tbody>tr:hover>td]:text-accent-content/70 [&>tbody>tr:hover]:scale-[0.998] [&>tbody>tr:hover]:bg-transparent">
                    {{-- ===== HEADER ===== --}}
                    <flux:table.columns>
                        <flux:table.column class="w-10 ">STT</flux:table.column>
                        <flux:table.column align='center'>Niên khóa</flux:table.column>
                        <flux:table.column align='center'>Giáo lý</flux:table.column>
                        <flux:table.column align='center'>Điểm giáo lý</flux:table.column>
                        <flux:table.column align='center'>Điểm chuyên cần giáo lý</flux:table.column>
                        <flux:table.column align='center'>Sinh hoạt</flux:table.column>
                        <flux:table.column align='center'>Điểm sinh hoạt</flux:table.column>
                        <flux:table.column align='center'>Trạng thái</flux:table.column>
                        <flux:table.column></flux:table.column>
                    </flux:table.columns>

                    {{-- ===== BODY ===== --}}
                    <flux:table.rows>
                        @foreach ($academic_years as $year)
                            <flux:table.row :key="$year->id">

                                <flux:table.cell align='center'>
                                    {{ ($academic_years->currentPage() - 1) * $academic_years->perPage() + $loop->iteration }}
                                </flux:table.cell>
                                <flux:table.cell align='center'>{{ $year->name }}</flux:table.cell>
                                <flux:table.cell align='center'>{{ $year->catechism_period }}</flux:table.cell>
                                <flux:table.cell align='center'>{{ $year->catechism_avg_score }} <span
                                        class="text-accent/50">/10</span></flux:table.cell>
                                <flux:table.cell align='center'>{{ $year->catechism_training_score }} <span
                                        class="text-accent/50">/10</span></flux:table.cell>
                                <flux:table.cell align='center'>{{ $year->activity_period }}</flux:table.cell>
                                <flux:table.cell align='center'>{{ $year->activity_score }}</flux:table.cell>
                                <flux:table.cell align='center'>
                                    <flux:badge color="{{ $year->status_academic_color }}">
                                        {{ $year->status_academic_label }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell class="text-right">
                                    <flux:dropdown position="bottom" align="end">
                                        <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                            variant="subtle" />
                                        <flux:menu>
                                            <flux:menu.item icon="pencil-square"
                                                wire:click='editAcademicYear({{ $year->id }})'>
                                                Sửa
                                            </flux:menu.item>
                                            <flux:menu.item icon="trash" variant="danger"
                                                wire:click='deleteAcademicYear({{ $year->id }})'>
                                                Xoá
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>

            </flux:card>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden space-y-3">
            @forelse ($academic_years as $year)
                <flux:accordion wire:key="academic-year-{{ $year->id }}" transition variant="reverse">
                    <flux:card class="space-y-6">
                        <flux:accordion.item>
                            <flux:accordion.heading>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex items-center justify-center min-w-8 min-h-8 rounded-full bg-accent text-sm font-semibold">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="flex flex-col text-left">
                                        <span class="font-semibold text-accent-text">{{ $year->name }}</span>
                                    </div>
                                </div>
                            </flux:accordion.heading>

                            <flux:accordion.content>
                                <div class="space-y-3 text-sm text-accent-text/90 mt-2">

                                    <div class="flex justify-between">
                                        <span>Điểm giáo lý:</span>
                                        <span>{{ $year->catechism_avg_score }} /10</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span>Điểm chuyên cần GL:</span>
                                        <span>{{ $year->catechism_training_score }} /10</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span>Điểm sinh hoạt:</span>
                                        <span>{{ $year->activity_score }} /10</span>
                                    </div>


                                    <div class="pt-3 border-t border-accent/10 flex gap-2">
                                        <flux:button wire:click="editAcademicYear({{ $year->id }})"
                                            icon="pencil-square" variant="filled" class="flex-1">
                                            Sửa
                                        </flux:button>
                                        <flux:button wire:click="deleteAcademicYear({{ $year->id }})"
                                            icon="trash" variant="danger" class="flex-1">
                                            Xóa
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
                    <flux:text>Không có lớp học nào</flux:text>
                </flux:card>
            @endforelse

        </div>


        @if ($academic_years->hasPages())
            <div class="py-4 px-5">
                {{ $academic_years->links('vendor.pagination.tailwind') }}
            </div>
        @endif



    </x-contents.layout>

    <livewire:management.academic-year.actions-academic-year />

</div>
