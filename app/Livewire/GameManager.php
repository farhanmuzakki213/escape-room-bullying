<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.guest')]
/**
 * Komponen utama yang mengelola state dan alur navigasi permainan.
 * Bertindak sebagai "state machine" yang menentukan tampilan mana yang aktif
 * (home, peta_misi, level, dll.) dan mengelola progres pemain.
 */
class GameManager extends Component
{
    /**
     * Menentukan tampilan/layar yang sedang aktif.
     * Contoh: 'home', 'peta_misi', 'level', 'help', 'profile'.
     */
    public string $currentView = 'home';

    /**
     * ID level yang sedang dimainkan. Null jika tidak berada di dalam level.
     */
    public ?int $currentLevel = null;

    /**
     * Level tertinggi yang telah berhasil dibuka oleh pemain.
     */
    public int $unlockedLevel = 1;

    /**
     * Menyimpan tampilan sebelumnya saat membuka layar sementara seperti 'help' atau 'profile'.
     */
    public ?string $previousView = null;

    /**
     * Menyimpan seluruh data progres permainan dari session.
     */
    public array $gameProgress = [];

    /**
     * Mendefinisikan listener untuk event yang dikirim dari komponen anak.
     */
    protected $listeners = [
        'startNewGame' => 'startNewGame',
        'continueGame' => 'continueGame',
        'backToHome' => 'showHome',
        'selectLevel' => 'enterLevel',
        'backToPetaMisi' => 'showPetaMisi',
        'showHelp' => 'showHelpScreen',
        'hideHelp' => 'hideHelpScreen',
        'showProfile' => 'showProfileScreen',
        'hideProfile' => 'hideProfileScreen',
        'finishStartSequence' => 'showPetaMisi'
    ];

    /**
     * Menangani event ketika sebuah level berhasil diselesaikan.
     * Akan membuka level berikutnya dan menyimpan progres.
     *
     * @param int $completedLevel ID level yang baru saja selesai.
     */
    #[On('levelCompleted')]
    public function handleLevelCompleted(int $completedLevel)
    {
        if ($completedLevel >= $this->unlockedLevel && $completedLevel < 4) {
            $this->unlockedLevel = $completedLevel + 1;
        }
        $this->saveProgressToSession();
    }

    /**
     * Menampilkan layar bantuan (help screen).
     */
    public function showHelpScreen()
    {
        $this->previousView = $this->currentView;
        $this->currentView = 'help';
    }

    /**
     * Menyembunyikan layar bantuan dan kembali ke tampilan sebelumnya.
     */
    public function hideHelpScreen()
    {
        $this->currentView = $this->previousView;
        $this->previousView = null;
    }

    /**
     * Menampilkan layar profil.
     */
    public function showProfileScreen()
    {
        $this->previousView = $this->currentView;
        $this->currentView = 'profile';
    }

    /**
     * Menyembunyikan layar profil dan kembali ke tampilan sebelumnya.
     */
    public function hideProfileScreen()
    {
        $this->currentView = $this->previousView;
        $this->previousView = null;
    }

    /**
     * Dijalankan saat komponen pertama kali dimuat.
     * Memuat progres dari session jika ada.
     */
    public function mount()
    {
        $progress = session('game_progress', []);
        $this->gameProgress = $progress;

        if (!empty($progress) && isset($progress['unlockedLevel'])) {
            $this->unlockedLevel = $progress['unlockedLevel'];
        } else {
            $this->currentView = 'home';
        }

        $this->currentView = 'start_sequence';
        // $this->currentLevel = '4';
    }

    /**
     * Memulai permainan baru dari awal.
     * Menghapus semua progres yang tersimpan di session dan local storage.
     */
    public function startNewGame()
    {
        session()->forget('game_progress');
        $this->dispatch('clear-local-storage');
        $this->unlockedLevel = 1;
        $this->gameProgress = [];
        $this->currentView = 'start_sequence';
        $this->currentLevel = null;
    }

    /**
     * Melanjutkan permainan dari progres yang tersimpan.
     * Logika pemuatan progres sudah ditangani di `mount()`.
     *
     * @param array $progress Data progres (saat ini tidak digunakan, tapi bisa dikembangkan).
     */
    public function continueGame(array $progress)
    {
        // Memuat progres dari session jika ada
        $progress = session('game_progress', []);
        $this->gameProgress = $progress;

        if (!empty($progress) && isset($progress['unlockedLevel'])) {
            $this->unlockedLevel = $progress['unlockedLevel'];
        }

        $this->showPetaMisi();
    }

    /**
     * Menyimpan progres saat ini ke session dan memicu penyimpanan ke local storage.
     */
    private function saveProgressToSession()
    {
        $currentProgress = session('game_progress', []);
        $currentProgress['unlockedLevel'] = $this->unlockedLevel;
        session(['game_progress' => $currentProgress]);
        $this->dispatch('save-progress-to-local-storage', progress: $currentProgress);
    }

    /**
     * Menampilkan halaman Peta Misi.
     */
    public function showPetaMisi()
    {
        $this->currentView = 'peta_misi';
        $this->currentLevel = null;
        $this->saveProgressToSession();
    }

    /**
     * Masuk ke dalam sebuah level permainan.
     *
     * @param int $level ID level yang akan dimainkan.
     */
    public function enterLevel(int $level)
    {
        if ($level <= $this->unlockedLevel) {
            $this->currentLevel = $level;
            $this->currentView = 'level';
            $this->saveProgressToSession();
        }
    }

    /**
     * Menampilkan halaman utama (home screen).
     */
    public function showHome()
    {
        $this->currentView = 'home';
    }

    /**
     * Merender tampilan komponen berdasarkan `$currentView`.
     */
    public function render()
    {
        return view('livewire.game-manager');
    }
}
