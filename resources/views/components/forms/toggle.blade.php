@props([
    'id',
    'name',
    'text',
    'value' => 1,
    'checked' => false,
])

<label class="inline-flex items-center mb-5 cursor-pointer">
  <input type="checkbox" id="{{ $id }}" value="{{ $value }}" name="{{ $name }}" class="sr-only peer" {{ $checked ? 'checked' : '' }}>
  <div class="relative w-11 h-6 bg-surface-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand/30 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-text-main after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-text-main after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
  <span class="select-none ms-3 text-sm font-medium text-text-main">{{ $text }}</span>
</label>