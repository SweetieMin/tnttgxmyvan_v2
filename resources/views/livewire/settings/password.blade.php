<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Cập nhật mật khẩu')" :subheading="__('Đảm bảo tài khoản của bạn sử dụng mật khẩu dài và ngẫu nhiên để đảm bảo an toàn')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Mật khẩu hiện tại')"
                type="password"
                autocomplete="current-password"
                viewable
            />
            <flux:input
                wire:model="password"
                :label="__('Mật khẩu mới')"
                type="password"
                autocomplete="new-password"
                viewable
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('Xác nhận mật khẩu')"
                type="password"
                autocomplete="new-password"
                viewable
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full cursor-pointer">{{ __('Lưu') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Đã lưu.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
