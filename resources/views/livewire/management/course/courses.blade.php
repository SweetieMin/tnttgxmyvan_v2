<div>
    <x-contents.layout heading="Lớp Giáo Lý" subheading="Quản lý danh sách và thông tin Lớp Giáo Lý" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Lớp Giáo Lý']]"  buttonLabel="Thêm Lớp Giáo Lý" buttonAction="addCourse">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Lớp Giáo Lý..." wire:model.live.debounce.300ms="search"
                     :years="$years" />
            </div>
        </div>


        {{-- Main content area --}}

            <div x-data="{
                initSortable() {
                    // Desktop sortable
                    const desktopEl = document.getElementById('sortable-courses');
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
                                    $wire.updateCourseOrdering(orderedIds, academicYearId);
                                }
                            }
                        });
                    }
                    
                    // Mobile sortable
                    const mobileEl = document.getElementById('sortable-courses-mobile');
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
                                    $wire.updateCourseOrdering(orderedIds, academicYearId);
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
 <flux:table container:class=" {{ $courses->hasPages() ? 'max-h-[calc(100vh-541px)]' : 'max-h-[calc(100vh-455px)]' }}"
                                class="w-full transition [&>tbody>tr]:transition-colors [&>tbody>tr:hover>td]:text-accent-content/70 [&>tbody>tr:hover]:scale-[0.998] [&>tbody>tr:hover]:bg-transparent">
                                <flux:table.columns sticky class="bg-white dark:bg-zinc-700">
                                    <flux:table.column class="w-16 text-center">STT</flux:table.column>
                                    <flux:table.column align="center">Tên lớp</flux:table.column>
                                    <flux:table.column align="center">Niên khoá</flux:table.column>
                                    <flux:table.column align="center">Chương trình</flux:table.column>
                                    <flux:table.column class="w-20"></flux:table.column>
                                </flux:table.columns>

                                <flux:table.rows id="sortable-courses" data-academic-year-id="{{ $selectedYear ?? null }}">
                                    @forelse ($courses as $course)
                                        <flux:table.row wire:key="course-desktop-{{ $course->id }}" data-id="{{ $course->id }}">
                                            <flux:table.cell align="center" class="drag-handle cursor-move">
                                                {{ $course->ordering }}
                                            </flux:table.cell>
                                            <flux:table.cell align="center">
                                                {{ $course->course }}
                                            </flux:table.cell>
                                            <flux:table.cell align="center">
                                                <flux:badge variant="solid" color="green">
                                                    {{ $course->academicYear->name ?? 'N/A' }}
                                                </flux:badge>
                                            </flux:table.cell>
                                            <flux:table.cell align="center">
                                                <flux:badge variant="solid" color="green">
                                                    {{ $course->program->course ?? 'N/A' }}
                                                </flux:badge>
                                            </flux:table.cell>
                                            <flux:table.cell class="text-right">
                                                <flux:dropdown position="bottom" align="end">
                                                    <flux:button class="cursor-pointer" icon="ellipsis-horizontal" variant="subtle" />
                                                    <flux:menu>
                                                        <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                            wire:click='editCourse({{ $course->id }})'>
                                                            Sửa
                                                        </flux:menu.item>
                                                        <flux:menu.item class="cursor-pointer" icon="trash"
                                                            variant="danger" wire:click='deleteCourse({{ $course->id }})'>
                                                            Xoá
                                                        </flux:menu.item>
                                                    </flux:menu>
                                                </flux:dropdown>
                                            </flux:table.cell>
                                        </flux:table.row>
                                    @empty
                                        <flux:table.row>
                                            <flux:table.cell colspan="5">
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
                    <div class="md:hidden space-y-3" id="sortable-courses-mobile" data-academic-year-id="{{ $selectedYear ?? null }}">
                        @forelse ($courses as $course)
                            <flux:accordion wire:key="course-mobile-{{ $course->id }}" transition variant="reverse" data-id="{{ $course->id }}">
                                <flux:card class="space-y-6">
                                    <flux:accordion.item>
                                        <flux:accordion.heading>
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-center gap-2">
                                                    <span class="drag-handle cursor-move inline-flex items-center justify-center min-w-8 min-h-8 rounded-full bg-accent text-sm font-semibold">
                                                        {{ $course->ordering }}
                                                    </span>
                                                    <div class="flex flex-col text-left">
                                                        <span class="font-semibold text-accent-text">{{ $course->course }}</span>
                                                        <span class="text-xs text-accent-text/70">Niên khoá: {{ $course->academicYear->name ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <flux:badge variant="solid" color="green">
                                                    {{ $course->program->course ?? 'N/A' }}
                                                </flux:badge>
                                            </div>
                                        </flux:accordion.heading>

                                        <flux:accordion.content>
                                            <div class="space-y-3 text-sm text-accent-text/90 mt-2">
                                                <div class="pt-3 border-t border-accent/10 flex gap-2">
                                                    <flux:button wire:click='editCourse({{ $course->id }})'
                                                        icon="pencil-square" variant="filled" class="flex-1">
                                                        Sửa
                                                    </flux:button>
                                                    <flux:button wire:click='deleteCourse({{ $course->id }})' icon="trash"
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

                    @if ($courses->hasPages())
                        <div class="py-4 px-5">
                            {{ $courses->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>
            </div>


    </x-contents.layout>

    <livewire:management.course.actions-course />

</div>
