<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('layouts.guest')]
class PetaMisiPage extends Component
{
    public function selectLevel(int $level)
    {
        $this->dispatch('selectLevel', level: $level);
    }

    public function goHome()
    {
        $this->dispatch('backToHome');
    }

    public function render()
    {
        return view('livewire.peta-misi-page');
    }
}
