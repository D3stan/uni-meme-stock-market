@props([
    'id',
    'name',
    'label',
    'options' => [],
    'placeholder' => 'Seleziona...',
    'required' => false
])

<div>
    <label for="{{ $id }}" class="block text-xs font-semibold text-gray-400 uppercase mb-2">
        {{ $label }}
    </label>
    <div class="relative">
        <select 
            id="{{ $id }}" 
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full h-12 px-4 pr-10 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent cursor-pointer']) }}
            style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none;"
        >
            <option value="" disabled selected>{{ $placeholder }}</option>
            @foreach($options as $option)
                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
            @endforeach
        </select>
        <span class="material-icons absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
            expand_more
        </span>
    </div>
</div>
