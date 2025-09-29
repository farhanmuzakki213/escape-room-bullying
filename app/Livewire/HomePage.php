<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
class HomePage extends Component
{
    public ?array $savedProgress = null;

    public function loadProgress(array $progress)
    {
        $this->savedProgress = $progress;
    }


    public function startNewGame()
    {
        $this->dispatch('startNewGame');
    }

    public function continueGame()
    {
        if ($this->savedProgress) {
            $this->dispatch('continueGame', $this->savedProgress);
        }
    }

    public function render()
    {
        return view('livewire.home-page');
    }
}
