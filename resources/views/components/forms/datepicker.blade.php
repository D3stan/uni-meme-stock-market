@props([
    'id' => 'datepicker',
    'name' => 'date',
    'label' => null,
    'helpText' => null,
])

<div>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-text-muted mb-2">
            {{ $label }}
        </label>
    @endif

    <input type="datetime-local" id="{{ $id }}" name="{{ $name }}" onkeydown="return false" onclick="this.showPicker()"
        class="input-base cursor-pointer"
    />

    @if($helpText)
        <p class="mt-1 text-xs text-text-muted">{{ $helpText }}</p>
    @endif
</div>
