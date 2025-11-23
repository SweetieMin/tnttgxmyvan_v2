<div>
    @include('passkeys::components.partials.authenticateScript')

    <form id="passkey-login-form" method="POST" action="{{ route('passkeys.login') }}">
        @csrf
    </form>

    @if ($message = session()->get('authenticatePasskey::message'))
        <flux:callout variant="warning" icon="exclamation-circle" heading="{{ $message }}" />
    @endif

    @if ($slot->isEmpty())

        <flux:button icon="key" class='w-full mt-1' @click.prevent="authenticateWithPasskey()">Đăng nhập bằng Passkey</flux:button>
    @else
        {{ $slot }}
    @endif

</div>
