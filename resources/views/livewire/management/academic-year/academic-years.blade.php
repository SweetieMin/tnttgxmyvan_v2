<div>
    <x-contents.layout heading="Niên Khoá" subheading="Quản lý danh sách và thông tin niên khoá" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Niên khoá']]"  buttonLabel="Thêm niên khoá" buttonAction="addAcademicYear">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm niên khoá..." wire:model.live.debounce.300ms="search"
                    :count="$academic_years->total() ?? 0" />
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
                                <th class="text-center w-12">STT</th>
                                <th class="text-center">Niên khóa</th>
                                <th class="text-center">Giáo lý</th>
                                <th class="text-center">Điểm giáo lý</th>
                                <th class="text-center">Điểm chuyên cần giáo lý</th>
                                <th class="text-center">Sinh hoạt</th>
                                <th class="text-center">Điểm sinh hoạt</th>
                                <th class="text-center">Thạng thái</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($academic_years as $year)
                                <tr>
                                    <td class="text-center w-12">
                                        {{ ($academic_years->currentPage() - 1) * $academic_years->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="text-center">{{ $year->name }}</td>
                                    <td class="text-center">{{ $year->catechism_period }}</td>
                                    <td class="text-center">{{ $year->catechism_avg_score }} <span
                                            class="text-accent/50">/10</span></td>
                                    <td class="text-center">{{ $year->catechism_training_score }}<span
                                            class="text-accent/50">/10</span></td>
                                    <td class="text-center">{{ $year->activity_period }}</td>
                                    <td class="text-center">{{ $year->activity_score }}</td>
                                    <td class="text-center">
                                        <flux:badge color="{{ $year->status_academic_color }}">
                                            {{ $year->status_academic_label }}
                                        </flux:badge>
                                    </td>
                                    <td>
                                        <flux:dropdown position="bottom" align="end">
                                            <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                                variant="subtle" />
                                            <flux:menu>
                                                <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                    wire:click='editAcademicYear({{ $year->id }})'>Sửa
                                                </flux:menu.item>
                                                <flux:menu.item class="cursor-pointer" icon="trash" variant="danger" wire:click='deleteAcademicYear({{ $year->id }})'>
                                                    Xoá
                                                </flux:menu.item>
                                            </flux:menu>
                                        </flux:dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
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
                    @forelse ($academic_years as $year)
                        <div class="bg-accent-background rounded-xl border border-accent/20 shadow-sm overflow-hidden"
                            x-data="{ expanded: false }" wire:key="academic-year-mobile-{{ $year->id }}">

                            {{-- Header (collapsed row) --}}
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-accent-text">{{ $year->name }}</span>
                                    <span class="text-sm text-accent-text/70">
                                        Giáo lý: {{ $year->catechism_period }} — Sinh hoạt:
                                        {{ $year->activity_period }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">

                                    <button @click="expanded = !expanded"
                                        class="p-2 rounded-full hover:bg-accent-background/50 transition"
                                        aria-label="Toggle details">
                                        <svg class="w-5 h-5 text-accent-text transition-transform duration-300"
                                            :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Expanded details --}}
                            <div x-show="expanded" x-transition
                                class="border-t border-accent/10 bg-accent-background/60 backdrop-blur-sm">
                                <div class="p-4 space-y-3 text-sm text-accent-text/90">

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

                                    <div class="flex justify-between">
                                        <span>Niên khóa:</span>
                                        <span>{{ $year->name }}</span>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="pt-3 border-t border-accent/10 flex gap-2">

                                        <button wire:click="deleteCourse({{ $year->id }})"
                                            class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition-colors flex items-center justify-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            <span>Xóa</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4">
                            <div class="empty-state flex flex-col items-center">
                                <flux:icon.squares-plus class="w-8 h-8 mb-2 " />
                                <div class="text-sm ">Không có lớp học nào</div>
                            </div>
                        </div>
                    @endforelse
                </div>


                @if ($academic_years->hasPages())
                    <div class="py-4 px-5">
                        {{ $academic_years->links('vendor.pagination.tailwind') }}
                    </div>
                @endif

            </div>
        </div>

    </x-contents.layout>

    <livewire:management.academic-year.actions-academic-year />

</div>
