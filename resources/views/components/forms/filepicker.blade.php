@props([
    'name' => 'image',
    'id' => null,
    'accept' => 'image/*',
    'maxSize' => '5MB',
    'emptyText' => 'Tocca per caricare',
    'formats' => '',
])

@php
    $uploadBoxId = $id ? $id . '-box' : 'uploadBox-' . uniqid();
    $emptyStateId = $id ? $id . '-empty' : 'emptyState-' . uniqid();
    $previewId = $id ? $id . '-preview' : 'imagePreview-' . uniqid();
    $previewImgId = $id ? $id . '-img' : 'previewImg-' . uniqid();
    $changeBtnId = $id ? $id . '-change' : 'changeImageBtn-' . uniqid();
    $inputId = $id ?? 'imageInput-' . uniqid();
@endphp

<div class="p-6">
    <div id="{{ $uploadBoxId }}" class="relative h-60 border-2 border-dashed border-surface-200 rounded-xl bg-surface-50 flex flex-col items-center justify-center cursor-pointer hover:border-brand transition-colors">
        
        {{-- Empty State --}}
        <div id="{{ $emptyStateId }}" class="text-center">
            <span aria-hidden="true" class="material-icons text-text-muted text-5xl mb-3">upload</span>
            <p class="text-sm text-text-muted">{{ $emptyText }}</p>
            <p class="text-xs text-text-muted mt-1">{{ $formats }} (Max {{ $maxSize }})</p>
        </div>

        {{-- Image Preview (hidden by default) --}}
        <div id="{{ $previewId }}" class="hidden w-full h-full">
            <img id="{{ $previewImgId }}" src="" alt="Preview" class="w-full h-full object-contain rounded-xl">
            <button type="button" id="{{ $changeBtnId }}" class="absolute top-3 right-3 px-3 py-1.5 bg-surface-200/90 hover:bg-surface-200 border border-surface-200 text-text-main text-xs font-medium rounded-lg transition-colors">
                Cambia
            </button>
        </div>

        <input type="file" id="{{ $inputId }}" name="{{ $name }}" accept="{{ $accept }}" class="hidden">
    </div>
</div>

@push('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadBox = document.getElementById('{{ $uploadBoxId }}');
        const imageInput = document.getElementById('{{ $inputId }}');
        const emptyState = document.getElementById('{{ $emptyStateId }}');
        const imagePreview = document.getElementById('{{ $previewId }}');
        const previewImg = document.getElementById('{{ $previewImgId }}');
        const changeImageBtn = document.getElementById('{{ $changeBtnId }}');

        // Click on upload to open file input
        uploadBox.addEventListener('click', function(e) {
            if (!e.target.closest('#{{ $changeBtnId }}')) {
                imageInput.click();
            }
        });

        // Click on change button
        changeImageBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            imageInput.click();
        });

        // Handle file selection
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    emptyState.classList.add('hidden');
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
