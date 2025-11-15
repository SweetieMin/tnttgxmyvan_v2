<flux:modal name="telegram-otp" class="md:w-96">
    <div class="space-y-6">
        <div class="flex flex-col items-center space-y-4">
            <div
                class="p-0.5 w-auto rounded-full border border-stone-100 dark:border-stone-600 bg-white dark:bg-stone-800 shadow-sm">
                <div
                    class="p-2.5 rounded-full border border-stone-200 dark:border-stone-600 overflow-hidden bg-stone-100 dark:bg-stone-200 relative">
                    <div
                        class="flex items-stretch absolute inset-0 w-full h-full divide-x [&>div]:flex-1 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                        @for ($i = 1; $i <= 5; $i++)
                            <div></div>
                        @endfor
                    </div>

                    <div
                        class="flex flex-col items-stretch absolute w-full h-full divide-y [&>div]:flex-1 inset-0 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                        @for ($i = 1; $i <= 5; $i++)
                            <div></div>
                        @endfor
                    </div>

                    <flux:icon.qr-code class="relative z-20 dark:text-accent-foreground" />
                </div>
            </div>

            <div class="space-y-2 text-center">
                <flux:heading size="lg">Xác minh mã xác thực</flux:heading>
                <flux:text>Nhập mã OTP đã được gửi đến số điện thoại trên Telegram của bạn.</flux:text>
            </div>
        </div>

        <div class="space-y-6">
            <div class="flex flex-col items-center space-y-3">
                <x-input-otp :digits="6" name="code" wire:model="code" autocomplete="one-time-code" />
                @error('code')
                    <flux:text color="red">
                        {{ $message }}
                    </flux:text>
                @enderror
            </div>

            <div class="flex items-center space-x-3">

                <flux:modal.close>
                    <flux:button variant="outline" class="flex-1 cursor-pointer">
                        {{ __('Quay lại') }}
                    </flux:button>


                </flux:modal.close>

                <flux:button variant="primary" class="flex-1 cursor-pointer">
                    {{ __('Lưu') }}
                </flux:button>
            </div>
        </div>
    </div>
</flux:modal>