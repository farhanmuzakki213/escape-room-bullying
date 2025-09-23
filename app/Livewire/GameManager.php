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
    public ?string $previousView = null;

    protected $listeners = [
        'startGame' => 'showPetaMisi',
        'backToHome' => 'showHome',
        'selectLevel' => 'enterLevel',
        'backToPetaMisi' => 'showPetaMisi',
        'showHelp' => 'showHelpScreen',
        'hideHelp' => 'hideHelpScreen',
        'showProfile' => 'showProfileScreen',
        'hideProfile' => 'hideProfileScreen'
    ];

    #[On('levelCompleted')]
    public function handleLevelCompleted(int $completedLevel)
    {
        if ($completedLevel == $this->unlockedLevel) {
            $this->unlockedLevel++;
        }
    }

    public function showHelpScreen()
    {
        $this->previousView = $this->currentView;
        $this->currentView = 'help';
    }

    public function hideHelpScreen()
    {
        $this->currentView = $this->previousView;
        $this->previousView = null;
    }

    public function showProfileScreen()
    {
        $this->previousView = $this->currentView;
        $this->currentView = 'profile';
    }

    public function hideProfileScreen()
    {
        $this->currentView = $this->previousView;
        $this->previousView = null;
    }

    // public function mount()
    // {
    //     $this->currentView = 'level';
    //     $this->currentLevel = '1';
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
