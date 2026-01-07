@props([
    'columns', // Array of ['label' => '', 'key' => '', 'align' => 'left', 'render' => fn($row) => '...']
    'rows' => [],
    'caption',
    'paginate' => false,
    'emptyMessage' => 'Nessun dato disponibile',
    'actions' => null, // Slot per azioni nella caption
])

@php
    $tableId = 'table-' . uniqid();
@endphp

<div class="bg-surface-100 rounded-2xl border border-surface-200 overflow-hidden">
    <div class="overflow-x-auto focus:ring-2 focus:ring-brand outline-none relative" 
     tabindex="0" 
     role="region" 
     aria-labelledby="{{ $tableId }}">
        <table class="w-full relative">
            <caption id="{{ $tableId }}" class="py-4 px-6">
                <div class="flex items-center justify-between">
                    <p class="text-left">{{ $caption }}</p>
                    @if($actions)
                        <div>{{ $actions }}</div>
                    @endif
                </div>
            </caption>
            <thead class="bg-surface-200 sticky md:static top-0 z-10">
                <tr>
                    @foreach($columns as $index => $column)
                        @php
                            $align = $column['align'] ?? 'left';
                            $alignClass = match($align) {
                                'right' => 'text-right',
                                'center' => 'text-center',
                                default => 'text-left',
                            };
                            $isFirstColumn = $index === 0;
                            $stickyClass = $isFirstColumn ? 'sticky md:static left-0 md:left-auto z-20 bg-surface-200' : '';
                        @endphp
                        <th scope="col" class="px-6 py-4 {{ $alignClass }} {{ $stickyClass }} text-xs font-semibold text-text-muted uppercase tracking-wider">
                            {{ $column['label'] }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-200">
                @forelse($rows as $row)
                    <tr class="hover:bg-surface-200 transition-colors">
                        @foreach($columns as $index => $column)
                            @php
                                $key = $column['key'] ?? '';
                                $align = $column['align'] ?? 'left';
                                $wrap = $column['wrap'] ?? false;
                                $alignClass = match($align) {
                                    'right' => 'text-right',
                                    'center' => 'text-center',
                                    default => 'text-left',
                                };
                                $wrapClass = $wrap ? 'whitespace-normal break-words' : 'whitespace-nowrap';
                                $hasRenderCallback = isset($column['render']) && is_callable($column['render']);
                                $isFirstColumn = $index === 0;
                                $stickyCellClass = $isFirstColumn ? 'sticky md:static left-0 md:left-auto z-10 bg-surface-100' : '';
                            @endphp
                            
                            @if($isFirstColumn)
                                <th scope="row" class="px-6 py-4 {{ $wrapClass }} {{ $stickyCellClass }} text-sm text-text-muted {{ $alignClass }}">
                                    @if($hasRenderCallback)
                                        {!! $column['render']($row) !!}
                                    @elseif($key)
                                        {{ data_get($row, $key) ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </th>
                            @else
                                <td class="px-6 py-4 {{ $wrapClass }} text-sm text-text-muted {{ $alignClass }}">
                                    @if($hasRenderCallback)
                                        {!! $column['render']($row) !!}
                                    @elseif($key)
                                        {{ data_get($row, $key) ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}" class="px-6 py-12 text-center text-text-muted">
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($paginate && method_exists($rows, 'hasPages') && $rows->hasPages())
        <div class="px-6 py-4 border-t border-surface-200">
            {{ $rows->links() }}
        </div>
    @endif
</div>
