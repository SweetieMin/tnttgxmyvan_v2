<div>
    <x-contents.layout heading="Course" subheading="Quản lý danh sách và thông tin course" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Course']]" :count="$courses->total() ?? 0" buttonLabel="Thêm course" buttonAction="addCourse">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm course..." wire:model.live.debounce.300ms="search"
                    :count="$courses->total() ?? 0" />
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
                                <th class="text-center">Tên</th>
                                <th class="text-center">Mô tả</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Ngày tạo</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($courses as $course)
                                
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


                @if ($courses->hasPages())
                    <div class="py-4 px-5">
                        {{ $courses->links('vendor.pagination.tailwind') }}
                    </div>
                @endif

            </div>
        </div>

    </x-contents.layout>

    <livewire:{{ groupLower }}.course.actions-course />

</div>
