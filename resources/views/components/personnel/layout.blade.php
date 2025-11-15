<div class="flex items-start max-md:flex-col">

    <div class="me-10 w-full pb-4 md:w-sm">
        <flux:card class="space-y-6">
            <flux:navlist>
                <flux:navlist.item :href="route('admin.settings.general')"
                    :current="request()->routeIs('admin.settings.general')" wire:navigate>Thông tin cá nhân
                </flux:navlist.item>
                <flux:navlist.item :href="route('admin.settings.email')"
                    :current="request()->routeIs('admin.settings.email')" wire:navigate>Thông tin phụ huynh
                </flux:navlist.item>
                <flux:navlist.item :href="route('admin.settings.pusher')"
                    :current="request()->routeIs('admin.settings.pusher')" wire:navigate>Thông tin công giáo
                </flux:navlist.item>
                <flux:navlist.item :href="route('admin.settings.pusher')"
                    :current="request()->routeIs('admin.settings.pusher')" wire:navigate>Thông tin HDĐ Trưởng
                </flux:navlist.item>
            </flux:navlist>
        </flux:card>
    </div>

    <flux:separator class="md:hidden" />

    <div class="self-stretch max-md:pt-6 w-full">
        <flux:card class="space-y-6 ">
            <flux:heading>{{ $heading ?? '' }}</flux:heading>
            <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>
            <flux:separator class="my-2" />
            <div class="mt-5 w-full max-h-[62vh] overflow-y-auto px-2">
                {{ $slot }}
            </div>
        </flux:card>
    </div>  
</div>
