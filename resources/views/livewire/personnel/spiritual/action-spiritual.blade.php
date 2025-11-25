<div>
    <x-app-action-modal name="action-spiritual" :dismissible="false"
        title="{{ $isEditSpiritualMode ? 'Cập nhật Linh hướng' : 'Tạo mới Linh hướng' }}"
        subheading="Quản lý thông tin Linh hướng" icon="squares-plus" class="md:max-w-[98vw]">
        {{-- Nội dung riêng của form --}}
        <form wire:submit.prevent='{{ $isEditSpiritualMode ? 'updateSpiritual' : 'createSpiritual' }}' class="space-y-6">


            <flux:separator />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" class="cursor-pointer" variant="primary">
                    {{ $isEditSpiritualMode ? 'Cập nhật' : 'Thêm mới' }}
                </flux:button>
            </div>
        </form>

    </x-app-action-modal>
</div>
