<x-filament-panels::page.simple>
    <div class="space-y-3 text-center">
        <div class="flex justify-center">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="Logo"
                class="h-16 w-auto"
            />
        </div>

        <div class="space-y-1">
            <h1 class="text-2xl font-semibold tracking-tight">
                STOCK MANAGEMENT
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Sign in with your ENO and Eportal password
            </p>
        </div>
    </div>

    <div class="mt-6">
        {{ \Filament\Support\Facades\FilamentView::renderHook(
            \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
            scopes: $this->getRenderHookScopes()
        ) }}
    </div>

    <div class="mt-4">
        <x-filament-panels::form id="form" wire:submit="authenticate">
            <div class="space-y-4">
                {{ $this->form }}
            </div>

            <div class="mt-6">
                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </div>
        </x-filament-panels::form>
    </div>

    <div class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
        <span>Having trouble? Contact your establishment administrator.</span>
    </div>

    <div class="mt-4">
        {{ \Filament\Support\Facades\FilamentView::renderHook(
            \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            scopes: $this->getRenderHookScopes()
        ) }}
    </div>
</x-filament-panels::page.simple>
