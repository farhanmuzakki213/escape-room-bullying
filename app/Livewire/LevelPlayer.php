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
    public array $questionMap = [];

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
    public int $totalQuestions = 0;

    /**
     * Properti STATE untuk mengelola alur di dalam level.
     * Pilihan state: 'rules_popup', 'rules_background', 'playing', 'level_complete_popup'.
     */
    public string $viewState = 'rules_popup';

    public $matchingGameItems = [];
    public $selectedImage = null;
    public $selectedText = null;
    public $correctPairs = [];
    public ?string $currentGameTitle = null;
    public array $currentGameCorrectKeys = [];

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
                ],
                'colokan' => [
                    'image' => 'images/level1/colokan.png',
                    'alt' => 'Colokan',
                    'style' => 'top:63%; left: 39%; width: 6%;',
                ],
                'lukisan' => [
                    'image' => 'images/level1/lukisan-biru.png',
                    'alt' => 'Lukisan',
                    'style' => 'top: 28%; left: 25%; width: 10%;',
                ],
                'penggaris' => [
                    'image' => 'images/level1/penggaris.png',
                    'alt' => 'Penggaris',
                    'style' => 'top: 90%; left: 25%; width: 10%;',
                ],
            ],
            'questions' => [
                'q1' => [
                    'image' => 'images/pertanyaan-jawaban/p1-lv1.svg',
                    'options' => [
                        'a' => 'images/pertanyaan-jawaban/a-p1-lv1.svg',
                        'b' => 'images/pertanyaan-jawaban/b-p1-lv1.svg',
                        'c' => 'images/pertanyaan-jawaban/c-p1-lv1.svg',
                    ],
                    'correct_answer' => 'b'
                ],
                'q2' => [
                    'image' => 'images/pertanyaan-jawaban/p2-lv1.svg',
                    'options' => [
                        'a' => 'images/pertanyaan-jawaban/a-p2-lv1.svg',
                        'b' => 'images/pertanyaan-jawaban/b-p2-lv1.svg',
                        'c' => 'images/pertanyaan-jawaban/c-p2-lv1.svg',
                    ],
                    'correct_answer' => 'a'
                ],
                'q3' => [
                    'image' => 'images/pertanyaan-jawaban/p3-lv1.svg',
                    'options' => [
                        'a' => 'images/pertanyaan-jawaban/a-p3-lv1.svg',
                        'b' => 'images/pertanyaan-jawaban/b-p3-lv1.svg',
                        'c' => 'images/pertanyaan-jawaban/c-p3-lv1.svg',
                    ],
                    'correct_answer' => 'b'
                ]
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
                ],
                'papan-library' => [
                    'image' => 'images/level2/papan-library.png',
                    'alt' => 'Papan Library',
                    'style' => 'top: 35%; left: 50%; width: 8%;',
                ],
                'tempat-sampah' => [
                    'image' => 'images/level2/tempat-sampah.png',
                    'alt' => 'Tempat Sampah',
                    'style' => 'top: 67%; left: 76%; width: 8%;',
                ],
                'vas' => [
                    'image' => 'images/level2/vas.png',
                    'alt' => 'Vas',
                    'style' => 'top: 76%; left: 33%; width: 7%;',
                ],
            ],
            'questions' => [
                'q1' => [
                    'image' => 'images/pertanyaan-jawaban/p1-lv2.svg',
                    'correct_pairs' => [
                        'verbal_1' => ['image' => 'images/pertanyaan-jawaban/verbal-1.svg', 'text' => 'Mengejek, mempermalukan, dan menghina seseorang dengan sengaja'],
                        'verbal_2' => ['image' => 'images/pertanyaan-jawaban/verbal-2.svg', 'text' => 'Memanggil nama-nama kasar, atau mengatakan hal-hal menyakitkan dengan sengaja.'],
                    ],
                ],
                'q2' => [
                    'image' => 'images/pertanyaan-jawaban/p2-lv2.svg',
                    'correct_pairs' => [
                        'fisik_1' => ['image' => 'images/pertanyaan-jawaban/fisik-1.svg', 'text' => 'menjambak dan mendorong seseorang dengan segaja'],
                        'fisik_2' => ['image' => 'images/pertanyaan-jawaban/fisik-2.svg', 'text' => 'Mendorong, memukul, atau melakukan kekerasan fisik lainnya'],
                    ],
                ],
                'q3' => [
                    'image' => 'images/pertanyaan-jawaban/p3-lv2.svg',
                    'correct_pairs' => [
                        'sosial_1' => ['image' => 'images/pertanyaan-jawaban/sosial-1.svg', 'text' => 'Merusak reputasi sosial korban dan membuat lelucon yang merendahkan seseorang '],
                        'sosial_2' => ['image' => 'images/pertanyaan-jawaban/sosial-2.svg', 'text' => 'Menyebarkan rumor, menghasut seseorang, dan mengucilkan seseorang dari kelompok'],
                    ],
                ],
                'q4' => [
                    'image' => 'images/pertanyaan-jawaban/p4-lv2.svg',
                    'correct_pairs' => [
                        'cyber_1' => ['image' => 'images/pertanyaan-jawaban/cyber-1.svg', 'text' => 'Membuat komentar jahat di media sosial dan menyebarkan foto-foto seseorang tanpa izin'],
                        'cyber_2' => ['image' => 'images/pertanyaan-jawaban/cyber-2.svg', 'text' => 'Meneror seseorang melalui media sosial dan menyerang seseorang secara online'],
                    ],
                ]
            ],
            'item_bank' => [
                // Ini adalah gabungan semua 'correct_pairs' dari atas
                'verbal_1' => ['image' => 'images/pertanyaan-jawaban/verbal-1.svg', 'text' => 'Mengejek, mempermalukan, dan menghina seseorang dengan sengaja'],
                'verbal_2' => ['image' => 'images/pertanyaan-jawaban/verbal-2.svg', 'text' => 'Memanggil nama-nama kasar, atau mengatakan hal-hal menyakitkan dengan sengaja.'],
                'fisik_1' => ['image' => 'images/pertanyaan-jawaban/fisik-1.svg', 'text' => 'menjambak dan mendorong seseorang dengan segaja'],
                'fisik_2' => ['image' => 'images/pertanyaan-jawaban/fisik-2.svg', 'text' => 'Mendorong, memukul, atau melakukan kekerasan fisik lainnya'],
                'sosial_1' => ['image' => 'images/pertanyaan-jawaban/sosial-1.svg', 'text' => 'Merusak reputasi sosial korban dan membuat lelucon yang merendahkan seseorang '],
                'sosial_2' => ['image' => 'images/pertanyaan-jawaban/sosial-2.svg', 'text' => 'Menyebarkan rumor, menghasut seseorang, dan mengucilkan seseorang dari kelompok'],
                'cyber_1' => ['image' => 'images/pertanyaan-jawaban/cyber-1.svg', 'text' => 'Membuat komentar jahat di media sosial dan menyebarkan foto-foto seseorang tanpa izin'],
                'cyber_2' => ['image' => 'images/pertanyaan-jawaban/cyber-2.svg', 'text' => 'Meneror seseorang melalui media sosial dan menyerang seseorang secara online'],
            ],
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
                ],
                'bola-basket' => [
                    'image' => 'images/level3/bola-basket.png',
                    'alt' => 'Bola Basket',
                    'style' => 'top: 67%; left: 54%; width: 3%;',
                ],
                'jam-dinding' => [
                    'image' => 'images/level3/jam-dinding.png',
                    'alt' => 'Jam Dinding',
                    'style' => 'top: 40%; left: 28%; width: 5%;',
                ],
                'pensil-berjatuhan' => [
                    'image' => 'images/level3/pensil-berjatuhan.png',
                    'alt' => 'Pensil Berjatuhan',
                    'style' => 'top: 85%; left: 32%; width: 8%;',
                ],
            ],
            'questions' => [
                'q1' => [
                    'image' => 'images/pertanyaan-jawaban/p1-lv3.svg',
                    'options' => [
                        'a' => 'images/pertanyaan-jawaban/a-p1-lv3.svg',
                        'b' => 'images/pertanyaan-jawaban/b-p1-lv3.svg',
                        'c' => 'images/pertanyaan-jawaban/c-p1-lv3.svg',
                    ],
                    'correct_answer' => 'a'
                ],
                'q2' => [
                    'image' => 'images/pertanyaan-jawaban/p2-lv3.svg',
                    'options' => [
                        'a' => 'images/pertanyaan-jawaban/a-p2-lv3.svg',
                        'b' => 'images/pertanyaan-jawaban/b-p2-lv3.svg',
                        'c' => 'images/pertanyaan-jawaban/c-p2-lv3.svg',
                    ],
                    'correct_answer' => 'b'
                ],
                'q3' => [
                    'image' => 'images/pertanyaan-jawaban/p3-lv3.svg',
                    'options' => [
                        'a' => 'images/pertanyaan-jawaban/a-p3-lv3.svg',
                        'b' => 'images/pertanyaan-jawaban/b-p3-lv3.svg',
                        'c' => 'images/pertanyaan-jawaban/c-p3-lv3.svg',
                    ],
                    'correct_answer' => 'c'
                ],
                'q4' => [
                    'image' => 'images/pertanyaan-jawaban/p4-lv3.svg',
                    'options' => [
                        'a' => 'images/pertanyaan-jawaban/a-p4-lv3.svg',
                        'b' => 'images/pertanyaan-jawaban/b-p4-lv3.svg',
                        'c' => 'images/pertanyaan-jawaban/c-p4-lv3.svg',
                    ],
                    'correct_answer' => 'a'
                ]
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
                ],
                'kursi' => [
                    'image' => 'images/level4/kursi.png',
                    'alt' => 'Kursi',
                    'style' => 'top: 70%; left: 11%; width: 20%;',
                ],
                'pohon' => [
                    'image' => 'images/level4/pohon.png',
                    'alt' => 'Pohon',
                    'style' => 'top: 45%; left: 10%; width: 25%;',
                ],
                'tempat-sampah' => [
                    'image' => 'images/level4/tempat-sampah.png',
                    'alt' => 'Tempat Sampah',
                    'style' => 'top: 66%; left: 78%; width: 14%;',
                ],
                'bendera' => [
                    'image' => 'images/level4/bendera.png',
                    'alt' => 'Bendera',
                    'style' => 'top: 50%; left: 55%; width: 15%;',
                ],
                'bola-basket' => [
                    'image' => 'images/level3/bola-basket.png',
                    'alt' => 'Bambu',
                    'style' => 'top: 85%; left: 85%; width: 5%;',
                ],
            ],
            'questions' => [
                'q1' => [
                    'id' => 1,
                    'image' => 'images/pertanyaan-jawaban/p1-lv4.svg',
                    'answer' => 'konseling'
                ],
                'q2' => [
                    'id' => 2,
                    'image' => 'images/pertanyaan-jawaban/p2-lv4.svg',
                    'answer' => 'dukungan'
                ],
                'q3' => [
                    'id' => 3,
                    'image' => 'images/pertanyaan-jawaban/p3-lv4.svg',
                    'answer' => 'komunikasi'
                ],
                'q4' => [
                    'id' => 4,
                    'image' => 'images/pertanyaan-jawaban/p4-lv4.svg',
                    'answer' => 'melaporkan'
                ],
                'q5' => [
                    'id' => 5,
                    'image' => 'images/pertanyaan-jawaban/p5-lv4.svg',
                    'answer' => 'masyarakat'
                ],
                'q6' => [
                    'id' => 6,
                    'image' => 'images/pertanyaan-jawaban/p6-lv4.svg',
                    'answer' => 'sekolah'
                ]
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

        $progress = session('game_progress', []);
        if (!isset($progress[$this->levelId]['question_map'])) {
            $objectKeys = array_keys($this->levelConfig['objects']);
            $questionKeys = array_keys($this->levelConfig['questions']);

            shuffle($objectKeys);
            shuffle($questionKeys);

            $objectsForQuestions = array_slice($objectKeys, 0, count($questionKeys));

            if (!empty($objectsForQuestions)) {
                $this->questionMap = array_combine($objectsForQuestions, $questionKeys);
            }

            $progress[$this->levelId]['question_map'] = $this->questionMap;
            session(['game_progress' => $progress]);
        } else {
            $this->questionMap = $progress[$this->levelId]['question_map'];
        }

        $this->answeredObjects = $progress[$this->levelId]['answered_objects'] ?? [];
        $this->filledAnswers = $progress[$this->levelId]['filled_answers'] ?? [];

        if ($this->levelId === 2) {
            $this->totalQuestions = count($this->levelConfig['objects']);
        } elseif ($this->levelId === 4) {
            $this->initializeTts();
            $this->totalQuestions = count($this->levelConfig['questions']);
        } else {
            $this->totalQuestions = count($this->levelConfig['questions']);
        }

        $answeredCount = count($this->answeredObjects);
        if ($answeredCount === 0) {
            $this->viewState = 'rules_popup';
        } elseif ($answeredCount >= $this->totalQuestions) {
            $this->viewState = 'level_complete_popup';
        } else {
            $this->viewState = 'playing';
        }

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

        // ### LOGIKA BARU: Ambil pertanyaan dari peta acak ###
        // Cek apakah ada pertanyaan yang dipetakan ke objek ini
        if (isset($this->questionMap[$objectName])) {
            $questionKey = $this->questionMap[$objectName];
            $this->currentQuestion = $this->levelConfig['questions'][$questionKey];

            // Tambahkan 'answer' jika tidak ada, khusus untuk level 4
            if ($this->levelId === 4 && !isset($this->currentQuestion['answer'])) {
                $this->currentQuestion['answer'] = $this->levelConfig['questions'][$questionKey]['answer'];
            }

            if ($this->levelId == 2) {
                $this->setupMatchingGameForQuestion($this->currentQuestion);
            }

            $this->activeObjectName = $objectName;
            $this->showQuestionModal = true;
            $this->userAnswer = '';
            $this->feedbackMessage = null;
        }
    }

    /**
     * (BARU) Menyiapkan data untuk mini-game mencocokkan.
     */
    public function setupMatchingGameForQuestion(array $questionData)
    {
        $allCorrectPairs = $questionData['correct_pairs'];
        $allCorrectKeys = array_keys($allCorrectPairs);

        // 2. Tentukan secara acak berapa banyak pasangan yang harus ditemukan (1 atau 2).
        // Pastikan tidak mencoba mengambil lebih dari yang tersedia.
        $maxPairsToFind = min(2, count($allCorrectKeys));
        $numPairsToFind = rand(1, $maxPairsToFind);

        // 3. Acak kunci jawaban dan ambil sejumlah yang ditentukan.
        shuffle($allCorrectKeys);
        $selectedCorrectKeys = array_slice($allCorrectKeys, 0, $numPairsToFind);
        $this->currentGameCorrectKeys = $selectedCorrectKeys; // Ini akan menjadi target kemenangan.

        // 4. Buat array pasangan benar yang akan digunakan di game kali ini.
        $correctPairsForGame = [];
        foreach ($selectedCorrectKeys as $key) {
            $correctPairsForGame[$key] = $allCorrectPairs[$key];
        }

        // 5. Hitung berapa banyak pengecoh yang dibutuhkan agar total item tetap konsisten (misal: 4).
        $poolSize = 4;
        $numDistractors = $poolSize - $numPairsToFind;

        $allItems = $this->levelConfig['item_bank'];

        // Dapatkan kunci item yang BUKAN merupakan jawaban benar yang telah dipilih.
        $distractorKeys = array_diff(array_keys($allItems), $this->currentGameCorrectKeys);
        shuffle($distractorKeys);

        // Ambil pengecoh berdasarkan jumlah yang sudah dihitung.
        $distractors = [];
        foreach (array_slice($distractorKeys, 0, $numDistractors) as $key) {
            $distractors[$key] = $allItems[$key];
        }

        // Gabungkan pasangan yang benar (yang sudah dipilih acak) dengan pengecoh.
        $gamePool = array_merge($correctPairsForGame, $distractors);

        // Pisahkan gambar dan teks untuk ditampilkan.
        $images = [];
        $texts = [];
        foreach ($gamePool as $key => $item) {
            $images[$key] = $item['image'];
            $texts[$key] = $item['text'];
        }

        // Acak urutan gambar.
        $shuffledImageKeys = array_keys($images);
        shuffle($shuffledImageKeys);
        $shuffledImages = [];
        foreach ($shuffledImageKeys as $key) {
            $shuffledImages[$key] = $images[$key];
        }

        // Acak urutan teks.
        $shuffledTextKeys = array_keys($texts);
        shuffle($shuffledTextKeys);
        $shuffledTexts = [];
        foreach ($shuffledTextKeys as $key) {
            $shuffledTexts[$key] = $texts[$key];
        }

        // Kirim data yang sudah siap ke view.
        $this->matchingGameItems = [
            'images' => $shuffledImages,
            'texts' => $shuffledTexts,
        ];

        // Reset state mini-game
        $this->correctPairs = [];
        $this->selectedImage = null;
        $this->selectedText = null;
    }

    /**
     * (BARU) Dipanggil saat pemain mengklik gambar atau teks di mini-game.
     */
    public function selectItem($type, $key)
    {
        if ($type === 'image') {
            $this->selectedImage = $key;
        } elseif ($type === 'text') {
            $this->selectedText = $key;
        }

        if ($this->selectedImage && $this->selectedText) {
            $this->checkPair();
        }
    }

    /**
     * (BARU) Memeriksa apakah pasangan yang dipilih benar.
     */
    public function checkPair()
    {
        if ($this->selectedImage === $this->selectedText) {
            $selectedKey = $this->selectedImage;

            if (in_array($selectedKey, $this->currentGameCorrectKeys) && !in_array($selectedKey, $this->correctPairs)) {
                $this->correctPairs[] = $selectedKey;
                $this->dispatch('correct-answer');

                if (count($this->correctPairs) >= count($this->currentGameCorrectKeys)) {
                    if (!in_array($this->activeObjectName, $this->answeredObjects)) {
                        $this->answeredObjects[] = $this->activeObjectName;
                    }

                    $progress = session('game_progress', []);
                    $progress[$this->levelId]['answered_objects'] = $this->answeredObjects;
                    session(['game_progress' => $progress]);

                    $this->dispatch('show-notification', message: 'Hebat, kamu berhasil!', type: 'success');
                    sleep(1);
                    $this->closeModalAndCheckCompletion();
                }
            } else {
                $this->dispatch('incorrect-answer');
            }
        } else {
            $this->dispatch('incorrect-answer');
            $this->dispatch('show-notification', message: 'Pasangan kurang tepat, coba lagi.', type: 'error');
        }

        $this->selectedImage = null;
        $this->selectedText = null;
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

                // Simpan progres ke session
                $progress = session('game_progress', []);
                $progress[$this->levelId]['answered_objects'] = $this->answeredObjects;
                session(['game_progress' => $progress]);

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
        if (count($this->answeredObjects) >= $this->totalQuestions) {
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
            if (!in_array($this->activeObjectName, $this->answeredObjects)) {
                $this->answeredObjects[] = $this->activeObjectName;
                $this->filledAnswers[] = $correctAnswer;
            }

            // Simpan progres ke session
            $progress = session('game_progress', []);
            $progress[$this->levelId]['answered_objects'] = $this->answeredObjects;
            $progress[$this->levelId]['filled_answers'] = $this->filledAnswers;
            session(['game_progress' => $progress]);

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
