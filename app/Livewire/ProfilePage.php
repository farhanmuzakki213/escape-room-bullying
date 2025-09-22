<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class ProfilePage extends Component
{
    /**
     * Mengirim event untuk kembali ke halaman sebelumnya.
     */
    public function back()
    {
        $this->dispatch('hideProfile');
    }

    public function render()
    {
        return view('livewire.profile-page');
    }
}
