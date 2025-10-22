 <div class="flex flex-col gap-6">
    <x-auth-header :title="__('Quên mật khẩu')" :description="__('Nhập email của bạn để nhận liên kết đặt lại mật khẩu')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Địa chỉ Email')"
            type="email"
            autofocus
            placeholder="email@example.com"
        />

        <flux:button variant="primary" type="submit" class="w-full">{{ __('Gửi liên kết') }}</flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('Hoặc, quay lại') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Đăng nhập') }}</flux:link>
    </div>
</div>
