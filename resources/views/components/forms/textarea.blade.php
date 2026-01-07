@props([
    'id',
    'name',
    'label',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'disabled' => false,
    'rows' => 4,
    'helpText' => null,
])

<div>
    <label for="{{ $id }}" class="block text-xs font-semibold text-text-muted uppercase mb-2">
        {{ $label }}
    </label>
    
    <textarea 
        id="{{ $id }}" 
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}" 
        rows="{{ $rows }}" 
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        @if($helpText) aria-describedby="{{ $id }}-help" @endif
        {{ $attributes->merge(['class' => 'input-base block w-full py-2.5']) }}
    >{{ old($name, $value) }}</textarea>

    @if($helpText)
        <p id="{{ $id }}-help" class="mt-1 text-xs text-text-muted">
            {{ $helpText }}
        </p>
    @endif
</div>