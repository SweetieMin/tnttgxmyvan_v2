<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="h-screen bg-white dark:bg-zinc-800 overflow-hidden">
    <div class="flex h-full">
        {{-- Sidebar bên trái --}}
        <flux:sidebar collapsible
            class="w-[300px] border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 h-full flex flex-col">
            <flux:sidebar.header>
                <flux:sidebar.brand logo="/storage/images/sites/logo.png" name="TNTT Giáo xứ Mỹ Vân"
                    class="text-pink-500" />
                <flux:sidebar.collapse />
            </flux:sidebar.header>

            <flux:sidebar.nav>

                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>Trang chủ</flux:sidebar.item>
                    <flux:separator class="my-2"/>

                <flux:sidebar.item icon="squares-plus" :href="route('admin.management.academic-year')"
                    :current="request()->routeIs('admin.management.academic-year')" wire:navigate>Niên khoá
                </flux:sidebar.item>


            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                <flux:sidebar.profile avatar="{{ auth()->user()->details?->picture }}"
                    name="{{ auth()->user()->full_name }}" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar src="{{ auth()->user()->details?->picture }}" />
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->christian_full_name }}</span>
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
        </flux:sidebar>

        <div class="flex flex-col flex-1 h-full bg-white dark:bg-zinc-800 overflow-hidden">
            {{-- Mobile header --}}
            <flux:header class="lg:hidden">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
                <flux:spacer />
                <flux:dropdown  position="top" align="end">
                    <flux:profile avatar="{{ auth()->user()->details?->picture }}" icon-trailing="chevron-down" />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <flux:avatar src="{{ auth()->user()->details?->picture }}" />
                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->christian_full_name }}</span>
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
