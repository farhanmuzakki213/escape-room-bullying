<div>
    @if ($currentView === 'home')
        <livewire:home-page wire:key="home" @start-game="$dispatch('startGame')" />
    @elseif ($currentView === 'peta_misi')
        <livewire:peta-misi-page wire:key="peta-misi" :unlockedLevel="$unlockedLevel" />
    @elseif ($currentView === 'level')
        <livewire:level-player wire:key="level-{{ $currentLevel }}" :levelId="$currentLevel" />
    @elseif ($currentView === 'help')
        <livewire:help-page wire:key="help" />
    @elseif ($currentView === 'profile')
        <livewire:profile-page wire:key="profile" />
    @endif
</div>
