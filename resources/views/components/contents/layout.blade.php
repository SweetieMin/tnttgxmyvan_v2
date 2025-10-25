@props([
    'heading' => null,          {{-- Tiêu đề chính --}}
    'subheading' => null,       {{-- Mô tả ngắn --}}
    'breadcrumb' => [],         {{-- Mảng breadcrumb --}}
    'buttonLabel' => null,      {{-- Nút thêm --}}
    'buttonAction' => null,     {{-- Livewire action hoặc route --}}
    'buttonLabelBack' => null,      {{-- Nút thêm --}}
    'buttonBackAction' => null,     {{-- Livewire action hoặc route --}}
])

<div
    class="bg-accent-background text-accent-text rounded-2xl shadow-sm p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 mb-4 ">

    {{-- LEFT: heading, subheading, breadcrumb <flux:heading size="xl" level="1">{{ __('Settings') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('Manage your profile and account settings') }}</flux:subheading> --}}
    <div class="flex-1">
        @if ($heading)
            <h1 class="text-2xl font-bold text-accent-text mb-1">{{ $heading }}</h1>
        @endif

        @if ($subheading)
            <p class="text-sm opacity-50 mb-3">{{ $subheading }}</p>
        @endif

        {{-- Breadcrumb --}}
        @if (!empty($breadcrumb))
            <div class="flex items-center text-sm space-x-1">
                @foreach ($breadcrumb as $item)
                    @if (!$loop->last)
                        <a href="{{ $item['url'] ?? '#' }}" class="hover:underline opacity-50">{{ $item['label'] }}</a>
                        <span>›</span>
                    @else
                        <span class="opacity-80">{{ $item['label'] }}</span>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- RIGHT: Counter + Button --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">

        @if ($buttonLabel)
            <button
                @if ($buttonAction) wire:click="{{ $buttonAction }}" @endif
                class="inline-flex items-center justify-center gap-2 bg-accent text-white px-4 py-2 rounded-xl font-semibold shadow hover:shadow-md transition w-full sm:w-auto cursor-pointer">
                <flux:icon.plus class="w-5 h-5" />
                <span>{{ $buttonLabel }}</span>
            </button>
        @endif

        @if ($buttonLabelBack)
            <button
                @if ($buttonBackAction) wire:click="{{ $buttonBackAction }}" @endif
                class="inline-flex items-center justify-center gap-2 bg-accent text-white px-4 py-2 rounded-xl font-semibold shadow hover:shadow-md transition w-full sm:w-auto cursor-pointer">
                <flux:icon.arrow-uturn-left class="w-5 h-5" />
                <span>{{ $buttonLabelBack }}</span>
            </button>
        @endif
    </div>
</div>

{{ $slot }}
