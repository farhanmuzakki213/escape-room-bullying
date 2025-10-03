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
    public array $gameProgress = [];

    protected $listeners = [
        'startNewGame' => 'startNewGame',
        'continueGame' => 'continueGame',
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
        if ($completedLevel >= $this->unlockedLevel && $completedLevel < 4) {
            $this->unlockedLevel = $completedLevel + 1;
        }
        $this->saveProgressToSession();
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

    public function mount()
    {
        $progress = session('game_progress', []);
        $this->gameProgress = $progress;
        if (!empty($progress) && isset($progress['unlockedLevel'])) {
            $this->unlockedLevel = $progress['unlockedLevel'];
            $this->currentView = 'peta_misi';
        } else {
            $this->currentView = 'home';
        }
        // $this->currentView = 'level';
        // $this->currentLevel = '2';
    }

    public function startNewGame()
    {
        session()->forget('game_progress');
        $this->dispatch('clear-local-storage');
        $this->unlockedLevel = 1;
        $this->gameProgress = [];
        $this->showPetaMisi();
    }

    // METODE BARU: Untuk melanjutkan game
    public function continueGame(array $progress)
    {
        $this->showPetaMisi();
    }

    private function saveProgressToSession()
    {
        $currentProgress = session('game_progress', []);
        $currentProgress['unlockedLevel'] = $this->unlockedLevel;
        session(['game_progress' => $currentProgress]);
        $this->dispatch('save-progress-to-local-storage', progress: $currentProgress);
    }


    public function showPetaMisi()
    {
        $this->currentView = 'peta_misi';
        $this->currentLevel = null;
        $this->saveProgressToSession();
    }

    public function enterLevel(int $level)
    {
        if ($level <= $this->unlockedLevel) {
            $this->currentLevel = $level;
            $this->currentView = 'level';
            $this->saveProgressToSession();
        }
    }

    public function showHome()
    {
        $this->currentView = 'home';
    }

    public function render()
    {
        return view('livewire.game-manager');
    }
}
