<section class="w-full">
    @include('partials.site-settings-heading')

    <x-settings.site.layout :heading="__('Cấu hình chung')" :subheading="__('Cập nhật cài đặt chung cho hệ thống')">


        <form wire:submit.prevent="updateGeneralSettings()" class="my-6 w-full">
            <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-8 items-start">

                {{-- Cột trái: Cài đặt chung --}}
                <div class="space-y-6">
                    <flux:separator text="Cài đặt chung" class="my-6" />
                    <flux:input wire:model="site_title" :label="__('Tiêu đề trang web')" type="text" autofocus />
                    <flux:input wire:model="site_email" :label="__('Email')" type="email" />
                    <flux:input wire:model="site_phone" :label="__('Số điện thoại')" type="text" />
                    <flux:input wire:model="site_meta_keywords" :label="__('Từ khóa meta')" type="text" />
                    <flux:textarea wire:model="site_meta_description" :label="__('Mô tả meta')" class="min-h-[120px]" />
                </div>

                {{-- Separator dọc --}}
                <flux:separator vertical class="hidden md:block" />

                {{-- Cột phải: Liên kết --}}
                <div class="space-y-6">
                    <flux:separator text="Liên kết" class="my-6" />
                    <flux:input wire:model.lazy="facebook_url" :label="__('URL Facebook')" type="text" />
                    <flux:input wire:model.lazy="instagram_url" :label="__('URL Instagram')" type="text" />
                    <flux:input wire:model.lazy="youtube_url" :label="__('URL YouTube')" type="text" />
                    <flux:input wire:model.lazy="tikTok_url" :label="__('URL TikTok')" type="text" />
                </div>

            </div>
            <flux:separator class="my-6" />
            {{-- Nút lưu --}}
            <div class="mt-8 flex items-center gap-4">
                <flux:button variant="primary" type="submit" class="cursor-pointer">
                    {{ __('Lưu') }}
                </flux:button>
            </div>
        </form>

    </x-settings.site.layout>

</section>
