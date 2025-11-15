<section class="w-full">
    @include('partials.site-settings-heading')

    <x-settings.site.layout :heading="__('Cấu hình Pusher')" :subheading="__('Cập nhật cài đặt Pusher để hệ thống có thể hoạt động Realtime.')">

        <form wire:submit.prevent="updatePusherSettings()" class="my-6 w-full">
            <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-8 items-start">

                {{-- Cột trái: App Credentials --}}
                <div class="space-y-6">
                    <flux:separator text="Thông tin ứng dụng (App Credentials)" class="my-6" />

                    <flux:input wire:model="app_id" :label="__('App ID')" type="text" placeholder="xxxxxx" viewable/>

                    <flux:input wire:model="key" :label="__('Key')" type="password" placeholder="xxxxxxxxxxxxxxxx" viewable/>

                    <flux:input wire:model="secret" :label="__('Secret')" type="password"
                        placeholder="****************" viewable />
                </div>

                {{-- Separator dọc --}}
                <flux:separator vertical class="hidden md:block" />

                {{-- Cột phải: Connection Settings --}}
                <div class="space-y-6">
                    <flux:separator text="Cài đặt kết nối (Connection Settings)" class="my-6" />

                    <flux:select variant="listbox" wire:model="cluster" label="Cluster" placeholder="ap1">
                        <flux:select.option value="ap1">ap1 (Asia Pacific)</flux:select.option>
                        <flux:select.option value="us2">us2 (United States)</flux:select.option>
                        <flux:select.option value="eu">eu (Europe)</flux:select.option>
                    </flux:select>

                    <flux:input wire:model="port" :label="__('Cổng kết nối (Port)')" type="number"
                        placeholder="6001" />

                    <flux:input wire:model="scheme" :label="__('Giao thức (Scheme)')" type="text"
                        placeholder="https" />
                </div>

            </div>

            <flux:separator class="my-6" />

            {{-- Nút lưu --}}
            <div class="mt-8 flex items-center gap-4">
                <flux:button variant="primary" type="submit" class="cursor-pointer">
                    {{ __('Lưu cấu hình Pusher') }}
                </flux:button>
            </div>
        </form>

    </x-settings.site.layout>
</section>
