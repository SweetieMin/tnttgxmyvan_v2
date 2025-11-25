<section class="w-full">
    @include('partials.site-settings-heading')

    <x-settings.site.layout :heading="__('Cấu hình email')" :subheading="__('Cập nhật cài đặt email cho hệ thống')">

        <form wire:submit.prevent="updateEmailSettings()" class="my-6 w-full">
            <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-8 items-start">

                {{-- Cột trái: Cài đặt chung --}}
                <div class="space-y-6">
                    <flux:separator text="Thông tin người gửi (Sender Info)" class="my-6" />

                    <flux:input wire:model="from_address" :label="__('Email gửi (From Address)')" type="email" autofocus
                        placeholder="tntt.myvan@gmail.com" />

                    <flux:input wire:model="from_name" :label="__('Tên người gửi (From Name)')" type="text"
                        placeholder="Đoàn TNTT giáo xứ Mỹ Vân" />

                    <flux:input wire:model="username" :label="__('Tên đăng nhập SMTP (Username)')" type="text"
                        placeholder="tntt.myvan@gmail.com" />
                    <label class="block text-sm font-medium mb-1">
                        {{ __('Mật khẩu SMTP (Password)') }}
                    </label>
                    {{-- Password section --}}
                    <div class="space-y-2">

                        {{-- Nếu đã có mật khẩu và không muốn thay đổi --}}
                        @if ($isHavePassword && !$showPasswordInput)

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-green-600">
                                    Mật khẩu đã được lưu.
                                </span>

                                <flux:button size="sm" wire:click="togglePasswordInput" class="cursor-pointer">
                                    Thay đổi mật khẩu
                                </flux:button>
                            </div>
                        @else
                            <div class="flex gap-2 items-start">

                                {{-- Label + Input --}}
                                <div class="w-4/5">


                                    <flux:input wire:model="password" type="password" placeholder="Nhập mật khẩu mới"
                                        viewable />
                                </div>

                                {{-- Nếu đã có mật khẩu và đang mở input → hiện nút Huỷ --}}
                                @if ($isHavePassword)
                                    <div class="w-1/5 flex justify-end">

                                        <flux:button size="sm" variant="danger" wire:click="togglePasswordInput"
                                            class="cursor-pointer">
                                            Huỷ
                                        </flux:button>
                                    </div>
                                @endif

                            </div>

                        @endif

                    </div>



                </div>

                {{-- Separator dọc --}}
                <flux:separator vertical class="hidden md:block" />

                {{-- Cột phải: Cài đặt SMTP --}}
                <div class="space-y-6">
                    <flux:separator text="Cài đặt SMTP Server" class="my-6" />

                    <flux:select variant="listbox" wire:model="mailer" label="Giao thức gửi mail" placeholder="smtp">
                        <flux:select.option value="smtp">SMTP</flux:select.option>
                        <flux:select.option value="sendmail">Sendmail</flux:select.option>
                        <flux:select.option value="mailgun">Mailgun</flux:select.option>
                        <flux:select.option value="postmark">Postmark</flux:select.option>
                        <flux:select.option value="log">Ghi vào Log (debug)</flux:select.option>
                        <flux:select.option value="array">Array (không gửi mail)</flux:select.option>
                    </flux:select>

                    <flux:input wire:model="host" :label="__('Máy chủ SMTP (Host)')" type="text"
                        placeholder="smtp.gmail.com" />


                    <flux:select wire:model.lazy="encryption" variant="listbox"
                        label="Mã hóa kết nối (Encryption: tls/ssl)"
                        placeholder="__('Mã hóa kết nối (Encryption: tls/ssl)')">
                        <flux:select.option>tls</flux:select.option>
                        <flux:select.option>ssl</flux:select.option>
                    </flux:select>

                    <flux:input wire:model="port" :label="__('Cổng SMTP (Port)')" type="number" placeholder="587"
                        disabled />


                </div>

            </div>

            <flux:separator class="my-6" />

            {{-- Nút lưu --}}
            <div class="mt-8 flex items-center gap-4">
                <flux:button variant="primary" type="submit" class="cursor-pointer">
                    {{ __('Lưu cấu hình Email') }}
                </flux:button>
            </div>
        </form>


    </x-settings.site.layout>

</section>
