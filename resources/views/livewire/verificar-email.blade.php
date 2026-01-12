<div class="space-y-4">
    <x-alert>
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn’t receive the email, we will gladly send you another.') }}
    </x-alert>

    @if (session('status') === 'verification-link-sent')
        <x-alert color="green">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </x-alert>
    @endif

    <div class="flex flex-col sm:flex-row gap-3 justify-between">
        <x-button wire:click="sendVerification">
            Reenviar e-mail de verificação
        </x-button>

        <x-button
            wire:click="logout"
            color="secondary"
            outline
        >
            Sair
        </x-button>
    </div>
</div>