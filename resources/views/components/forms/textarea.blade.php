@props([
    'for',
    'title',
    'message',
    'id',
    'name',
    'placeholder' => '...',
])

<label for="{{ $for }}" class="block mb-2.5 text-sm font-medium text-heading">{{ $title }}</label>
<textarea id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder }}" rows="4" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body"></textarea>
