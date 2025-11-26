<div>
    <x-contents.layout heading="Lớp Giáo Lý & Ngành Sinh Hoạt" subheading="Quản lý danh sách và thông tin Lớp Giáo Lý & Ngành Sinh Hoạt" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Lớp Giáo Lý & Ngành Sinh Hoạt']]" buttonLabel="Thêm" buttonAction="addCourseSector">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm..." 
                     />
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
                        <flux:table
                            container:class=" "
                            class="w-full transition [&>tbody>tr]:transition-colors [&>tbody>tr:hover>td]:text-accent-content/70 [&>tbody>tr:hover]:scale-[0.998] [&>tbody>tr:hover]:bg-transparent">
                            <flux:table.columns sticky class="bg-white dark:bg-zinc-700">
                                <flux:table.column class="w-16 text-center">STT</flux:table.column>
                                <flux:table.column align="center">Niên khoá</flux:table.column>
                                <flux:table.column align="center">Chương trình</flux:table.column>
                                <flux:table.column align="center">Tên lớp</flux:table.column>
                                <flux:table.column align="center">Tên ngành</flux:table.column>
                                <flux:table.column class="w-20"></flux:table.column>
                            </flux:table.columns>

                            <flux:table.rows id="sortable-course-sectors" data-academic-year-id="">
                                
                            </flux:table.rows>
                        </flux:table>
                    </flux:card>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-3" id="sortable-courses-mobile"
                    data-academic-year-id="">
                   
                </div>


            </div>
        </div>


    </x-contents.layout>
</div>
