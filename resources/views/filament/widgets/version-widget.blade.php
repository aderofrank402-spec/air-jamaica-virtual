<x-filament-widgets::widget class="fi-filament-info-widget">
    <x-filament::section>
        <div class="fi-filament-info-widget-main">
            <div>
                <img src="{{ asset('air-jamaica-logo.png') }}" class="h-10" alt="Air Jamaica Virtual Logo" />
            </div>

            <p class="fi-filament-info-widget-version">
                Air Jamaica Virtual {{ $version }}
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
