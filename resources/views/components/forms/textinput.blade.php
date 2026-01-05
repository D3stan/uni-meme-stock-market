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
    <label for="{{ $id }}" class="block text-xs font-semibold text-text-muted uppercase mb-2">
        {{ $label }}
    </label>
    
    <div class="relative">
        @if($prefix)
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-main font-mono font-bold">{{ $prefix }}</span>
        @endif
        
        <input 
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $maxlength ? "maxlength={$maxlength}" : '' }}
            {{ $attributes->merge(['class' => ($prefix ? 'pl-8 ' : '') . 'input-base h-12']) }}
        >
    </div>
    
    @if($helpText)
        <p class="mt-1 text-xs text-text-muted">
            {{ $helpText }}
        </p>
    @endif
</div>
