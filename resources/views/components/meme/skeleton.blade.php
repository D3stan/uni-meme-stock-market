@props(['class' => ''])

<div class="card-base animate-pulse {{ $class }}" aria-hidden="true">
    {{-- Header Card --}}
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center gap-3">
            {{-- Avatar Placeholder --}}
            <div class="w-8 h-8 rounded-full bg-surface-200"></div>
            <div class="space-y-1">
                {{-- Title Placeholder --}}
                <div class="h-4 w-24 bg-surface-200 rounded"></div>
                {{-- Ticker Placeholder --}}
                <div class="h-3 w-12 bg-surface-200 rounded"></div>
            </div>
        </div>
        
        {{-- Badge Placeholder --}}
        <div class="h-5 w-16 bg-surface-200 rounded"></div>
    </div>
    
    {{-- Image Placeholder - Changed from aspect-square to aspect-[4/3] to be less tall --}}
    <div class="w-full aspect-[4/3] bg-surface-200"></div>
    
    {{-- Info Bar --}}
    <div class="p-4">
        <div class="flex items-end justify-between mb-4">
            {{-- Price Placeholder --}}
            <div class="space-y-1">
                <div class="h-8 w-24 bg-surface-200 rounded"></div>
                <div class="h-3 w-10 bg-surface-200 rounded"></div>
            </div>
            
            {{-- Badge Change Placeholder --}}
            <div class="h-7 w-20 bg-surface-200 rounded"></div>
        </div>
        
        {{-- Button Placeholder --}}
        <div class="h-[48px] w-full bg-surface-200 rounded-xl"></div>
    </div>
</div>