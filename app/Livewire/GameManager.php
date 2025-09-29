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
            $this->saveProgressToSession();
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

    public function mount()
    {
        $progress = session('game_progress', []);
        if (!empty($progress)) {
            $this->unlockedLevel = $progress['unlockedLevel'] ?? 1;
        }
        // $this->currentView = 'level';
        // $this->currentLevel = '4';
    }

    public function startNewGame()
    {
        session()->forget('game_progress');
        $this->dispatch('clear-local-storage');
        $this->unlockedLevel = 1;
        $this->showPetaMisi();
    }

    // METODE BARU: Untuk melanjutkan game
    public function continueGame(array $progress)
    {
        session(['game_progress' => $progress]);
        $this->unlockedLevel = $progress['unlockedLevel'] ?? 1;

        $this->showPetaMisi();
    }

    private function saveProgressToSession()
    {
        $progress = [
            'unlockedLevel' => $this->unlockedLevel,
        ];
        session(['game_progress' => $progress]);
        $this->dispatch('save-progress-to-local-storage', progress: $progress);
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
