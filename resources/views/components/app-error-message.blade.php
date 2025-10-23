@props(['message' => 'Lỗi'])

<div class="flex items-center gap-1">
    <flux:icon variant="solid" name="exclamation-triangle" class="w-4 h-4 text-red-500" />
    <flux:text class="text-red-500 text-sm font-medium">
        {{ $message }}
    </flux:text>
</div>
