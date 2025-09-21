<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.guest')]
class GameManager extends Component
{
    public string $currentView = 'home';
    public ?int $currentLevel = null;
    public int $unlockedLevel = 1;

    protected $listeners = [
        'startGame' => 'showPetaMisi',
        'backToHome' => 'showHome',
        'selectLevel' => 'enterLevel',
        'backToPetaMisi' => 'showPetaMisi'
    ];

    #[On('levelCompleted')]
    public function handleLevelCompleted(int $completedLevel)
    {
        if ($completedLevel == $this->unlockedLevel) {
            $this->unlockedLevel++;
        }
    }

    // public function mount()
    // {
    //     $this->currentView = 'peta_misi';
    //     $this->currentLevel = null;
    // }

    public function showPetaMisi()
    {
        $this->currentView = 'peta_misi';
        $this->currentLevel = null;
    }

    public function enterLevel(int $level)
    {
        if ($level <= $this->unlockedLevel) {
            $this->currentLevel = $level;
            $this->currentView = 'level';
        }
    }

    public function showHome()
    {
        $this->currentView = 'home';
        $this->unlockedLevel = 1;
    }

    public function render()
    {
        return view('livewire.game-manager');
    }
}
