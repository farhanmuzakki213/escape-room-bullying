<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('layouts.guest')]
/**
 * Komponen Livewire untuk halaman Peta Misi.
 * Halaman ini menampilkan level-level yang tersedia dan yang sudah terbuka.
 */
class PetaMisiPage extends Component
{
    /**
     * Level tertinggi yang sudah berhasil dibuka oleh pemain.
     * Properti ini di-passing dari GameManager.
     */
    public int $unlockedLevel;

    /**
     * Judul untuk setiap level yang akan ditampilkan di peta.
     */
    public array $levelTitles = [
        1 => 'Ruang Kelas',
        2 => 'Kantin',
        3 => 'Lorong',
        4 => 'Lapangan',
    ];

    /**
     * Memilih level untuk dimainkan.
     * Mengirimkan event 'selectLevel' ke GameManager.
     *
     * @param int $level ID level yang dipilih.
     */
    public function selectLevel(int $level)
    {
        $this->dispatch('selectLevel', level: $level);
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
        return view('livewire.peta-misi-page');
    }
}
