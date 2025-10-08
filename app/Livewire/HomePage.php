<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
class HomePage extends Component
{
    /**
     * Menyimpan data progres permainan yang diambil dari local storage.
     */
    public ?array $savedProgress = null;

    /**
     * Mengontrol visibilitas popup untuk melanjutkan permainan.
     */
    public bool $showContinuePopup = false;

    /**
     * Memuatkan popup untuk melanjutkan permainan yang tersimpan sebelumnya.
     * Metode ini dipanggil dari JavaScript setelah data progres diambil dari Local Storage.
     *
     * @param array $progress Data permainan yang tersimpan sebelumnya.
     */
    public function loadProgress(array $progress)
    {
        if (!empty($progress)) {
            $this->savedProgress = $progress;
            $this->showContinuePopup = true;
        }
    }


    /**
     * Memulai permainan baru.
     * Mengirimkan event 'startNewGame' ke komponen GameManager.
     */
    public function startNewGame()
    {
        $this->dispatch('startNewGame');
    }

    /**
     * Melanjutkan permainan yang tersimpan.
     * Mengirimkan event 'continueGame' ke komponen GameManager.
     */
    public function continueGame()
    {
        $this->dispatch('continueGame');
    }

    /**
     * Merender tampilan komponen.
     */
    public function render()
    {
        return view('livewire.home-page');
    }
}
