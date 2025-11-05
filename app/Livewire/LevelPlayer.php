<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.guest')]
class LevelPlayer extends Component
{
    /**
     * ID level yang sedang dimainkan.
     */
    public int $levelId;

    /**
     * Konfigurasi spesifik untuk level saat ini, diambil dari $levelData.
     */
    public array $levelConfig;

    /**
     * URL gambar latar belakang untuk level saat ini.
     */
    public string $backgroundUrl;

    /**
     * Menyimpan daftar nama objek yang pertanyaannya sudah dijawab dengan benar.
     */
    public array $answeredObjects = [];

    /**
     * Memetakan objek interaktif ke pertanyaan secara acak untuk setiap sesi game.
     */
    public array $questionMap = [];

    /**
     * Mengontrol visibilitas modal/popup pertanyaan.
     */
    public bool $showQuestionModal = false;

    /**
     * Data pertanyaan yang sedang aktif ditampilkan di modal.
     */
    public ?array $currentQuestion = null;

    /**
     * Pesan umpan balik (Benar/Salah) setelah menjawab pertanyaan.
     */
    public ?string $feedbackMessage = null;

    /**
     * Nama objek yang sedang aktif (diklik) untuk menampilkan pertanyaan.
     */
    public ?string $activeObjectName = null;

    /**
     * Mengontrol halaman/gambar aturan yang sedang ditampilkan.
     */
    public int $currentRulesPage = 0;

    /**
     * Mengontrol halaman/gambar popup penyelesaian level yang sedang ditampilkan.
     */
    public int $currentCompletionPage = 0;

    /**
     * Properti untuk game Teka-Teki Silang (Level 4).
     */
    public array $crosswordGrid = [];
    public int $rows = 0;
    public int $cols = 0;
    public array $clueNumbers = [];
    public array $wordData = [];
    public string $userAnswer = '';

    /**
     * Jumlah total pertanyaan/objek yang harus diselesaikan di level ini.
     */
    public int $totalQuestions = 0;

    /**
     * Status apakah pertanyaan saat ini (misal: TTS atau game menjodohkan) sudah selesai.
     */
    public bool $isQuestionComplete = false;

    /**
     * Mengelola state tampilan utama dalam level.
     * Pilihan: 'rules_popup', 'rules_background', 'playing', 'level_complete_popup', 'reflection'.
     */
    public string $viewState = 'rules_popup';

    /**
     * Properti untuk game Menjodohkan (Level 2).
     */
    public $matchingGameItems = [];
    public $selectedImage = null;
    public $selectedText = null;
    public $correctPairs = [];
    public ?string $currentGameTitle = null;
    public ?string $currentGameCategory = null;

    /**
     * Properti untuk alur layar refleksi di akhir Level 4.
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
                'kemonceng' => [
                    'image' => 'images/level1/kemonceng.svg',
                    'alt' => 'Kemonceng',
                    'style' => 'top: 48%; left: 21.5%; width: 3%;',
                ],
                'penggaris' => [
                    'image' => 'images/level1/penggaris.png',
                    'alt' => 'Penggaris',
                    'style' => 'top: 92%; left: 42%; width: 6%;',
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
            'title' => 'Kantin',
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
                'kotak-tisu' => [
                    'image' => 'images/level2/kotak-tisu.png',
                    'alt' => 'Kotak Tisu',
                    'style' => 'top: 47%; left: 16%; width: 10%;',
                ],
                'botol-saus' => [
                    'image' => 'images/level2/botol-saus.png',
                    'alt' => 'Botol Saus',
                    'style' => 'top: 44%; left: 64.5%; width: 3%;',
                ],
                'tong-sampah-3' => [
                    'image' => 'images/level2/tong-sampah-3.png',
                    'alt' => 'Tong Sampah',
                    'style' => 'top: 85%; left: 92%; width: 11%;',
                ],
                'botol-3' => [
                    'image' => 'images/level2/botol-3.png',
                    'alt' => 'Botol Minum',
                    'style' => 'top: 73%; left: 40%; width: 3%;',
                ],
            ],
            'questions' => [
                'q1' => [
                    'image' => 'images/pertanyaan-jawaban/p1-lv2.svg',
                    'category' => 'verbal',
                    'correct_items' => [
                        'verbal_1' => ['image' => 'images/pertanyaan-jawaban/verbal-1.png', 'text' => 'Mengejek, mempermalukan, dan menghina seseorang dengan sengaja'],
                        'verbal_2' => ['image' => 'images/pertanyaan-jawaban/verbal-2.png', 'text' => 'Memanggil nama-nama kasar, atau mengatakan hal-hal menyakitkan'],
                    ],
                ],
                'q2' => [
                    'image' => 'images/pertanyaan-jawaban/p2-lv2.svg',
                    'category' => 'fisik',
                    'correct_items' => [
                        'fisik_1' => ['image' => 'images/pertanyaan-jawaban/fisik-1.jpg', 'text' => 'Menjambak, menampar, dan mengunci seseorang di ruangan dan pemerasan uang atau barang'],
                        'fisik_2' => ['image' => 'images/pertanyaan-jawaban/fisik-2.jpg', 'text' => 'Memukul, menendang, dan mendorong seseorang dengan segaja'],
                    ],
                ],
                'q3' => [
                    'image' => 'images/pertanyaan-jawaban/p3-lv2.svg',
                    'category' => 'sosial',
                    'correct_items' => [
                        'sosial_1' => ['image' => 'images/pertanyaan-jawaban/sosial-1.jpg', 'text' => 'Menyebarkan rumor, menghasut seseorang, dan mengucilkan seseorang dari kelompok'],
                        'sosial_2' => ['image' => 'images/pertanyaan-jawaban/sosial-2.jpg', 'text' => 'Merusak reputasi sosial korban dan membuat lelucon yang merendahkan seseorang'],
                    ],
                ],
                'q4' => [
                    'image' => 'images/pertanyaan-jawaban/p4-lv2.svg',
                    'category' => 'cyber',
                    'correct_items' => [
                        'cyber_1' => ['image' => 'images/pertanyaan-jawaban/cyber-1.png', 'text' => 'Membuat komentar jahat di media sosial dan menyebarkan foto-foto seseorang tanpa izin'],
                        'cyber_2' => ['image' => 'images/pertanyaan-jawaban/cyber-2.png', 'text' => 'Meneror seseorang melalui media sosial dan menyerang seseorang secara online'],
                    ],
                ]
            ],
            'item_bank' => [
                // Ini adalah gabungan semua 'correct_pairs' dari atas
                'verbal_1' => ['image' => 'images/pertanyaan-jawaban/verbal-1.png', 'text' => 'Mengejek, mempermalukan, dan menghina seseorang dengan sengaja'],
                'verbal_2' => ['image' => 'images/pertanyaan-jawaban/verbal-2.png', 'text' => 'Memanggil nama-nama kasar, atau mengatakan hal-hal menyakitkan'],
                'fisik_1' => ['image' => 'images/pertanyaan-jawaban/fisik-1.jpg', 'text' => 'Menjambak, menampar, dan mengunci seseorang di ruangan dan pemerasan uang atau barang'],
                'fisik_2' => ['image' => 'images/pertanyaan-jawaban/fisik-2.jpg', 'text' => 'Memukul, menendang, dan mendorong seseorang dengan segaja'],
                'sosial_1' => ['image' => 'images/pertanyaan-jawaban/sosial-1.jpg', 'text' => 'Menyebarkan rumor, menghasut seseorang, dan mengucilkan seseorang dari kelompok'],
                'sosial_2' => ['image' => 'images/pertanyaan-jawaban/sosial-2.jpg', 'text' => 'Merusak reputasi sosial korban dan membuat lelucon yang merendahkan seseorang'],
                'cyber_1' => ['image' => 'images/pertanyaan-jawaban/cyber-1.png', 'text' => 'Membuat komentar jahat di media sosial dan menyebarkan foto-foto seseorang tanpa izin'],
                'cyber_2' => ['image' => 'images/pertanyaan-jawaban/cyber-2.png', 'text' => 'Meneror seseorang melalui media sosial dan menyerang seseorang secara online'],
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
                'tas-2' => [
                    'image' => 'images/level3/tas-2.png',
                    'alt' => 'Tas',
                    'style' => 'top: 72%; left: 60%; width: 6%;',
                ],
                'bola-basket' => [
                    'image' => 'images/level3/bola-basket.png',
                    'alt' => 'Bola Basket',
                    'style' => 'top: 67%; left: 54%; width: 3%;',
                ],
                'sepatu' => [
                    'image' => 'images/level3/sepatu.png',
                    'alt' => 'Sepatu',
                    'style' => 'top: 93%; left: 23%; width: 6%;',
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
                    'correct_answer' => 'b'
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
                    'style' => 'top: 72%; left: 62%; width: 5%;',
                ],
                'botol-2' => [
                    'image' => 'images/level4/botol-2.png',
                    'alt' => 'Botol Minum',
                    'style' => 'top: 68%; left: 6%; width: 2%;',
                ],
                'kotak-bekal' => [
                    'image' => 'images/level4/kotak-bekal.png',
                    'alt' => 'Kotak Bekal',
                    'style' => 'top: 70%; left: 11%; width: 6%;',
                ],
                'sepatu' => [
                    'image' => 'images/level4/sepatu.png',
                    'alt' => 'Sepatu',
                    'style' => 'top: 89%; left: 14%; width: 6%;',
                ],
                'tas-3' => [
                    'image' => 'images/level4/tas-3.png',
                    'alt' => 'Tas',
                    'style' => 'top: 86%; left: 6%; width: 7%;',
                ],
                'bola-basket' => [
                    'image' => 'images/level3/bola-basket.png',
                    'alt' => 'Bambu',
                    'style' => 'top: 78%; left: 20%; width: 3%;',
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

        if (env('APP_ENV') == 'local') {
            $this->viewState = 'playing';
        }

        if ($this->levelId == 2) {
            $this->redrawArrows();
        }
    }

    // --- METODE NAVIGASI ALUR ---

    /**
     * Menampilkan latar belakang di balik popup aturan.
     */
    public function showRulesBackground()
    {
        $this->viewState = 'rules_background';
    }

    /**
     * Memulai gameplay utama setelah melihat aturan.
     */
    public function startGameplay()
    {
        $this->viewState = 'playing';
        $this->currentRulesPage = 0;
    }

    /**
     * Kembali ke popup aturan dari tampilan latar belakang aturan.
     */
    public function backToRulesPopup()
    {
        $this->viewState = 'rules_popup';
    }

    /**
     * Kembali ke halaman Peta Misi.
     */
    public function backToPetaMisi()
    {
        $this->dispatch('backToPetaMisi');
    }

    // --- METODE LOGIKA GAME ---


    /**
     * Menangani event klik pada objek interaktif di dalam level.
     * @param string $objectName Nama objek yang diklik.
     */
    public function objectClicked(string $objectName)
    {
        if (in_array($objectName, $this->answeredObjects)) {
            return;
        }
        if (isset($this->questionMap[$objectName])) {
            $questionKey = $this->questionMap[$objectName];
            $this->currentQuestion = $this->levelConfig['questions'][$questionKey];

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
     * Menyiapkan state untuk mini-game menjodohkan (Level 2).
     * @param array $questionData Data pertanyaan yang berisi item-item yang benar.
     */
    public function setupMatchingGameForQuestion(array $questionData)
    {
        $this->isQuestionComplete = false;
        $this->dispatch('clear-arrows');
        $this->correctPairs = [];
        $this->selectedImage = null;
        $this->selectedText = null;

        $this->currentGameCategory = $questionData['category'];

        $correctItems = $questionData['correct_items'];
        $correctKeys = array_keys($correctItems);

        $distractors = $this->getDistractors($this->currentGameCategory, $correctKeys);

        $gameItems = array_merge($correctItems, $distractors);

        $images = [];
        $texts = [];

        foreach ($gameItems as $key => $item) {
            $images[$key] = $item['image'];
            $texts[$key] = $item['text'];
        }

        $imageKeys = array_keys($images);
        $textKeys = array_keys($texts);
        shuffle($imageKeys);
        shuffle($textKeys);

        $shuffledImages = [];
        $shuffledTexts = [];

        foreach ($imageKeys as $key) {
            $shuffledImages[$key] = $images[$key];
        }

        foreach ($textKeys as $key) {
            $shuffledTexts[$key] = $texts[$key];
        }

        $this->matchingGameItems = [
            'images' => $shuffledImages,
            'texts' => $shuffledTexts,
        ];
    }

    /**
     * Mengambil item pengecoh untuk game menjodohkan dari kategori yang berbeda.
     * @param string $currentCategory Kategori soal saat ini.
     * @param array $excludeKeys Kunci item yang benar untuk dikecualikan.
     * @return array Daftar item pengecoh.
     */
    private function getDistractors(string $currentCategory, array $excludeKeys = []): array
    {
        $distractors = [];
        $itemBank = $this->levelConfig['item_bank'];

        // Acak semua kunci item bank
        $allKeys = array_keys($itemBank);
        shuffle($allKeys);

        foreach ($allKeys as $key) {
            if (count($distractors) >= 2) break;

            $itemCategory = explode('_', $key)[0];

            if ($itemCategory === $currentCategory || in_array($key, $excludeKeys)) {
                continue;
            }

            $distractors[$key] = $itemBank[$key];
        }

        return $distractors;
    }

    /**
     * Menerima event 'itemSelected' dari JavaScript untuk game menjodohkan.
     * @param string $type Tipe item ('image' atau 'text').
     * @param string $key Kunci unik dari item yang dipilih.
     */
    #[On('itemSelected')]
    public function itemSelected($type, $key)
    {
        if ($this->isQuestionComplete) {
            return;
        }

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
     * Memeriksa apakah pasangan gambar dan teks yang dipilih benar untuk game menjodohkan.
     */
    public function checkPair()
    {
        if ($this->isQuestionComplete || !$this->selectedImage || !$this->selectedText) {
            return;
        }
        $imageCategory = explode('_', $this->selectedImage)[0];
        $textCategory = explode('_', $this->selectedText)[0];

        if ($imageCategory === $textCategory && $imageCategory === $this->currentGameCategory) {

            $isImageAlreadyPaired = isset($this->correctPairs[$this->selectedImage]);
            $isTextAlreadyPaired = in_array($this->selectedText, $this->correctPairs);

            if ($isImageAlreadyPaired || $isTextAlreadyPaired) {
                $this->dispatch('show-notification', message: 'Item ini sudah punya pasangan yang benar.', type: 'error');
                $this->dispatch('incorrect-answer');
            } else {
                $this->correctPairs[$this->selectedImage] = $this->selectedText;
                $this->dispatch('correct-answer');
                $this->dispatch('draw-correct-arrow', [
                    'startKey' => $this->selectedImage,
                    'endKey' => $this->selectedText
                ]);

                if (count($this->correctPairs) >= 2) {
                    $this->isQuestionComplete = true;
                    $this->js("setTimeout(() => { Livewire.dispatch('completeCurrentQuestion') }, 500)");
                } else {
                    $this->dispatch('show-notification', message: 'Pasangan benar!', type: 'success');
                }
            }
        } else {
            $this->dispatch('show-notification', message: 'Pasangan kurang tepat, coba lagi.', type: 'error');
            $this->dispatch('incorrect-answer');
        }
        $this->resetSelection();
    }

    /**
     * Menyelesaikan pertanyaan game menjodohkan saat ini.
     */
    #[On('completeCurrentQuestion')]
    public function completeQuestion()
    {
        if (!in_array($this->activeObjectName, $this->answeredObjects)) {
            $this->answeredObjects[] = $this->activeObjectName;
        }

        $progress = session('game_progress', []);
        $progress[$this->levelId]['answered_objects'] = $this->answeredObjects;
        session(['game_progress' => $progress]);

        $this->dispatch('show-notification', message: 'Hebat, semua pasangan ditemukan!', type: 'success');

        $this->js("setTimeout(() => { Livewire.dispatch('closeModalAndCheck') }, 1200)");
    }

    /**
     * Mereset pilihan gambar dan teks pada game menjodohkan.
     */
    public function resetSelection()
    {
        $this->selectedImage = null;
        $this->selectedText = null;
    }

    /**
     * Livewire hook yang dipanggil setelah properti diupdate.
     */
    public function updated($property)
    {
        if ($property === 'correctPairs' && $this->levelId == 2) {
            $this->dispatch('redraw-all-arrows');
        }
    }

    /**
     * Memicu event JavaScript untuk menggambar ulang semua panah yang sudah benar.
     */
    public function redrawArrows()
    {
        if ($this->levelId == 2 && !empty($this->correctPairs)) {
            foreach ($this->correctPairs as $imgKey => $txtKey) {
                $this->dispatch('draw-correct-arrow', [
                    'startKey' => $imgKey,
                    'endKey' => $txtKey,
                    'initialLoad' => true
                ]);
            }
        }
    }

    /**
     * Livewire hook yang dipanggil saat komponen di-hydrate (misal: setelah request).
     */
    public function hydrate()
    {
        if ($this->levelId == 2) {
            $this->redrawArrows();
        }
    }

    /**
     * Memproses pilihan jawaban dari pemain.
     * @param string $selectedOption Pilihan jawaban (misal: 'a', 'b', 'c').
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
     * Menyelesaikan level dan melanjutkan alur game.
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
     * Menutup modal pertanyaan dan memeriksa status penyelesaian level.
     */
    #[On('closeModalAndCheck')]
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

    /**
     * Menampilkan halaman berikutnya pada popup penyelesaian level.
     */
    public function showNextCompletionPage()
    {
        $completionBoards = $this->levelConfig['assets']['completion_boards'];
        if ($this->currentCompletionPage < count($completionBoards) - 1) {
            $this->currentCompletionPage++;
        }
    }

    /**
     * Menampilkan halaman sebelumnya pada popup penyelesaian level.
     */
    public function showPreviousCompletionPage()
    {
        if ($this->currentCompletionPage > 0) {
            $this->currentCompletionPage--;
        }
    }

    /**
     * Menampilkan halaman aturan berikutnya atau memulai game jika sudah di halaman terakhir.
     */
    public function showNextRule()
    {
        $rulesBoards = $this->levelConfig['assets']['rules_boards'];
        if ($this->currentRulesPage < count($rulesBoards) - 1) {
            $this->currentRulesPage++;
        } else {
            $this->startGameplay();
        }
    }

    /**
     * Menampilkan halaman aturan sebelumnya.
     */
    public function showPreviousRule()
    {
        if ($this->currentRulesPage > 0) {
            $this->currentRulesPage--;
        }
    }

    /**
     * Navigasi ke halaman refleksi berikutnya atau menyelesaikan level jika sudah di halaman terakhir.
     */
    public function nextReflectionPage()
    {
        if ($this->currentReflectionPage < count($this->reflectionPages)) {
            $this->currentReflectionPage++;
        } else {
            $this->dispatch('levelCompleted', $this->levelId);
            $this->dispatch('backToPetaMisi');
        }
    }

    /**
     * Navigasi ke halaman refleksi sebelumnya.
     */
    public function previousReflectionPage()
    {
        if ($this->currentReflectionPage > 1) {
            $this->currentReflectionPage--;
        }
    }

    /**
     * Menginisialisasi grid dan data untuk game Teka-Teki Silang (Level 4).
     */
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

    /**
     * Memproses jawaban yang dikirimkan pemain untuk game Teka-Teki Silang.
     */
    public function submitTtsAnswer()
    {
        if (!$this->currentQuestion) return;

        $correctAnswer = strtolower($this->currentQuestion['answer']);
        $submittedAnswer = strtolower(trim($this->userAnswer));

        if ($submittedAnswer === $correctAnswer) {
            if (!in_array($this->activeObjectName, $this->answeredObjects)) {
                $this->answeredObjects[] = $this->activeObjectName;
            }

            $progress = session('game_progress', []);
            $progress[$this->levelId]['answered_objects'] = $this->answeredObjects;
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

    /**
     * Mengisi huruf-huruf dari kata yang benar ke dalam grid Teka-Teki Silang.
     * @param string $word Kata yang akan diisi.
     */
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
            } else {
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
