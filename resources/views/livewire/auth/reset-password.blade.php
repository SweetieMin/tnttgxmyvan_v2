<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Đặt lại mật khẩu')" :description="__('Hãy nhập mật khẩu mới bên dưới.')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="resetPassword" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Địa chỉ Email')"
            type="email"
            required
            autocomplete="email"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Mật khẩu mới')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Mật khẩu mới')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Xác nhận mật khẩu')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Xác nhận mật khẩ')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Đặt lại mật khẩu') }}
            </flux:button>
        </div>
    </form>
</div>
