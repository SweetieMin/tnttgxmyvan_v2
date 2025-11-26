<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Giao diện')" :subheading="__('Cập nhật cài đặt giao diện cho tài khoản')">
        <flux:separator text="Cài đặt giao diện" class="my-6" />
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio class="cursor-pointer" value="light" icon="sun">{{ __('Sáng') }}</flux:radio>
            <flux:radio class="cursor-pointer" value="dark" icon="moon">{{ __('Tối') }}</flux:radio>
            <flux:radio class="cursor-pointer" value="system" icon="computer-desktop">{{ __('Hệ thống') }}</flux:radio>
        </flux:radio.group>

        <flux:separator text="Thông báo" class="my-6" />

        <form wire:submit.prevent='saveSettingAppearance()'>

            <flux:card class="p-4 space-y-4">

                {{-- HÀNG 1: Icon + Label + Switch --}}
                <div class="flex items-center justify-between w-full">

                    {{-- Left: Icon + Label --}}
                    <div class="flex items-center gap-3">

                        {{-- Nút Preview âm --}}
                        @if ($notification_sound)
                            <flux:tooltip content="Nghe thử">
                                <flux:dropdown>
                                    <button type="button" class="p-2 rounded-md hover:bg-accent-background">
                                        <flux:icon.speaker-wave class="w-5 h-5 text-accent" />
                                    </button>

                                    <flux:menu>
                                        <flux:menu.item icon="check"
                                            @click="playTestSound('/storage/sounds/success.mp3', userSettings)">
                                            Thành công
                                        </flux:menu.item>

                                        <flux:menu.item icon="information-circle"
                                            @click="playTestSound('/storage/sounds/warning.mp3', userSettings)">
                                            Thông tin
                                        </flux:menu.item>

                                        <flux:menu.item icon="x-mark" variant="danger"
                                            @click="playTestSound('/storage/sounds/error.mp3', userSettings)">
                                            Thất bại
                                        </flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:tooltip>
                        @else
                            <flux:icon.speaker-x-mark class="w-6 h-6 text-red-500" />
                        @endif

                        {{-- Label --}}
                        <div>
                            <p class="font-medium">Âm thông báo</p>
                            <p class="text-sm text-muted-foreground">Bật âm khi có thông báo mới.</p>
                        </div>
                    </div>

                    {{-- Switch --}}
                    <flux:switch wire:model.live="notification_sound" />
                </div>

                {{-- HÀNG 2: Slider âm lượng --}}
                @if ($notification_sound)
                    <flux:field>
                        <flux:label>
                            Âm lượng

                            <x-slot name="trailing">
                                <span wire:text="notification_volume" class="tabular-nums"></span>
                            </x-slot>
                        </flux:label>

                        <flux:slider wire:model="notification_volume" track:class="h-5" thumb:class="size-5"
                            min="0" max="100" step="10" big-step="10" />
                    </flux:field>
                @endif

            </flux:card>

            <flux:separator class="my-6" />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full cursor-pointer">{{ __('Lưu') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="appearance-updated">
                    {{ __('Đã lưu.') }}
                </x-action-message>
            </div>

        </form>


    </x-settings.layout>

</section>


@push('scripts')
    <script>
        window.addEventListener('updateVolume', event => {
            if (!window.userSettings) {
                window.userSettings = {};
            }

            window.userSettings.notification_volume = event.detail.value;
        });
    </script>
@endpush
