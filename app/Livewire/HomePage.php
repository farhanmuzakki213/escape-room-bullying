<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
class HomePage extends Component
{
    public ?array $savedProgress = null;
    public bool $showContinuePopup = false;

    public function loadProgress(array $progress)
    {
        if (!empty($progress)) {
            $this->savedProgress = $progress;
            $this->showContinuePopup = true;
        }
    }


    public function startNewGame()
    {
        $this->dispatch('startNewGame');
    }

    public function continueGame()
    {
        $this->dispatch('continueGame');
    }

    public function render()
    {
        return view('livewire.home-page');
    }
}
