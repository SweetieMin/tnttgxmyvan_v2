<section class="max-w-3xl">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Passkey')" :subheading="__('Quản lý Passkey đăng nhập của bạn')">

    @if (!auth()->user()?->email)
        {{-- ❌ Không có email --}}
        <flux:callout icon="exclamation-circle" color="orange">
            <flux:callout.text>
                Vui lòng cập nhật địa chỉ email trước khi sử dụng tính năng Passkey. 
                <flux:callout.link :href="route('admin.settings.profile')" wire:navigate>
                    Cài đặt email
                </flux:callout.link>
            </flux:callout.text>
        </flux:callout>

    @elseif (!auth()->user()?->hasVerifiedEmail())
        {{-- ⚠️ Có email nhưng chưa xác minh --}}
        <flux:callout icon="question-mark-circle" color="yellow">
            <flux:callout.text>
                Địa chỉ email của bạn chưa được xác minh. 
                <flux:callout.link :href="route('verification.notice')" wire:navigate>
                    Xác minh ngay
                </flux:callout.link>
            </flux:callout.text>
        </flux:callout>

    @else
        {{-- ✅ Có email và đã xác minh --}}
        <livewire:passkeys />
    @endif

    </x-settings.layout>
</section>
