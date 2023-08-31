<div class="fi-ta-text grid gap-y-1 px-3 py-4">
    <div class="flex flex-wrap items-center gap-1.5">
        @if ($getRecord()->name === 'Super Admin')
            <x-filament::badge color="success" icon="heroicon-m-lock-open">
                <span>*</span>
            </x-filament::badge>
        @else
            @foreach ($getRecord()->permissions->pluck('name') as $p)
                <x-filament::badge color="success" icon="heroicon-m-lock-closed">
                    <span>{{ $p }}</span>
                </x-filament::badge>
            @endforeach
        @endif
    </div>
</div>
