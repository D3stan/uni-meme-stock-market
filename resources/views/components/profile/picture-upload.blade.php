@props([
    'user',
])

<div class="relative w-24 h-24 mx-auto">
    {{-- Avatar Image --}}
    <img 
        src="{{ $user->avatarUrl() }}" 
        alt="{{ $user->name }}"
        class="w-full h-full rounded-full object-cover border-4 border-surface-200"
        id="avatar-preview"
    >
    
    {{-- Edit Icon Button --}}
    <label for="avatar-upload" class="absolute bottom-0 right-0 w-10 h-10 bg-brand rounded-full flex items-center justify-center cursor-pointer hover:bg-brand-light transition-colors border-4 border-surface-50">
        <span class="material-icons text-text-main text-lg">edit</span>
    </label>
    
    {{-- Hidden File Input --}}
    <input 
        type="file" 
        id="avatar-upload" 
        name="avatar" 
        accept="image/*"
        class="hidden"
        onchange="previewAvatar(event)"
    >
</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB = 2048KB = 2097152 bytes)
        const maxSizeBytes = 2 * 1024 * 1024; // 2MB
        if (file.size > maxSizeBytes) {
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            if (typeof window.showToast === 'function') {
                window.showToast('error', `L'immagine è troppo grande (${sizeMB}MB). Dimensione massima: 2MB.`, 'error');
            } else {
                alert(`L'immagine è troppo grande (${sizeMB}MB). Dimensione massima: 2MB.`);
            }
            // Clear the file input
            event.target.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            if (typeof window.showToast === 'function') {
                window.showToast('error', 'Formato immagine non supportato. Usa: jpeg, png, jpg, gif.', 'error');
            } else {
                alert('Formato immagine non supportato. Usa: jpeg, png, jpg, gif.');
            }
            // Clear the file input
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
