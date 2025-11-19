<div>
    <x-contents.layout heading="Người linh hướng" subheading="Quản lý danh sách và thông tin Người linh hướng"
        icon="squares-plus" :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Người linh hướng']]" buttonLabelBack="Quay lại" buttonBackAction="backSpiritual">


        <div class="flex items-start max-md:flex-col">

            <div class="me-10 w-full pb-4 md:w-3xl">
                <flux:card class="flex flex-col items-center justify-center gap-6 p-6 ">

                    {{-- AVATAR UPLOAD --}}
                    <div class="w-full flex justify-center">

                        <div
                            class="relative flex items-center justify-center size-50 rounded-full
                            border border-zinc-300/40 dark:border-white/10
                            bg-zinc-200/30 hover:bg-zinc-300/40 dark:bg-white/10 hover:dark:bg-white/15
                            transition mx-auto">

                            <img src="{{ $picture }}" class="size-full rounded-full object-cover" />

                        </div>

                    </div>

                    <flux:heading size="xl">{{ $christian_name ?? '...' }}</flux:heading>
                    <flux:heading size="xl">{{ $full_name ?? '...' }}</flux:heading>

                    <flux:separator text="QR" />

                    {{-- QR CODE --}}
                    <div class="flex justify-center">
                        <div
                            class="p-6 rounded-3xl bg-zinc-400/20 dark:bg-zinc-800
                             shadow-[8px_8px_20px_#c9c9c9,-8px_-8px_20px_#ffffff]
                             dark:shadow-[8px_8px_20px_#0b0b0b,-8px_-8px_20px_#3a3a3a]">

                            {!! $tokenQrCode !!}

                        </div>
                    </div>

                </flux:card>
            </div>

            <flux:separator class="md:hidden" />

            <div id="section" class="self-stretch max-md:pt-6 w-full">
                <flux:card class="space-y-6 ">
                    <flux:heading>Thông tin Người linh hướng</flux:heading>
                    <flux:subheading>Quản lý thông tin và hồ sơ người linh hướng</flux:subheading>
                    <flux:separator class="my-2" />
                    <div class="mt-5 w-full  overflow-y-auto px-2"> {{-- min-h-[57vh] max-h-[57vh] --}}

                        <flux:tab.group>
                            <flux:tabs scrollable scrollable:fade>
                                <flux:tab wire:click="selectTab('profile')" name="profile" icon="user"
                                    :selected="$tab == 'profile'">Thông tin cá nhân</flux:tab>
                                @if ($isShowTabParent)
                                    <flux:tab wire:click="selectTab('parent')" name="parent" icon="newspaper"
                                        :selected="$tab == 'parent'">Thông tin phụ huynh</flux:tab>
                                @endif
                               @if ($isShowTabCatechism)
                                    <flux:tab wire:click="selectTab('catechism')" name="catechism" icon="church"
                                        :selected="$tab == 'catechism'">Thông tin Công giáo</flux:tab>
                                @endif 
                                @if ($isShowTabAchievement)
                                    <flux:tab wire:click="selectTab('achievement')" name="achievement" icon="sparkles"
                                        disabled>Thành tích & Cấp bậc</flux:tab>
                                @endif

                            </flux:tabs>

                            <flux:tab.panel name="profile">

                                <x-personnel.profile :isEditSpiritualMode="$isEditSpiritualMode" :roles="$roles" />

                            </flux:tab.panel>

                            {{-- KẾT THÚC THÔNG TIN CÁ NHÂN --}}
                            @if ($isShowTabParent)
                                <flux:tab.panel name="parent">

                                    <livewire:personnel.common.update-parent :userID="$spiritualID" />

                                </flux:tab.panel>
                            @endIf
                            @if ($isShowTabCatechism)
                                <flux:tab.panel name="catechism">

                                     <livewire:personnel.common.update-catechism :userID="$spiritualID" /> 

                                </flux:tab.panel>
                            @endIf
                            <flux:tab.panel name="achievement">
                                TAB HUY HIỆU / CẤP BẬC
                            </flux:tab.panel>
                        </flux:tab.group>



                    </div>
                </flux:card>
            </div>
        </div>



    </x-contents.layout>

</div>


@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            scrollToHash();
        });

        document.addEventListener("livewire:navigated", () => {
            scrollToHash();
        });

        function scrollToHash() {
            if (!location.hash) return;
            const el = document.querySelector(location.hash);
            if (el) {
                el.scrollIntoView();
            }
        }
    </script>
@endpush
