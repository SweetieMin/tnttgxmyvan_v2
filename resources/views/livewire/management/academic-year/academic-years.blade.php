<div>
    <x-contents.layout heading="Niên Khoá" subheading="Quản lý danh sách và thông tin niên khoá" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Niên khoá']]" :count="1" buttonLabel="Thêm niên khoá" buttonAction="addAcademicYear">

        {{-- Component Search & Filter --}}
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm niên khoá..." wire:model.live.debounce.300ms="search" />
            </div>
        </div>

    {{-- Main content area --}}
    <div class="mt-6">
        <div class="theme-table">
            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            
                        </tr>
                    </thead>
                    <tbody>
                        
                        
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden space-y-3">
                
                
            </div>
            
            
            
        </div>
    </div>
        
        
    </x-contents.layout>



</div>
