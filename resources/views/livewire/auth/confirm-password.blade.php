<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Xác nhận mật khẩu')" :description="__('Đây là khu vực bảo mật của ứng dụng. Vui lòng xác nhận mật khẩu trước khi tiếp tục.')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input name="password" :label="__('Mật khẩu')" type="password" required autocomplete="current-password"
                :placeholder="__('Password')" viewable />

            <div class="flex gap-3">
                <flux:button icon="arrow-uturn-left" class="flex-1" onclick="window.history.back();">{{ __('Quay lại') }}</flux:button>
                <flux:button variant="primary" type="submit" class="flex-[2]" data-test="confirm-password-button">
                    {{ __('Xác nhận') }}
                </flux:button>


            </div>

        </form>
    </div>
</x-layouts.auth>
