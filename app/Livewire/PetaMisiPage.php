<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('layouts.guest')]
class PetaMisiPage extends Component
{
    public int $unlockedLevel;
    public array $levelTitles = [
        1 => 'Ruang Kelas',
        2 => 'Perpustakaan',
        3 => 'Lorong',
        4 => 'Lapangan',
    ];
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
