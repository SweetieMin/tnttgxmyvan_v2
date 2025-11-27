<section class="w-full">
    @include('partials.site-settings-heading')




    <x-settings.site.layout :heading="__('Cấu hình chung')" :subheading="__('Cập nhật cài đặt chung cho hệ thống')">

        <flux:tab.group>

            <flux:tabs>

                <flux:tab wire:click="selectTab('general')" name="general" icon="cog-6-tooth"
                    :selected="$tab == 'general'">Chung</flux:tab>
                <flux:tab wire:click="selectTab('logo-favicon')" name="logo-favicon" icon="globe-alt"
                    :selected="$tab == 'logo-favicon'">Logo & Favicon</flux:tab>

            </flux:tabs>

            <flux:tab.panel name="general">
                <form wire:submit.prevent="updateGeneralSettings()" class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-8 items-start">

                        {{-- Cột trái: Cài đặt chung --}}
                        <div class="space-y-6">
                            <flux:separator text="Cài đặt chung" class="my-6" />
                            <flux:input wire:model="site_title" :label="__('Tiêu đề trang web')" type="text"
                                autofocus />
                            <flux:input wire:model="site_email" :label="__('Email')" type="email" />
                            <flux:input wire:model="site_phone" :label="__('Số điện thoại')" type="text" />
                            <flux:input wire:model="site_meta_keywords" :label="__('Từ khóa meta')" type="text" />

                        </div>

                        {{-- Separator dọc --}}
                        <flux:separator vertical class="hidden md:block" />

                        {{-- Cột phải: Liên kết --}}
                        <div class="space-y-6">
                            <flux:separator text="Liên kết" class="my-6" />
                            <flux:input wire:model.lazy="facebook_url" :label="__('URL Facebook')" type="text" />
                            <flux:input wire:model.lazy="instagram_url" :label="__('URL Instagram')" type="text" />
                            <flux:input wire:model.lazy="youtube_url" :label="__('URL YouTube')" type="text" />
                            <flux:input wire:model.lazy="tikTok_url" :label="__('URL TikTok')" type="text" />
                        </div>

                    </div>
                    <div class="my-6">
                        <flux:textarea wire:model="site_meta_description" :label="__('Mô tả meta')"
                            class="min-h-[120px]" />

                    </div>
                    <flux:separator class="my-6" />
                    {{-- Nút lưu --}}
                    <div class="mt-8 flex items-center gap-4">
                        <flux:button variant="primary" type="submit" class="cursor-pointer">
                            {{ __('Lưu') }}
                        </flux:button>
                    </div>
                </form>
            </flux:tab.panel>


            <flux:tab.panel name="logo-favicon">

                <div class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-8 items-start">

                        {{-- Cột trái: Cài đặt chung --}}
                        <div class="space-y-6">
                            <flux:separator text="Open Graph (OG)" class="my-6" />

                            <!-- Blade view: -->

                            <form wire:submit="saveLogo">
                                <flux:file-upload wire:model="logo" label="Tải tập tin lên" accept=".jpg, .png">
                                    <flux:file-upload.dropzone heading="Thả tập tin vào đây hoặc nhấp để duyệt"
                                        text="JPG, PNG dung lượng 10MB" with-progress inline />
                                </flux:file-upload>

                                <div class="mt-3 flex flex-col gap-2 ">
                                    @if ($logo)
                                        <div class="mb-2 mt-1 max-w-lg">
                                            <img wire:ignore id="preview_side_logo" src="{{ $logo->temporaryUrl() }}"
                                                alt="Site Logo" class="w-full h-auto max-h-[300px] ">
                                        </div>
                                    @endif
                                </div>

                                @if ($existLogo && !$logo)
                                    <div class="mb-2 ">
                                        <img wire:ignore id="preview_side_logo"
                                            src="/storage/{{ $existLogo ?? '/images/sites/LOGO_default.png' }}"
                                            alt="Site Logo" class="w-full h-auto max-h-[300px] ">
                                    </div>
                                @endif

                                <div class="mt-2 flex items-center gap-4">
                                    <flux:button variant="primary" type="submit" class="cursor-pointer">
                                        {{ $logo ? 'Lưu Open Graph (OG)' : 'Hãy chọn hình trước' }}
                                    </flux:button>
                                </div>

                            </form>


                        </div>

                        {{-- Separator dọc --}}
                        <flux:separator vertical class="hidden md:block" />

                        {{-- Cột phải: Liên kết --}}
                        <div class="space-y-6">
                            <flux:separator text="Favicon" class="my-6" />

                            <form wire:submit.prevent="saveFavicon">
                                <flux:file-upload wire:model="favicon" label="Tải tập tin lên" accept=".jpg, .png">
                                    <flux:file-upload.dropzone heading="Thả tập tin vào đây hoặc nhấp để duyệt"
                                        text="JPG, PNG dung lượng 10MB" with-progress inline />
                                </flux:file-upload>

                                <div class="mt-3 flex flex-col gap-2 ">
                                    @if ($favicon)
                                        <div class="mb-2 max-w-[300px]">
                                            <img wire:ignore id="preview_side_favicon"
                                                src="{{ $favicon->temporaryUrl() }}" alt="Site favicon"
                                                class="w-full h-auto max-h-[300px]">
                                        </div>
                                    @endif
                                </div>

                                @if ($existFavicon && !$favicon)
                                    <div class="mb-2 max-w-[300px]">
                                        <img wire:ignore id="preview_side_favicon"
                                            src="/storage/{{ $existFavicon ?? '/images/sites/FAVICON_default.png' }}"
                                            alt="Site favicon" class="w-full h-auto max-h-[300px]">
                                    </div>
                                @endif

                                <div class="mt-2 flex items-center gap-4">
                                    <flux:button variant="primary" type="submit" class="cursor-pointer">
                                        {{ $favicon ? 'Lưu Favicon' : 'Hãy chọn hình trước' }}
                                    </flux:button>
                                </div>

                            </form>


                        </div>

                    </div>


                    {{-- Nút lưu --}}

                </div>

            </flux:tab.panel>

        </flux:tab.group>


    </x-settings.site.layout>

</section>
