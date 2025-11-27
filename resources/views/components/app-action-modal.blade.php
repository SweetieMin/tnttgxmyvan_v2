@props([
    'name' => 'default-modal',      {{-- Tên modal Flux --}}
    'title' => 'Tiêu đề',           {{-- Tiêu đề modal --}}
    'subheading' => null,           {{-- Phụ đề --}}
    'icon' => null,                 {{-- Icon tên hoặc SVG path --}}
    'dismissible' => true,          {{-- Có thể đóng bằng click outside hay không --}}
    'class' => null,                {{-- Class tùy chọn --}}
    'closeEvent' => null,
])

<flux:modal 
    :dismissible="$dismissible" 
    name="{{ $name }}" 
    class="w-full max-w-[90vw] max-h-[90vh] overflow-y-auto {{ $class }}" @close="{{ $closeEvent }}">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-3">
            @if ($icon)
                <flux:icon :name="$icon" class="w-6 h-6 text-primary" />
            @endif
            <div>
                <flux:heading size="lg" class="font-bold">
                    {{ $title }}
                </flux:heading>
                @if ($subheading)
                    <flux:text class="text-muted mt-1">
                        {{ $subheading }}
                    </flux:text>
                @endif
            </div>
        </div>

        <flux:separator />

        {{-- Nội dung form / slot chính --}}
        <div class="space-y-4">
            {{ $slot }}
        </div>

    </div>
</flux:modal>
