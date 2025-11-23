<div>

    <div class="mt-2">
        <form id="passkeyForm" wire:submit="validatePasskeyProperties" ">
            <div>

                <flux:input wire:model="name" :label="__('Tên ứng dụng')" type="text"
                    autocomplete="off" />

            </div>

            <div class='mt-2'>
                <flux:button variant="primary" type="submit" >{{ __('Thêm passkey') }}</flux:button>
            </div>

        </form>
    </div>

    <div class="mt-6">
        <ul class="space-y-4">
             @foreach ($passkeys as $passkey)
            <li
                class="flex justify-between items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700
                   bg-gray-50 dark:bg-gray-700/50 text-gray-800 dark:text-gray-100 shadow-sm
                   hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">

                <div>
                    {{ $passkey->name }}
                </div>

                <div class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('passkeys::passkeys.last_used') }}:
                    {{ $passkey->last_used_at?->diffForHumans() ?? __('passkeys::passkeys.not_used_yet') }}
                </div>

                <div>
                    <button wire:click="deletePasskey({{ $passkey->id }})"
                        class="inline-flex justify-center py-2 px-4 text-sm font-medium rounded-md
                                   text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-400 focus:outline-none
                                   transition">
                        {{ __('passkeys::passkeys.delete') }}
                    </button>
                </div>
            </li>
            @endforeach
            </ul>
    </div>


</div>

@include('passkeys::livewire.partials.createScript')
