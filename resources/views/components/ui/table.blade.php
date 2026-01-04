@props([
    'columns', // Array of ['label' => '', 'key' => '', 'align' => 'left', 'render' => fn($row) => '...']
    'rows' => [],
    'caption',
    'paginate' => false,
    'emptyMessage' => 'Nessun dato disponibile',
])

<div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
    <div class="overflow-x-auto focus:ring-2 focus:ring-green-500 outline-none" 
     tabindex="0" 
     role="region" 
     aria-labelledby="table-caption">
        <table class="w-full">
            <caption class="py-4">
                <p>{{ $caption }}<p>
            </caption>
            <thead class="bg-gray-800">
                <tr>
                    @foreach($columns as $column)
                        @php
                            $align = $column['align'] ?? 'left';
                            $alignClass = match($align) {
                                'right' => 'text-right',
                                'center' => 'text-center',
                                default => 'text-left',
                            };
                        @endphp
                        <th scope="col" class="px-6 py-4 {{ $alignClass }} text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            {{ $column['label'] }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($rows as $row)
                    <tr class="hover:bg-gray-800 transition-colors">
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
                            @endphp
                            
                            @if($isFirstColumn)
                                <th scope="row" class="px-6 py-4 {{ $wrapClass }} text-sm text-gray-300 {{ $alignClass }}">
                                    @if($hasRenderCallback)
                                        {!! $column['render']($row) !!}
                                    @elseif($key)
                                        {{ data_get($row, $key) ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </th>
                            @else
                                <td class="px-6 py-4 {{ $wrapClass }} text-sm text-gray-300 {{ $alignClass }}">
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
                        <td colspan="{{ count($columns) }}" class="px-6 py-12 text-center text-gray-400">
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($paginate && method_exists($rows, 'hasPages') && $rows->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">
            {{ $rows->links() }}
        </div>
    @endif
</div>
