@props([
    'unreadNotifications' => 0,
    'isAdmin' => false
])

<div class="px-4 mb-6">
    <h2 class="text-xl font-bold text-text-main mb-4">Menu Opzioni</h2>
    
    <div class="space-y-3">
        {{-- Impostazioni Account --}}
        <a href="{{ route('profile.settings') }}" class="bg-surface-100 rounded-2xl p-5 border border-surface-200 hover:border-surface-200/80 transition-colors flex items-center justify-between group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-surface-200 rounded-full flex items-center justify-center group-hover:bg-surface-200/80 transition-colors">
                    <span class="material-icons text-text-main text-xl">settings</span>
                </div>
                <span class="text-text-main font-medium">Impostazioni Account</span>
            </div>
            <span class="material-icons text-text-muted group-hover:text-text-main transition-colors">chevron_right</span>
        </a>
        
        {{-- Centro Notifiche --}}
        <a href="#" class="bg-surface-100 rounded-2xl p-5 border border-surface-200 hover:border-surface-200/80 transition-colors flex items-center justify-between group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-surface-200 rounded-full flex items-center justify-center group-hover:bg-surface-200/80 transition-colors relative">
                    <span class="material-icons text-text-main text-xl">notifications</span>
                    @if($unreadNotifications > 0)
                        <span class="absolute -top-1 -right-1 bg-brand-danger text-text-main text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                        </span>
                    @endif
                </div>
                <span class="text-text-main font-medium">Centro Notifiche</span>
            </div>
            <div class="flex items-center gap-2">
                @if($unreadNotifications > 0)
                    <span class="bg-brand-danger text-text-main text-xs font-bold rounded-full px-2 py-1">
                        {{ $unreadNotifications }}
                    </span>
                @endif
                <span class="material-icons text-text-muted group-hover:text-text-main transition-colors">chevron_right</span>
            </div>
        </a>

        {{-- Admin Panel --}}
        @if($isAdmin)
        <a href="{{ route('admin.admin') }}" class="bg-gray-900 rounded-2xl p-5 border border-gray-800 hover:border-gray-700 transition-colors flex items-center justify-between group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                    <span class="material-icons text-white text-xl">shield</span>
                </div>
                <span class="text-white font-medium">Pannello Admin</span>
            </div>
            <span class="material-icons text-gray-600 group-hover:text-gray-400 transition-colors">chevron_right</span>
        </a>
        @endif
        
        {{-- Logout --}}
        <form action="{{ route('auth.logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-surface-100 rounded-2xl p-5 border border-surface-200 hover:border-brand-danger transition-colors flex items-center gap-4 group">
                <div class="w-12 h-12 bg-brand-danger/20 rounded-full flex items-center justify-center group-hover:bg-brand-danger/30 transition-colors">
                    <span class="material-icons text-brand-danger text-xl">logout</span>
                </div>
                <span class="text-brand-danger font-medium">Logout</span>
            </button>
        </form>
    </div>
</div>
