@props(['field' => null])

@if($errors->any() && !$field)
    <div class="p-4 mb-4 text-sm text-brand-danger bg-brand-danger/20 border border-brand-danger/30 rounded-lg">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@elseif($field && $errors->has($field))
    <p class="mt-1 text-sm text-brand-danger">{{ $errors->first($field) }}</p>
@endif
