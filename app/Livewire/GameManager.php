<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class GameManager extends Component
{
    public string $currentView = 'home';
    public ?int $currentLevel = null;

    protected $listeners = [
        'startGame' => 'showPetaMisi',
        'backToHome' => 'showHome',
        'selectLevel' => 'enterLevel',
        'backToPetaMisi' => 'showPetaMisi'
    ];

    public function showPetaMisi()
    {
        $this->currentView = 'peta_misi';
        $this->currentLevel = null;
    }

    public function enterLevel(int $level)
    {
        $this->currentLevel = $level;
        $this->currentView = 'level';
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
