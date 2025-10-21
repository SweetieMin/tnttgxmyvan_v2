<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="h-screen bg-white dark:bg-zinc-800 overflow-hidden">
    <div class="flex h-full">
        {{-- Sidebar bên trái --}}
        <flux:sidebar sticky stashable
            class="w-[320px] border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 h-full flex flex-col">

            {{-- Header cố định --}}
            <div class="flex-shrink-0 p-4 border-b border-zinc-200 dark:border-zinc-700">

                <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse"
                    wire:navigate>
                    <x-app-logo />
                </a>
            </div>

            {{-- Menu items có thể scroll --}}
            <div class="flex-1 overflow-y-auto">
                <flux:navlist variant="outline">
                    <flux:navlist.group :heading="('Tổng quan')" class="grid">
                        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                            wire:navigate>Trang chủ</flux:navlist.item>
                    </flux:navlist.group>
                </flux:navlist>
        
                <flux:navlist variant="outline">
                    <flux:navlist.group :heading="__('Quản lý')" class="grid">
                        <flux:navlist.item icon="squares-plus" :href="route('admin.management.academic-year')" :current="request()->routeIs('admin.management.academic-year')"
                            wire:navigate>Niên khoá</flux:navlist.item>
                    </flux:navlist.group>
                </flux:navlist>

                
            </div>

            {{-- Footer cố định --}}
            <div class="flex-shrink-0  border-t border-zinc-200 dark:border-zinc-700">
                <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                    <flux:profile :name="auth()->user()->name" avatar="{{ auth()->user()->detail?->avatar }}"
                        icon:trailing="chevrons-up-down" />
                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <flux:avatar src="{{ auth()->user()->detail?->avatar }}" />
                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
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
                <flux:dropdown class="cursor-pointer" position="top" align="end">
                    <flux:profile avatar="{{ auth()->user()->detail?->avatar }}" icon-trailing="chevron-down" />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <flux:avatar src="{{ auth()->user()->detail?->avatar }}" />
                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
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
            <main class="flex-grow p-4 overflow-auto">
                {{ $slot }}
            </main>
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
