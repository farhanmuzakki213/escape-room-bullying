<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class StartSequencePage extends Component
{
    /**
     * Menentukan langkah tutorial yang sedang aktif (1, 2, or 3).
     */
    public int $step = 1;

    /**
     * Daftar gambar background untuk setiap langkah.
     */
    public array $backgroundImages = [
        1 => 'images/home/start-step-1.jpg',
        2 => 'images/home/start-step-2.jpg',
        3 => 'images/home/start-step-3.jpg',
    ];

    /**
     * Mengatur properti $step.
     * Dipanggil oleh tombol 'Mengerti' (step 1) dan 'Next' (step 2).
     */
    public function nextStep()
    {
        if ($this->step < 3) {
            $this->step++;
        }
    }

    /**
     * Menyelesaikan sequence dan beralih ke Peta Misi.
     * Dipanggil oleh tombol 'Next' (step 3).
     */
    public function finishSequence()
    {
        // Mengirim event ke GameManager untuk pindah ke peta misi
        $this->dispatch('finishStartSequence');
    }

    /**
     * Kembali ke halaman utama (home).
     * Mengirimkan event 'backToHome' ke GameManager.
     */
    public function goHome()
    {
        $this->dispatch('backToHome');
    }

    /**
     * Merender tampilan komponen.
     */
    public function render()
    {
        return view('livewire.start-sequence-page', [
            'currentBackground' => $this->backgroundImages[$this->step]
        ]);
    }
}
