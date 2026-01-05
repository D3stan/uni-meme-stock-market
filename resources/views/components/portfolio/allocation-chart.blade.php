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

<div class="bg-gray-800 rounded-3xl p-6 border border-gray-700">
    {{-- Title --}}
    <h2 class="text-lg font-semibold text-white mb-6">Allocazione Patrimonio</h2>
    
    <div class="flex flex-col items-center gap-6">
        {{-- Donut Chart Container --}}
        <div class="relative w-48 h-48">
            <canvas id="allocation-chart"></canvas>
            {{-- Center Text --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <p class="text-xs text-gray-400 uppercase">Investito</p>
                <p class="text-3xl font-black text-white">{{ $investedPct }}%</p>
            </div>
        </div>
        
        {{-- Legend --}}
        <div class="w-full space-y-3">
            {{-- Invested --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-sm text-gray-300">Investito</span>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-white font-mono">{{ $formattedInvested }} CFU</span>
                    <span class="text-xs text-gray-400 ml-2">({{ $investedPct }}%)</span>
                </div>
            </div>
            
            {{-- Liquid --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-gray-600"></div>
                    <span class="text-sm text-gray-300">Liquidità</span>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-white font-mono">{{ $formattedLiquid }} CFU</span>
                    <span class="text-xs text-gray-400 ml-2">({{ $liquidPct }}%)</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('allocation-chart');
        if (!ctx) return;
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Investito', 'Liquidità'],
                datasets: [{
                    data: [{{ $invested }}, {{ $liquid }}],
                    backgroundColor: ['#10B981', '#4B5563'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                return label + ': ' + value.toFixed(2) + ' CFU';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
