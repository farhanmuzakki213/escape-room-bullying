<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class LevelPlayer extends Component
{
    /**
     * Properti utama untuk konfigurasi level.
     */
    public int $levelId;
    public array $levelConfig;
    public string $backgroundUrl;
    public array $answeredObjects = [];

    /**
     * Properti untuk mengelola state Popup Soal.
     */
    public bool $showQuestionModal = false;
    public ?array $currentQuestion = null;
    public ?string $feedbackMessage = null;
    public ?string $activeObjectName = null;

    /**
     * Properti STATE untuk mengelola alur di dalam level.
     * Pilihan state: 'rules_popup', 'rules_background', 'playing', 'level_complete_popup'.
     */
    public string $viewState = 'rules_popup';

    /**
     * Konfigurasi data untuk semua level dalam game.
     */
    public array $levelData = [
        1 => [
            'background' => 'images/level1/background-level-1.svg',
            'title' => 'Ruang Kelas',
            'rules' => [
                'popup_text' => 'Halo, aku Arunika. Aku akan menemanimu menjelajah sekolah ini. Tapi pintu kelas terkunci! Untuk keluar, kita harus tahu dulu apa itu bullying, bagaimana cirinya, dan apa tujuan orang melakukannya.',
                'background_text' => 'Silahkan kamu klik benda-benda yang ada didalam kelas untuk mengetahuinya!',
                'completion_text' => 'Hebat! Sekarang kamu tahu kan bahwa bullying itu artinya perilaku menyakiti orang lain secara sengaja dan berulang, biasanya pada yang lebih lemah. Orang melakukan bullying biasanya karena ingin berkuasa, mencari perhatian, balas dendam, atau supaya diakui teman-temannya. Tapi semua itu bukan alasan yang benar, karena justru merugikan diri sendiri dan orang lain. Yuk, kita lanjut!'
            ],
            'assets' => [
                'rules_board' => 'images/petunjuk/papan-aturan.svg',
                'question_board' => 'images/petunjuk/papan-pertanyaan.svg',
                'rules_background' => 'images/petunjuk/background-rules.svg'
            ],
            'objects' => [
                'kipas-angin' => [
                    'image' => 'images/level1/kipas-angin.svg',
                    'alt' => 'Kipas Angin',
                    'style' => 'top: 5%; left: 50%; width: 10%;',
                    'question' => [
                        'text' => 'Jika suasana di kelas terasa "panas" karena ada ejekan, apa yang sebaiknya kamu lakukan untuk "mendinginkan" suasana?',
                        'options' => [
                            'A' => 'Ikut memanasi suasana agar seru',
                            'B' => 'Mengalihkan pembicaraan ke topik yang positif',
                            'C' => 'Menyalakan kipas angin sekeras-kerasnya',
                            'D' => 'Diam saja karena bukan urusanmu',
                        ],
                        'correct_answer' => 'B'
                    ]
                ],
                'lukisan' => [
                    'image' => 'images/level1/lukisan-biru.svg',
                    'alt' => 'Lukisan',
                    'style' => 'top: 25%; left: 25%; width: 10%;',
                    'question' => [
                        'text' => 'Perundungan bisa meninggalkan "bekas" yang tak terlihat seperti lukisan yang retak. Apa "bekas" yang paling mungkin dirasakan korban?',
                        'options' => [
                            'A' => 'Luka memar di badan',
                            'B' => 'Kehilangan uang jajan',
                            'C' => 'Rasa cemas dan tidak mau sekolah',
                            'D' => 'Pakaian yang sobek',
                        ],
                        'correct_answer' => 'C'
                    ]
                ],
                'colokan' => [
                    'image' => 'images/level1/colokan.svg',
                    'alt' => 'Colokan',
                    'style' => 'top:63%; left: 39%; width: 6%;',
                    'question' => [
                        'text' => 'Perkataan negatif bisa "menyetrum" semangat seseorang. Bagaimana cara terbaik memberikan "energi" positif pada teman yang jadi korban perundungan?',
                        'options' => [
                            'A' => 'Menceritakan masalahnya ke semua orang',
                            'B' => 'Menjauhinya agar tidak ikut jadi target',
                            'C' => 'Mengajaknya bicara dan mendengarkan ceritanya',
                            'D' => 'Menyuruhnya melawan balik dengan kekerasan',
                        ],
                        'correct_answer' => 'C'
                    ]
                ],
                'gunting' => [
                    'image' => 'images/level1/gunting.svg',
                    'alt' => 'Gunting',
                    'style' => 'top: 90%; left: 70%; width: 6%;',
                    'question' => [
                        'text' => 'Kata-kata tajam bisa "menggunting" perasaan seseorang. Manakah kalimat yang paling mungkin menyakiti perasaan teman?',
                        'options' => [
                            'A' => '"Menurutku gambarmu bisa lebih bagus lagi warnanya."',
                            'B' => '"Kamu lari lambat sekali, seperti siput!"',
                            'C' => '"Bolehkah aku pinjam pensilmu?"',
                            'D' => '"Terima kasih sudah membantuku."',
                        ],
                        'correct_answer' => 'B'
                    ]
                ],
            ]
        ],
        2 => ['background' => 'images/petamisi/background-peta-misi.svg', 'title' => 'Perpustakaan', 'objects' => []],
        3 => ['background' => 'images/petamisi/background-peta-misi.svg', 'title' => 'Lorong Sekolah', 'objects' => []],
        4 => ['background' => 'images/petamisi/background-peta-misi.svg', 'title' => 'Lapangan Sekolah', 'objects' => []],
    ];

    /**
     * Dijalankan saat komponen pertama kali dimuat.
     */
    public function mount()
    {
        $this->levelConfig = $this->levelData[$this->levelId];
        $this->backgroundUrl = asset($this->levelConfig['background']);

        // if (env('APP_ENV') == 'local') {
        //     $this->viewState = 'playing';
        // }
    }

    // --- METODE NAVIGASI ALUR ---

    public function showRulesBackground()
    {
        $this->viewState = 'rules_background';
    }

    public function startGameplay()
    {
        $this->viewState = 'playing';
    }

    public function backToRulesPopup()
    {
        $this->viewState = 'rules_popup';
    }

    public function backToPetaMisi()
    {
        $this->dispatch('backToPetaMisi');
    }

    // --- METODE LOGIKA GAME ---

    /**
     * Dipanggil saat objek interaktif di dalam game diklik.
     */
    public function objectClicked(string $objectName)
    {
        // Jangan tampilkan soal jika sudah pernah dijawab benar
        if (in_array($objectName, $this->answeredObjects)) {
            return;
        }

        if (isset($this->levelConfig['objects'][$objectName]['question'])) {
            $this->activeObjectName = $objectName;
            $this->currentQuestion = $this->levelConfig['objects'][$objectName]['question'];
            $this->showQuestionModal = true;
            $this->feedbackMessage = null;
        }
    }

    /**
     * Memproses pilihan jawaban dari pemain.
     */
    public function selectAnswer(string $selectedOption)
    {
        if ($this->currentQuestion) {
            if ($selectedOption == $this->currentQuestion['correct_answer']) {
                $this->feedbackMessage = "Jawabanmu Benar! 👍";
                if (!in_array($this->activeObjectName, $this->answeredObjects)) {
                    $this->answeredObjects[] = $this->activeObjectName;
                }
            } else {
                $this->feedbackMessage = "Jawabanmu kurang tepat. Perundungan adalah masalah serius, coba pikirkan lagi dampak dari setiap pilihan.";
            }
        }
    }

    /**
     * Menutup modal selesai dan langsung menuju peta misi.
     */
    public function completeLevelAndExit()
    {
        $this->dispatch('levelCompleted', $this->levelId);
        $this->dispatch('backToPetaMisi');
    }

    /**
     * Menutup modal pertanyaan dan langsung memeriksa apakah level sudah selesai.
     */
    public function closeModalAndCheckCompletion()
    {
        $this->closeModal();
        $this->checkLevelCompletion();
    }

    /**
     * Memeriksa apakah semua pertanyaan di level ini sudah dijawab dengan benar.
     */
    public function checkLevelCompletion()
    {
        $totalObjectsWithQuestions = count($this->levelConfig['objects']);
        if (count($this->answeredObjects) >= $totalObjectsWithQuestions) {
            $this->viewState = 'level_complete_popup';
        }
    }

    /**
     * Mereset state modal pertanyaan.
     */
    public function closeModal()
    {
        $this->showQuestionModal = false;
        $this->currentQuestion = null;
        $this->feedbackMessage = null;
        $this->activeObjectName = null;
    }

    /**
     * Merender view komponen.
     */
    public function render()
    {
        return view('livewire.level-player');
    }
}
