@props([
    'for',
    'title',
    'message',
    'id',
    'name',
    'placeholder' => '...',
])

<label for="{{ $for }}" class="block mb-2.5 text-sm font-medium text-heading">{{ $title }}</label>
<textarea id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder }}" rows="4" class="input-base"></textarea>
