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

    public int $currentRulesPage = 0;
    public int $currentCompletionPage = 0;

    /**
     * Properti untuk teka teki silang.
     */
    public array $crosswordGrid = [];
    public int $rows = 0;
    public int $cols = 0;
    public array $clueNumbers = [];
    public array $wordData = [];
    public array $filledAnswers = [];
    public string $userAnswer = '';

    /**
     * Properti STATE untuk mengelola alur di dalam level.
     * Pilihan state: 'rules_popup', 'rules_background', 'playing', 'level_complete_popup'.
     */
    public string $viewState = 'rules_popup';

    /**
     * Properti baru untuk alur refleksi.
     */
    public int $currentReflectionPage = 1;
    public array $reflectionPages = [
        1 => 'images/petunjuk/background-refleksi-diri.jpg',
        2 => 'images/petunjuk/background-refleksi-verbal.jpg',
        3 => 'images/petunjuk/background-refleksi-fisik.jpg',
        4 => 'images/petunjuk/background-refleksi-relasional.jpg',
        5 => 'images/petunjuk/background-refleksi-cyberbullying.jpg',
    ];

    /**
     * Konfigurasi data untuk semua level dalam game.
     */
    public array $levelData = [
        1 => [
            'background' => 'images/level1/background-level-1.jpg',
            'title' => 'Ruang Kelas',
            'assets' => [
                'rules_boards' => [
                    'images/level1/papan-aturan-lv1.1.png',
                    'images/level1/papan-aturan-lv1.2.png',
                ],
                'completion_boards' => [
                    'images/level1/papan-selesai-lv1.1.png',
                    'images/level1/papan-selesai-lv1.2.png',
                ],
            ],
            'objects' => [
                'gunting' => [
                    'image' => 'images/level1/gunting.png',
                    'alt' => 'Gunting',
                    'style' => 'top: 90%; left: 70%; width: 6%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p1-lv1.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/a-p1-lv1.svg',
                            'b' => 'images/pertanyaan-jawaban/b-p1-lv1.svg',
                            'c' => 'images/pertanyaan-jawaban/c-p1-lv1.svg',
                        ],
                        'correct_answer' => 'b'
                    ]
                ],
                'colokan' => [
                    'image' => 'images/level1/colokan.png',
                    'alt' => 'Colokan',
                    'style' => 'top:63%; left: 39%; width: 6%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p2-lv1.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/a-p2-lv1.svg',
                            'b' => 'images/pertanyaan-jawaban/b-p2-lv1.svg',
                            'c' => 'images/pertanyaan-jawaban/c-p2-lv1.svg',
                        ],
                        'correct_answer' => 'a'
                    ]
                ],
                'lukisan' => [
                    'image' => 'images/level1/lukisan-biru.png',
                    'alt' => 'Lukisan',
                    'style' => 'top: 25%; left: 25%; width: 10%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p3-lv1.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/a-p3-lv1.svg',
                            'b' => 'images/pertanyaan-jawaban/b-p3-lv1.svg',
                            'c' => 'images/pertanyaan-jawaban/c-p3-lv1.svg',
                        ],
                        'correct_answer' => 'b'
                    ]
                ],
            ]
        ],
        2 => [
            'background' => 'images/level2/background-level-2.jpg',
            'title' => 'Perpustakaan',
            'assets' => [
                'rules_boards' => [
                    'images/level2/papan-aturan-lv2.1.png',
                ],
                'completion_boards' => [
                    'images/level2/papan-selesai-lv2.1.png',
                    'images/level2/papan-selesai-lv2.2.png',
                ],
            ],
            'objects' => [
                'ac' => [
                    'image' => 'images/level2/ac.png',
                    'alt' => 'AC',
                    'style' => 'top: 20%; left: 60%; width: 10%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p1-lv2.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/gambar-a.svg',
                            'b' => 'images/pertanyaan-jawaban/gambar-b.svg',
                            'c' => 'images/pertanyaan-jawaban/gambar-c.svg',
                            'd' => 'images/pertanyaan-jawaban/gambar-d.svg',
                        ],
                        'correct_answer' => 'c'
                    ]
                ],
                'papan-library' => [
                    'image' => 'images/level2/papan-library.png',
                    'alt' => 'Papan Library',
                    'style' => 'top: 35%; left: 50%; width: 8%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p2-lv2.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/gambar-a.svg',
                            'b' => 'images/pertanyaan-jawaban/gambar-b.svg',
                            'c' => 'images/pertanyaan-jawaban/gambar-c.svg',
                            'd' => 'images/pertanyaan-jawaban/gambar-d.svg',
                        ],
                        'correct_answer' => 'b'
                    ]
                ],
                'tempat-sampah' => [
                    'image' => 'images/level2/tempat-sampah.png',
                    'alt' => 'Tempat Sampah',
                    'style' => 'top: 67%; left: 76%; width: 8%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p3-lv2.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/gambar-a.svg',
                            'b' => 'images/pertanyaan-jawaban/gambar-b.svg',
                            'c' => 'images/pertanyaan-jawaban/gambar-c.svg',
                            'd' => 'images/pertanyaan-jawaban/gambar-d.svg',
                        ],
                        'correct_answer' => 'a'
                    ]
                ],
                'vas' => [
                    'image' => 'images/level2/vas.png',
                    'alt' => 'Vas',
                    'style' => 'top: 76%; left: 33%; width: 7%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p4-lv2.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/gambar-a.svg',
                            'b' => 'images/pertanyaan-jawaban/gambar-b.svg',
                            'c' => 'images/pertanyaan-jawaban/gambar-c.svg',
                            'd' => 'images/pertanyaan-jawaban/gambar-d.svg',
                        ],
                        'correct_answer' => 'd'
                    ]
                ],
            ]
        ],
        3 => [
            'background' => 'images/level3/background-level-3.jpg',
            'title' => 'Lorong Sekolah',
            'assets' => [
                'rules_boards' => [
                    'images/level3/papan-aturan-lv3.1.png',
                    'images/level3/papan-aturan-lv3.2.png',
                ],
                'completion_boards' => [
                    'images/level3/papan-selesai-lv3.1.png',
                    'images/level3/papan-selesai-lv3.2.png',
                ],
            ],
            'objects' => [
                'mading' => [
                    'image' => 'images/level3/mading.png',
                    'alt' => 'Mading',
                    'style' => 'top: 48%; left: 44.5%; width: 10%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p1-lv3.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/a-p1-lv3.svg',
                            'b' => 'images/pertanyaan-jawaban/b-p1-lv3.svg',
                            'c' => 'images/pertanyaan-jawaban/c-p1-lv3.svg',
                        ],
                        'correct_answer' => 'a'
                    ]
                ],
                'bola-basket' => [
                    'image' => 'images/level3/bola-basket.png',
                    'alt' => 'Bola Basket',
                    'style' => 'top: 67%; left: 54%; width: 3%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p2-lv3.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/a-p2-lv3.svg',
                            'b' => 'images/pertanyaan-jawaban/b-p2-lv3.svg',
                            'c' => 'images/pertanyaan-jawaban/c-p2-lv3.svg',
                        ],
                        'correct_answer' => 'b'
                    ]
                ],
                'jam-dinding' => [
                    'image' => 'images/level3/jam-dinding.png',
                    'alt' => 'Jam Dinding',
                    'style' => 'top: 40%; left: 28%; width: 5%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p3-lv3.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/a-p3-lv3.svg',
                            'b' => 'images/pertanyaan-jawaban/b-p3-lv3.svg',
                            'c' => 'images/pertanyaan-jawaban/c-p3-lv3.svg',
                        ],
                        'correct_answer' => 'c'
                    ]
                ],
                'pensil-berjatuhan' => [
                    'image' => 'images/level3/pensil-berjatuhan.png',
                    'alt' => 'Pensil Berjatuhan',
                    'style' => 'top: 85%; left: 32%; width: 8%;',
                    'question' => [
                        'image' => 'images/pertanyaan-jawaban/p4-lv3.svg',
                        'options' => [
                            'a' => 'images/pertanyaan-jawaban/a-p4-lv3.svg',
                            'b' => 'images/pertanyaan-jawaban/b-p4-lv3.svg',
                            'c' => 'images/pertanyaan-jawaban/c-p4-lv3.svg',
                        ],
                        'correct_answer' => 'a'
                    ]
                ],
            ]
        ],
        4 => [
            'background' => 'images/level4/background-level-4.jpg',
            'title' => 'Lapangan Sekolah',
            'assets' => [
                'rules_boards' => [
                    'images/level4/papan-aturan-lv4.1.png',
                    'images/level4/papan-aturan-lv4.2.png',
                ],
                'completion_boards' => [
                    'images/level4/papan-selesai-lv4.1.png',
                    'images/level4/papan-selesai-lv4.2.png',
                ],
            ],
            'objects' => [
                'kertas-berjatuhan' => [
                    'image' => 'images/level4/kertas-berjatuhan.png',
                    'alt' => 'Kertas Berjatuhan',
                    'style' => 'top: 90%; left: 20%; width: 8%;',
                    'question' => [
                        'id' => 1,
                        'image' => 'images/pertanyaan-jawaban/p1-lv4.svg',
                        'answer' => 'konseling'
                    ]
                ],
                'kursi' => [
                    'image' => 'images/level4/kursi.png',
                    'alt' => 'Kursi',
                    'style' => 'top: 70%; left: 11%; width: 20%;',
                    'question' => [
                        'id' => 2,
                        'image' => 'images/pertanyaan-jawaban/p2-lv4.svg',
                        'answer' => 'dukungan'
                    ]
                ],
                'pohon' => [
                    'image' => 'images/level4/pohon.png',
                    'alt' => 'Pohon',
                    'style' => 'top: 45%; left: 10%; width: 25%;',
                    'question' => [
                        'id' => 3,
                        'image' => 'images/pertanyaan-jawaban/p3-lv4.svg',
                        'answer' => 'komunikasi'
                    ]
                ],
                'tempat-sampah' => [
                    'image' => 'images/level4/tempat-sampah.png',
                    'alt' => 'Tempat Sampah',
                    'style' => 'top: 66%; left: 78%; width: 14%;',
                    'question' => [
                        'id' => 4,
                        'image' => 'images/pertanyaan-jawaban/p4-lv4.svg',
                        'answer' => 'melaporkan'
                    ]
                ],
                'bendera' => [
                    'image' => 'images/level4/bendera.png',
                    'alt' => 'Bendera',
                    'style' => 'top: 50%; left: 55%; width: 15%;',
                    'question' => [
                        'id' => 5,
                        'image' => 'images/pertanyaan-jawaban/p5-lv4.svg',
                        'answer' => 'masyarakat'
                    ]
                ],
                'bambu' => [
                    'image' => 'images/level3/bola-basket.png',
                    'alt' => 'Bambu',
                    'style' => 'top: 85%; left: 85%; width: 5%;',
                    'question' => [
                        'id' => 6,
                        'image' => 'images/pertanyaan-jawaban/p6-lv4.svg',
                        'answer' => 'sekolah'
                    ]
                ],
            ]
        ],
    ];

    /**
     * Dijalankan saat komponen pertama kali dimuat.
     */
    public function mount()
    {
        $this->levelConfig = $this->levelData[$this->levelId];
        $this->backgroundUrl = asset($this->levelConfig['background']);

        if (env('APP_ENV') == 'local') {
            $this->viewState = 'reflection';
        }

        if ($this->levelId === 4) {
            $this->initializeTts();
        }
    }

    // --- METODE NAVIGASI ALUR ---

    public function showRulesBackground()
    {
        $this->viewState = 'rules_background';
    }

    public function startGameplay()
    {
        $this->viewState = 'playing';
        $this->currentRulesPage = 0;
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
        if (in_array($objectName, $this->answeredObjects)) {
            return;
        }

        if (isset($this->levelConfig['objects'][$objectName]['question'])) {
            $this->activeObjectName = $objectName;
            $this->currentQuestion = $this->levelConfig['objects'][$objectName]['question'];
            $this->showQuestionModal = true;
            $this->userAnswer = '';
            $this->feedbackMessage = null;
        }
    }

    /**
     * Memproses pilihan jawaban dari pemain.
     */
    public function selectAnswer(string $selectedOption)
    {
        if ($this->levelId === 4) return;

        if ($this->currentQuestion) {
            if ($selectedOption == $this->currentQuestion['correct_answer']) {
                if (!in_array($this->activeObjectName, $this->answeredObjects)) {
                    $this->answeredObjects[] = $this->activeObjectName;
                }
                $this->dispatch('correct-answer');
                $this->dispatch('show-notification', message: 'Jawabanmu Benar! 👍', type: 'success');
                $this->feedbackMessage = 'Benar';
            } else {
                $this->dispatch('incorrect-answer');
                $this->dispatch('show-notification', message: 'Jawabanmu kurang tepat. Coba pikirkan lagi.', type: 'error');
                $this->feedbackMessage = 'Salah';
            }
        }
    }

    /**
     * Menutup modal selesai dan langsung menuju peta misi.
     */
    public function completeLevelAndExit()
    {
        if ($this->levelId === 4) {
            $this->viewState = 'reflection';
            $this->currentReflectionPage = 1;
        } else {
            $this->dispatch('levelCompleted', $this->levelId);
            $this->dispatch('backToPetaMisi');
        }
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
        $answeredCount = ($this->levelId === 4) ? count($this->filledAnswers) : count($this->answeredObjects);
        if ($answeredCount >= $totalObjectsWithQuestions) {
            $this->viewState = 'level_complete_popup';
            $this->currentCompletionPage = 0;
        }
    }

    /**
     * Mereset state modal pertanyaan.
     */
    public function closeModal()
    {
        $this->showQuestionModal = false;
        $this->currentQuestion = null;
        $this->activeObjectName = null;
        $this->userAnswer = '';
        $this->feedbackMessage = null;
    }


    public function showNextCompletionPage()
    {
        $completionBoards = $this->levelConfig['assets']['completion_boards'];
        if ($this->currentCompletionPage < count($completionBoards) - 1) {
            $this->currentCompletionPage++;
        }
    }

    public function showPreviousCompletionPage()
    {
        if ($this->currentCompletionPage > 0) {
            $this->currentCompletionPage--;
        }
    }

    public function showNextRule()
    {
        $rulesBoards = $this->levelConfig['assets']['rules_boards'];
        if ($this->currentRulesPage < count($rulesBoards) - 1) {
            $this->currentRulesPage++;
        } else {
            $this->startGameplay();
        }
    }

    public function showPreviousRule()
    {
        if ($this->currentRulesPage > 0) {
            $this->currentRulesPage--;
        }
    }

    /**
     * **NEW:** Navigasi ke halaman refleksi berikutnya.
     */
    public function nextReflectionPage()
    {
        if ($this->currentReflectionPage < count($this->reflectionPages)) {
            $this->currentReflectionPage++;
        } else {
            // Setelah halaman terakhir, kembali ke peta misi
            $this->dispatch('levelCompleted', $this->levelId);
            $this->dispatch('backToPetaMisi');
        }
    }

    /**
     * **NEW:** Navigasi ke halaman refleksi sebelumnya.
     */
    public function previousReflectionPage()
    {
        if ($this->currentReflectionPage > 1) {
            $this->currentReflectionPage--;
        }
    }

    public function initializeTts()
    {
        $words = [
            ['word' => 'konseling',  'pos' => [9, 0], 'dir' => 'H', 'clueNo' => 1],
            ['word' => 'dukungan',   'pos' => [2, 2],  'dir' => 'V', 'clueNo' => 2],
            ['word' => 'komunikasi', 'pos' => [0, 6],  'dir' => 'V', 'clueNo' => 3],
            ['word' => 'melaporkan', 'pos' => [2, 6],  'dir' => 'H', 'clueNo' => 4],
            ['word' => 'masyarakat', 'pos' => [7, 5],  'dir' => 'H', 'clueNo' => 5],
            ['word' => 'sekolah',    'pos' => [0, 4],  'dir' => 'H', 'clueNo' => 6],
        ];

        // Simpan data kata untuk digunakan nanti saat mengisi jawaban
        foreach ($words as $w) {
            $this->wordData[strtolower($w['word'])] = $w;
        }

        // Tentukan ukuran grid secara dinamis
        $maxRow = 0;
        $maxCol = 0;
        foreach ($words as $w) {
            $r = $w['pos'][0];
            $c = $w['pos'][1];
            $len = strlen($w['word']);
            if ($w['dir'] === 'H') {
                $maxRow = max($maxRow, $r);
                $maxCol = max($maxCol, $c + $len - 1);
            } else { // 'V'
                $maxRow = max($maxRow, $r + $len - 1);
                $maxCol = max($maxCol, $c);
            }
        }
        $this->rows = $maxRow + 1;
        $this->cols = $maxCol + 1;

        // Inisialisasi grid: null = area kosong, '' = kotak huruf yang belum diisi
        $grid = array_fill(0, $this->rows, array_fill(0, $this->cols, null));

        foreach ($words as $w) {
            $r_start = $w['pos'][0];
            $c_start = $w['pos'][1];
            $this->clueNumbers["{$r_start}_{$c_start}"] = $w['clueNo'];

            for ($k = 0; $k < strlen($w['word']); $k++) {
                if ($w['dir'] === 'H') {
                    $grid[$r_start][$c_start + $k] = ''; // Kotak kosong
                } else { // 'V'
                    $grid[$r_start + $k][$c_start] = ''; // Kotak kosong
                }
            }
        }

        // soal 1
        $grid[9][0] = 'K';
        $grid[9][3] = 'S';
        $grid[9][6] = 'I';

        // soal 6
        $grid[0][4] = 'S';
        $grid[0][10] = 'H';
        $grid[0][7] = 'O';

        // soal 2
        $grid[2][2] = 'D';
        $grid[4][2] = 'K';
        $grid[6][2] = 'N';

        // soal 3
        $grid[7][5] = 'M';
        $grid[7][7] = 'S';
        $grid[7][13] = 'A';

        // soal 4
        $grid[2][6] = 'M';
        $grid[2][9] = 'A';
        $grid[2][14] = 'N';

        $this->crosswordGrid = $grid;
    }

    public function submitTtsAnswer()
    {
        if (!$this->currentQuestion) return;

        $correctAnswer = strtolower($this->currentQuestion['answer']);
        $submittedAnswer = strtolower(trim($this->userAnswer));

        if ($submittedAnswer === $correctAnswer) {
            if (!in_array($correctAnswer, $this->filledAnswers)) {
                $this->filledAnswers[] = $correctAnswer;
            }
            $this->fillWordInGrid($correctAnswer);
            $this->dispatch('correct-answer');
            $this->dispatch('show-notification', message: 'Jawabanmu Benar! 👍', type: 'success');
            $this->feedbackMessage = 'Benar';
        } else {
            $this->dispatch('incorrect-answer');
            $this->dispatch('show-notification', message: 'Jawabanmu kurang tepat. Coba pikirkan lagi.', type: 'error');
        }
    }

    public function fillWordInGrid(string $word)
    {
        $word = strtolower($word);
        if (!isset($this->wordData[$word])) return;

        $data = $this->wordData[$word];
        $letters = str_split($word);
        [$row, $col] = $data['pos'];

        foreach ($letters as $i => $letter) {
            if ($data['dir'] === 'H') {
                $this->crosswordGrid[$row][$col + $i] = strtoupper($letter);
            } else { // 'V'
                $this->crosswordGrid[$row + $i][$col] = strtoupper($letter);
            }
        }
    }



    /**
     * Merender view komponen.
     */
    public function render()
    {
        return view('livewire.level-player');
    }
}
