<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="relative group">
        <div class="flex items-center gap-3 p-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/[0.07] transition-all">
            <!-- Icon -->
            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/20 group-hover:scale-110 transition-transform">
                <x-filament::icon :icon="$getIcon()" class="w-5 h-5" />
            </div>

            <!-- Content -->
            <div class="flex-grow min-w-0">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-sm font-black text-gray-100 tabular-nums">
                        {{ $getValue() }}
                    </span>
                    @if($unit = $getUnit())
                        <span class="text-[9px] font-bold text-blue-400/80 uppercase">
                            {{ $unit }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
