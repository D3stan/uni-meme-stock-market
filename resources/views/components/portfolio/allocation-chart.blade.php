@props([
    'invested' => 0,
    'liquid' => 0,
])

@php
    $total = $invested + $liquid;
    $investedPct = $total > 0 ? round(($invested / $total) * 100) : 0;
    $liquidPct = $total > 0 ? round(($liquid / $total) * 100) : 100;
    
    $formattedInvested = number_format($invested, 2);
    $formattedLiquid = number_format($liquid, 2);
@endphp

<section aria-labelledby="allocation-title" class="bg-surface-100 rounded-3xl p-6 border border-surface-200">
    {{-- Title --}}
    <h2 id="allocation-title" class="text-lg font-semibold text-text-main mb-6">Allocazione Patrimonio</h2>
    
    <div class="flex flex-col items-center gap-6">
        {{-- Donut Chart Container --}}
        <div class="relative w-48 h-48" role="img" aria-label="Grafico a ciambella: {{ $investedPct }}% Investito, {{ $liquidPct }}% Liquidità">
            <canvas aria-hidden="true" id="allocation-chart" data-invested="{{ $invested }}" data-liquid="{{ $liquid }}"></canvas>
            {{-- Center Text --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center" aria-hidden="true">
                <p class="text-xs text-text-muted uppercase">Investito</p>
                <p class="text-3xl font-black text-text-main">{{ $investedPct }}%</p>
            </div>
        </div>
        
        {{-- Legend --}}
        <div class="w-full space-y-3">
            {{-- Invested --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-brand" aria-hidden="true"></div>
                    <span class="text-sm text-text-muted">Investito</span>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-text-main font-mono">{{ $formattedInvested }} CFU</span>
                    <span class="text-xs text-text-muted ml-2">({{ $investedPct }}%)</span>
                </div>
            </div>
            
            {{-- Liquid --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-surface-200" aria-hidden="true"></div>
                    <span class="text-sm text-text-muted">Liquidità</span>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-text-main font-mono">{{ $formattedLiquid }} CFU</span>
                    <span class="text-xs text-text-muted ml-2">({{ $liquidPct }}%)</span>
                </div>
            </div>
        </div>
    </div>
</section>