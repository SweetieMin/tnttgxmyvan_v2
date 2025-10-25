<div>
    <x-contents.layout heading="Lớp Giáo Lý" subheading="Quản lý danh sách và thông tin Lớp Giáo Lý" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Lớp Giáo Lý']]" :count="$courses->total() ?? 0" buttonLabel="Thêm Lớp Giáo Lý" buttonAction="addCourse">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Lớp Giáo Lý..." wire:model.live.debounce.300ms="search"
                     :years="$years" />
            </div>
        </div>


        {{-- Main content area --}}
        <div class="mt-2">
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
                    <div class="hidden md:block">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-center w-12">STT</th>
                                    <th class="text-center">Tên lớp</th>
                                    <th class="text-center">Niên khoá</th>
                                    <th class="text-center">Chương trình</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-courses" data-academic-year-id="{{ $selectedYear ?? null }}">
                                @forelse ($courses as $course)
                                <tr wire:key="course-desktop-{{ $course->id }}" data-id="{{ $course->id }}">
                                    <td class="text-center w-12 drag-handle cursor-move">{{ $course->ordering }}</td>
                                    <td class="text-center">{{ $course->course }}</td>
                                    <td class="text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $course->academicYear->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $course->program->course ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <flux:dropdown position="bottom" align="end">
                                            <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                                variant="subtle" />
                                            <flux:menu>
                                                <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                    wire:click='editCourse({{ $course->id }})'>
                                                    Sửa
                                                </flux:menu.item>

                                                <flux:menu.item class="cursor-pointer" icon="trash" variant="danger"
                                                    wire:click='deleteCourse({{ $course->id }})'>
                                                    Xoá
                                                </flux:menu.item>

                                            </flux:menu>
                                        </flux:dropdown>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">
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
                        <div id="sortable-courses-mobile" data-academic-year-id="{{ $selectedYear ?? null }}">
                            @forelse ($courses as $course)
                            <div class="sortable-row bg-white rounded-lg border border-gray-200 p-4 shadow-sm" 
                                 wire:key="course-mobile-{{ $course->id }}"
                                 data-id="{{ $course->id }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="drag-handle cursor-move">
                                            <flux:icon.bars-3 class="w-5 h-5 text-gray-400" />
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $course->course }}</div>
                                            <div class="text-sm text-gray-500">
                                                STT: {{ $course->ordering }} | 
                                                Năm: {{ $course->academicYear->name ?? 'N/A' }} | 
                                                Chương trình: {{ $course->program->course ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <flux:button size="sm" icon="pencil-square" variant="subtle"
                                            wire:click='editCourse({{ $course->id }})' />
                                        <flux:button size="sm" icon="trash" variant="danger"
                                            wire:click='deleteCourse({{ $course->id }})' />
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state flex flex-col items-center py-8">
                                <flux:icon.squares-plus class="w-8 h-8 mb-2 text-gray-400" />
                                <div class="text-sm text-gray-500">Không có dữ liệu</div>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    @if ($courses->hasPages())
                        <div class="py-4 px-5">
                            {{ $courses->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </x-contents.layout>

    <livewire:management.course.actions-course />

</div>
