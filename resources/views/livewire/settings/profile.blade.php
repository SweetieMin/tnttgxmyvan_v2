<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Hồ sơ')" :subheading="__('Cập nhật hồ sơ của bạn')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Họ và tên')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Dịa chỉ Email')" type="email" required autocomplete="email" />

                @if (!auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Địa chỉ Email chưa được xác minh.') }}

                            <flux:link class="text-sm cursor-pointer inline-flex items-center gap-2"
                                wire:click.prevent="resendVerificationNotification">
                                {{ __('Nhấp vào đây để gửi lại email xác minh.') }}

                                <!-- ICON LOADING -->
                                <svg wire:loading wire:target="resendVerificationNotification"
                                    class="w-4 h-4 animate-spin text-primary" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </flux:link>

                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email của bạn.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button wire:loading.attr="disabled" wire:target="resendVerificationNotification" variant="primary"
                        type="submit" class="w-full cursor-pointer">{{ __('Lưu') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Đã lưu.') }}
                </x-action-message>
            </div>
        </form>

    </x-settings.layout>
</section>
