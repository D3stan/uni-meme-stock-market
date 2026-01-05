@props([
    'user',
])

<div class="relative w-24 h-24 mx-auto">
    <!-- Avatar Image -->
    <img 
        src="{{ $user->avatar ?? asset('default-avatar.png') }}" 
        alt="{{ $user->name }}"
        class="w-full h-full rounded-full object-cover border-4 border-gray-800"
        id="avatar-preview"
    >
    
    <!-- Edit Icon Button -->
    <label for="avatar-upload" class="absolute bottom-0 right-0 w-10 h-10 bg-brand rounded-full flex items-center justify-center cursor-pointer hover:bg-brand-dark transition-colors border-4 border-gray-950">
        <span class="material-icons text-white text-lg">edit</span>
    </label>
    
    <!-- Hidden File Input -->
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
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
