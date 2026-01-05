@props([
    'id',
    'name',
    'label',
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'maxlength' => null,
    'prefix' => null,
    'helpText' => null
])

<div>
    <label for="{{ $id }}" class="block text-xs font-semibold text-gray-400 uppercase mb-2">
        {{ $label }}
    </label>
    
    <div class="relative">
        @if($prefix)
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white font-mono font-bold">{{ $prefix }}</span>
        @endif
        
        <input 
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $maxlength ? "maxlength={$maxlength}" : '' }}
            {{ $attributes->merge(['class' => ($prefix ? 'pl-8 ' : '') . 'w-full h-12 px-4 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent']) }}
        >
    </div>
    
    @if($helpText)
        <p class="mt-1 text-xs text-gray-500">
            {{ $helpText }}
        </p>
    @endif
</div>
