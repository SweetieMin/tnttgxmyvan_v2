<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="h-screen bg-white dark:bg-zinc-800 overflow-hidden" 
    x-data="{ 
        handleToast(event) {
            const { heading, text, variant } = event.detail;
            console.log('Toast xuất hiện:', { heading, text, variant });
            // Thêm logic của bạn ở đây
            // Ví dụ: cập nhật UI, gửi analytics, v.v.
        }
    }"
    x-on:toast-show.document="handleToast($event)">
    <div class="flex h-full">
        {{-- Sidebar bên trái --}}
        <flux:sidebar collapsible
            class="w-[300px] border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-700 h-full flex flex-col">
            <flux:sidebar.header>
                <flux:sidebar.brand logo="/storage/images/sites/logo.png" name="TNTT Giáo xứ Mỹ Vân"
                    class="text-pink-500" />
                <flux:sidebar.collapse tooltip="Nút sidebar" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="flex-1 overflow-y-auto custom-scrollbar">

                <flux:separator class="my-2 flex-shrink-0 " />

                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>Trang chủ</flux:sidebar.item>

                <flux:separator class="my-2 flex-shrink-0 " />

                <flux:sidebar.item icon="squares-plus" :href="route('admin.management.academic-year')"
                    :current="request()->routeIs('admin.management.academic-year')" wire:navigate>Niên khoá
                </flux:sidebar.item>

                <flux:sidebar.item icon="clipboard-document-list" :href="route('admin.management.regulations')"
                    :current="request()->routeIs('admin.management.regulations*')" wire:navigate>Nội quy
                </flux:sidebar.item>

                <flux:sidebar.item icon="bookmark-square" :href="route('admin.management.programs')"
                    :current="request()->routeIs('admin.management.programs')" wire:navigate>Chương trình học
                </flux:sidebar.item>

                <flux:sidebar.item icon="academic-cap" :href="route('admin.management.courses')"
                    :current="request()->routeIs('admin.management.courses')" wire:navigate>Lớp Giáo Lý
                </flux:sidebar.item>

                <flux:sidebar.item icon="ferris-wheel" :href="route('admin.management.sectors')"
                    :current="request()->routeIs('admin.management.sectors')" wire:navigate>Ngành Sinh Hoạt
                </flux:sidebar.item>

                <flux:separator class="my-2 flex-shrink-0 " />

                <flux:sidebar.item icon="academic-cap" :href="route('admin.access.roles')"
                    :current="request()->routeIs('admin.access.roles*')" wire:navigate>Chức vụ
                </flux:sidebar.item>

                <flux:separator class="my-2 flex-shrink-0 " />

                <flux:sidebar.item icon="clipboard-document-check" :href="route('admin.finance.transaction-items')"
                    :current="request()->routeIs('admin.finance.transaction-items')" wire:navigate>Hạng mục thu chi
                </flux:sidebar.item>

                <flux:sidebar.item icon="banknotes" :href="route('admin.finance.transactions')"
                    :current="request()->routeIs('admin.finance.transactions')" wire:navigate>Tiền quỹ
                </flux:sidebar.item>

                <flux:separator class="my-2 flex-shrink-0 " />

                <flux:sidebar.item icon="church" :href="route('admin.personnel.spirituals')"
                    :current="request()->routeIs('admin.personnel.spirituals')" wire:navigate>Linh hướng
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-plus" :href="route('admin.personnel.catechists')"
                    :current="request()->routeIs('admin.personnel.catechists')" wire:navigate>Giáo Lý Viên
                </flux:sidebar.item>

                <flux:sidebar.item icon="user-star" :href="route('admin.personnel.scouters')"
                    :current="request()->routeIs('admin.personnel.scouters')" wire:navigate>Huynh-Dự-Đội Trưởng
                </flux:sidebar.item>

                <flux:sidebar.item icon="user-group" :href="route('admin.personnel.children')"
                    :current="request()->routeIs('admin.personnel.children')" wire:navigate>Thiếu Nhi
                </flux:sidebar.item>

                <flux:sidebar.item icon="user-round-check" :href="route('admin.personnel.children-inactive')"
                    :current="request()->routeIs('admin.personnel.children-inactive')" wire:navigate>Đã Tốt Nghiệp
                </flux:sidebar.item>


                <flux:sidebar.spacer />
            </flux:sidebar.nav>


            <div class="flex-shrink-0  border-t border-zinc-200 dark:border-zinc-700 max-lg:hidden">
                <flux:dropdown position="top" align="start" class="max-lg:hidden">
                    <flux:sidebar.profile avatar="{{ auth()->user()->details?->picture }}"
                        name="{{ auth()->user()->full_name }}" />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <flux:avatar src="{{ auth()->user()->details?->picture }}" />
                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span
                                            class="truncate font-semibold">{{ auth()->user()->christian_full_name }}</span>
                                        <span class="truncate text-xs opacity-60">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('admin.settings.profile')" icon="cog" wire:navigate>
                                Cài đặt</flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full">
                                Đăng xuất
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </flux:sidebar>

        <div class="flex flex-col flex-1 h-full bg-white dark:bg-zinc-800 overflow-hidden">
            {{-- Mobile header --}}
            <flux:header class="lg:hidden">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
                <flux:spacer />
                <flux:dropdown position="top" align="end">
                    <flux:profile avatar="{{ auth()->user()->details?->picture }}" icon-trailing="chevron-down" />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <flux:avatar src="{{ auth()->user()->details?->picture }}" />
                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span
                                            class="truncate font-semibold">{{ auth()->user()->christian_full_name }}</span>
                                        <span class="truncate text-xs opacity-60">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('admin.settings.profile')" icon="cog" wire:navigate>
                                Cài đặt</flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full ">
                                Đăng xuất
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </flux:header>

            {{-- Nội dung --}}
            <main class="flex-grow overflow-auto">


                {{ $slot }}


            </main>

            @persist('toast')
                <flux:toast.group position="top end">
                    <flux:toast />
                </flux:toast.group>
            @endpersist

            <x-alert-toastr />
            {{-- Footer --}}
            @include('components.layouts.app.footer')
            {{-- @include('components.layouts.app.dial') --}}
        </div>
    </div>
    @stack('scripts')
    @fluxScripts
    @livewireCalendarScripts

</body>



</html>
