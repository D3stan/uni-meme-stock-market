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
        <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">{{ $icon }}</span>
    @endif
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => ($icon ? 'pl-12 ' : '') . ($type === 'password' ? 'pr-12 ' : '') . 'bg-[#1a2e23] border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-[#1a2e23] dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-300 dark:focus:ring-blue-500 dark:focus:border-blue-500']) }}
    >
    @if($type === 'password')
        <button type="button" tabindex="-1" class="absolute right-3 top-0 h-full flex items-center text-gray-400 focus:outline-none" onclick="
            var input = this.previousElementSibling;
            var icon = this.querySelector('.material-icons');
            if(input.type === 'password') { input.type = 'text'; icon.innerHTML = 'visibility_off'; }
            else { input.type = 'password'; icon.innerHTML = 'visibility'; }
        ">
            <span class="material-icons">visibility</span>
        </button>
    @endif
</div>