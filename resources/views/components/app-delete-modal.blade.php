@props([
    'name' => 'confirm-delete',      {{-- Tên modal (dùng cho Flux) --}}
    'width' => 500,                  {{-- Chiều rộng modal --}}
    'title' => 'Xác nhận xóa dữ liệu',
    'description' => 'Hành động này không thể hoàn tác.',
    'warningTitle' => 'Cảnh báo xóa dữ liệu',
    'message' => 'Bạn có chắc chắn muốn xóa mục này không?',
    'warnings' => [
        'Tất cả thông tin liên quan sẽ bị xóa vĩnh viễn.',
        'Các dữ liệu phụ thuộc có thể bị ảnh hưởng.',
        'Hành động này không thể hoàn tác.'
    ],
    'action' => null,                {{-- wire:submit action --}}
])

<flux:modal name="{{ $name }}" class="md:w-[{{ $width }}px]">
    <div class="bg-gradient-to-br from-red-50 via-white to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 rounded-2xl">
        <!-- Header -->
        <div class="relative px-8 py-6 bg-gradient-to-r from-red-500 via-pink-500 to-red-600 rounded-t-2xl">
            <div class="absolute inset-0 bg-black/10 rounded-t-2xl"></div>
            <div class="relative flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                    <flux:icon.trash class="w-6 h-6 text-white" />
                </div>
                <div>
                    <flux:heading class="font-bold text-white text-xl">
                        {{ $title }}
                    </flux:heading>
                    <flux:text class="mt-1 text-red-100">
                        {{ $description }}
                    </flux:text>
                </div>
            </div>
        </div>

        <form @if ($action) wire:submit="{{ $action }}" @endif class="px-8 py-6 space-y-6">
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <flux:icon.exclamation-triangle class="w-8 h-8 text-red-600 dark:text-red-400" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-red-800 dark:text-red-200">
                            {{ $warningTitle }}
                        </h3>
                        <div class="mt-2 text-red-700 dark:text-red-300">
                            <p class="mb-2">{{ $message }}</p>
                            @if ($warnings)
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    @foreach ($warnings as $warn)
                                        <li>{{ $warn }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <flux:modal.close>
                    <flux:button variant="ghost"
                        class="px-6 py-2 rounded-xl border border-gray-300 hover:bg-gray-50 transition-all duration-300">
                        Hủy bỏ
                    </flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger"
                    class="cursor-pointer px-8 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    Xóa vĩnh viễn
                </flux:button>
            </div>
        </form>
    </div>
</flux:modal>
