@props([
    'type' => 'text',
    'name',
    'id' => null,
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'disabled' => false,
    'icon' => null, // material icon name
])

<div class="relative w-full">
    @if($icon)
        <span aria-hidden="true" class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-text-muted pointer-events-none">{{ $icon }}</span>
    @endif
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => ($icon ? 'pl-12 ' : '') . ($type === 'password' ? 'pr-12 ' : '') . 'bg-surface-50 border border-surface-200 text-text-main text-sm rounded-lg focus:ring-brand focus:border-brand block w-full p-2.5 placeholder-text-muted']) }}
    >
    @if($type === 'password')
        <button type="button" tabindex="-1" class="absolute right-3 top-0 h-full flex items-center text-text-muted focus:outline-none" onclick="
            var input = this.previousElementSibling;
            var icon = this.querySelector('.material-icons');
            if(input.type === 'password') { input.type = 'text'; icon.innerHTML = 'visibility_off'; }
            else { input.type = 'password'; icon.innerHTML = 'visibility'; }
        ">
            <span class="material-icons">visibility</span>
        </button>
    @endif
</div>