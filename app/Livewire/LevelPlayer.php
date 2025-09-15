<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class LevelPlayer extends Component
{
    public int $levelId;
    public string $backgroundUrl;

    public array $levelData = [
        1 => ['background' => 'images/petamisi/background-peta-misi.svg', 'title' => 'Ruang Kelas'],
        2 => ['background' => 'images/petamisi/background-peta-misi.svg', 'title' => 'Perpustakaan'],
        3 => ['background' => 'images/petamisi/background-peta-misi.svg', 'title' => 'Lorong Kelas'],
        4 => ['background' => 'images/petamisi/background-peta-misi.svg', 'title' => 'Lapangan'],
    ];

    public function mount()
    {
        $this->backgroundUrl = asset($this->levelData[$this->levelId]['background']);
    }

    public function backToPetaMisi()
    {
        $this->dispatch('backToPetaMisi');
    }

    public function render()
    {
        return view('livewire.level-player');
    }
}
