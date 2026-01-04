@props([
    'id' => 'datepicker',
    'name' => 'date',
    'label' => null,
    'helpText' => null,
])

<div>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-300 mb-2">
            {{ $label }}
        </label>
    @endif

    <input type="datetime-local" id="{{ $id }}" name="{{ $name }}" onkeydown="return false" onclick="this.showPicker()"
        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent cursor-pointer"
    />

    @if($helpText)
        <p class="mt-1 text-xs text-gray-500">{{ $helpText }}</p>
    @endif
</div>
