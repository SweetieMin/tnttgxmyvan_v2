<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Giao diện')" :subheading=" __('Cập nhật cài đặt giao diện cho tài khoản')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio class="cursor-pointer" value="light" icon="sun">{{ __('Sáng') }}</flux:radio>
            <flux:radio class="cursor-pointer" value="dark" icon="moon">{{ __('Tối') }}</flux:radio>
            <flux:radio class="cursor-pointer" value="system" icon="computer-desktop">{{ __('Hệ thống') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
