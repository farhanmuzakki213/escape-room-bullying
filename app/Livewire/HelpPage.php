<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class HelpPage extends Component
{
    public function back()
    {
        $this->dispatch('hideHelp');
    }

    public function render()
    {
        return view('livewire.help-page');
    }
}
