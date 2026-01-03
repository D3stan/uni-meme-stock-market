@props(['field' => null])

@if($errors->any() && !$field)
    <div class="p-4 mb-4 text-sm text-red-400 bg-red-900/30 border border-red-800 rounded-lg">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@elseif($field && $errors->has($field))
    <p class="mt-1 text-sm text-red-400">{{ $errors->first($field) }}</p>
@endif
