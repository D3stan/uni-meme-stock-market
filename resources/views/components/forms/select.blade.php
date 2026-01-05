@props([
    'id',
    'name',
    'label',
    'options' => [],
    'placeholder' => 'Seleziona...',
    'required' => false
])

<div>
    <label for="{{ $id }}" class="block text-xs font-semibold text-text-muted uppercase mb-2">
        {{ $label }}
    </label>
    <div class="relative">
        <select 
            id="{{ $id }}" 
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'input-base h-12 pr-10 cursor-pointer']) }}
            style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none;"
        >
            <option value="" disabled selected>{{ $placeholder }}</option>
            @foreach($options as $option)
                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
            @endforeach
        </select>
        <span class="material-icons absolute right-4 top-1/2 -translate-y-1/2 text-text-muted pointer-events-none">
            expand_more
        </span>
    </div>
</div>
