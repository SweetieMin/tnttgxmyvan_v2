<section class="w-full">
    @include('partials.site-settings-heading')

    <x-settings.site.layout :heading="__('Cấu hình bẩo trì')" :subheading="__(
        'Cập nhật cài đặt bẩo trì để hệ thống cập nhật phiên bản mới. Và cài đặt SECRET  KEY để cấp quyền truy cập test.',
    )">

        <form wire:submit.prevent="updateMaintenanceSettings()" class="my-6 w-full">
            <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-8 items-start">

                {{-- Cột trái: App Credentials --}}
                <div class="space-y-6">
                    <flux:separator text="Cấu hình bảo trì" class="my-6" />

                    <flux:field variant="inline">

                        <flux:switch wire:model.live="is_maintenance" label="Bật bảo trì" />

                        <flux:error name="is_maintenance" />
                    </flux:field>

                    @if ($is_maintenance)
                        <flux:input icon="key" readonly :copyable="$is_maintenance" wire:model="secret_key"
                            label="SECRET KEY" />

                        <flux:textarea wire:model="message" label="Thông điệp bảo trì" class="min-h-[120px]" />
                    @endif


                </div>

                {{-- Separator dọc --}}
                <flux:separator vertical class="hidden md:block" />

                {{-- Cột phải: Connection Settings --}}
                <div class="space-y-6">
                    @if ($is_maintenance)
                        <flux:separator text="Cài đặt thông báo bảo trì" class="my-6" />

                        <flux:time-picker wire:model="start_at" label="Thời gian bắt đầu" type="input" />
                        <flux:time-picker wire:model="end_at" label="Thời gian kết thúc" type="input" />
                    @endif
                </div>

            </div>

            <flux:separator class="my-6" />

            {{-- Nút lưu --}}
            <div class="mt-8 flex items-center gap-4">
                <flux:button variant="primary" type="submit" class="cursor-pointer">
                    {{ __('Lưu cấu hình bảo trì') }}
                </flux:button>
            </div>
        </form>

    </x-settings.site.layout>


    <flux:modal name="maintenance-confirm" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Bật chế độ bảo trì?</flux:heading>
                <flux:text class="mt-2">
                    Hệ thống sẽ chuyển sang chế độ bảo trì và người dùng không thể truy cập.<br>
                    Bạn có chắc chắn muốn bật không?
                </flux:text>
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Huỷ</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" wire:click="enableMaintenanceConfirm">
                    Xác nhận
                </flux:button>
            </div>
        </div>
    </flux:modal>


</section>
