@props([
    'heading' => null,          {{-- Tiêu đề chính --}}
    'subheading' => null,       {{-- Mô tả ngắn --}}
    'breadcrumb' => [],         {{-- Mảng breadcrumb --}}
    'buttonLabel' => null,      {{-- Nút thêm --}}
    'buttonAction' => null,     {{-- Livewire action hoặc route --}}
    'buttonLabelBack' => null,  {{-- Nút quay lại --}}
    'buttonBackAction' => null, {{-- Livewire action hoặc route --}}
])

<flux:card class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 mb-4 sticky top-0 z-10 bg-white dark:bg-zinc-700">

    {{-- LEFT: heading, subheading, breadcrumb --}}
    <div class="flex-1">
        @if ($heading)
            <h1 class="text-2xl font-bold text-accent-text mb-1">{{ $heading }}</h1>
        @endif

        @if ($subheading)
            <p class="text-sm opacity-50 mb-3">{{ $subheading }}</p>
        @endif

        {{-- Breadcrumbs (Flux Pro) --}}
        @if (!empty($breadcrumb))
            <flux:breadcrumbs>
                @foreach ($breadcrumb as $item)
                    @if (!empty($item['url']))
                        <flux:breadcrumbs.item href="{{ $item['url'] }}">
                            {{ $item['label'] }}
                        </flux:breadcrumbs.item>
                    @else
                        <flux:breadcrumbs.item>
                            {{ $item['label'] }}
                        </flux:breadcrumbs.item>
                    @endif
                @endforeach
            </flux:breadcrumbs>
        @endif
    </div>

    {{-- RIGHT: Buttons --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
        @if ($buttonLabel)
            @if ($buttonAction)
                <button
                    wire:click="{{ $buttonAction }}"
                    class="inline-flex items-center justify-center gap-2 bg-accent text-white px-4 py-2 rounded-xl font-semibold shadow hover:shadow-md transition w-full sm:w-auto cursor-pointer">
                    <flux:icon.plus class="w-5 h-5" />
                    <span>{{ $buttonLabel }}</span>
                </button>
            @else
                <button
                    class="inline-flex items-center justify-center gap-2 bg-accent text-white px-4 py-2 rounded-xl font-semibold shadow hover:shadow-md transition w-full sm:w-auto cursor-pointer">
                    <flux:icon.plus class="w-5 h-5" />
                    <span>{{ $buttonLabel }}</span>
                </button>
            @endif
        @endif

        @if ($buttonLabelBack)
            @if ($buttonBackAction)
                <button
                    wire:click="{{ $buttonBackAction }}"
                    class="inline-flex items-center justify-center gap-2 bg-accent text-white px-4 py-2 rounded-xl font-semibold shadow hover:shadow-md transition w-full sm:w-auto cursor-pointer">
                    <flux:icon.arrow-uturn-left class="w-5 h-5" />
                    <span>{{ $buttonLabelBack }}</span>
                </button>
            @else
                <button
                    class="inline-flex items-center justify-center gap-2 bg-accent text-white px-4 py-2 rounded-xl font-semibold shadow hover:shadow-md transition w-full sm:w-auto cursor-pointer">
                    <flux:icon.arrow-uturn-left class="w-5 h-5" />
                    <span>{{ $buttonLabelBack }}</span>
                </button>
            @endif
        @endif
    </div>
</flux:card>

{{ $slot }}
