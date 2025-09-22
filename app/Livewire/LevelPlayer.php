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
     * Properti untuk paginasi teks penyelesaian level.
     */
    public array $completionTextPages = [];
    public int $currentCompletionTextPage = 0;

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
        1 => 'images/petunjuk/background-refleksi-diri.svg',
        2 => 'images/petunjuk/background-refleksi-verbal.svg',
        3 => 'images/petunjuk/background-refleksi-fisik.svg',
        4 => 'images/petunjuk/background-refleksi-relasional.svg',
        5 => 'images/petunjuk/background-refleksi-cyberbullying.svg',
    ];

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
                'gunting' => [
                    'image' => 'images/level1/gunting.svg',
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
                    'image' => 'images/level1/colokan.svg',
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
                    'image' => 'images/level1/lukisan-biru.svg',
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
            'background' => 'images/level2/background-level-2.svg',
            'title' => 'Perpustakaan',
            'rules' => [
                'popup_text' => 'Hore, kita berhasil keluar dari kelas! Tapi sekarang kita terkunci di perpustakaan. Di tempat ini, tersimpan pertanyaan-pertanyaan yang harus kamu jawab agar bisa keluar dari ruangan ini.',
                'background_text' => 'Coba klik benda-benda didalamnya, siapa tahu ada petunjuk yang bisa membantu kita.',
                'completion_text' => 'Keren! Sekarang kamu tahu kan jenis-jenis bullying itu apa saja, ada jenis fisik seperti memukul atau mendorong, ada verbal seperti mengejek dan menghina, ada sosial dengan cara mengucilkan atau menyebarkan gosip, dan ada juga cyberbullying lewat media sosial atau pesan online. Semua bentuk ini sama-sama menyakitkan dan berbahaya loh!'
            ],
            'assets' => [
                'rules_board' => 'images/petunjuk/papan-aturan.svg',
                'question_board' => 'images/petunjuk/papan-pertanyaan.svg',
                'rules_background' => 'images/petunjuk/background-rules.svg'
            ],
            'objects' => [
                'ac' => [
                    'image' => 'images/level2/ac.svg',
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
                    'image' => 'images/level2/papan-library.svg',
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
                    'image' => 'images/level2/tempat-sampah.svg',
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
                    'image' => 'images/level2/vas.svg',
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
            'background' => 'images/level3/background-level-3.svg',
            'title' => 'Lorong Sekolah',
            'rules' => [
                'popup_text' => 'Kita berhasil keluar dari perpustakaan! Tapi sekarang lorong sekolah ini terkunci. Hmm… suasananya terasa berbeda ya? Di tempat ini, kita harus mencari tahu apa yang membuat bullying terjadi dan apa saja dampaknya bagi korban maupun lingkungan sekolah.',
                'background_text' => 'Ayo perhatikan baik-baik benda-benda di lorong ini, mungkin ada petunjuk yang bisa membantu kita membuka pintu berikutnya.',
                'completion_text' => 'Wah, kamu hebat! Teman-teman, bullying itu tidak muncul begitu saja. Ada banyak faktor penyebabnya yaitu keluarga, pergaulan, teknologi, dan lingkungan sekolah dan dampaknya juga besar banget loh korban bisa terluka fisik (dampak fisik), menghindar dari pertemanan (dampak sosial), kehilangan semangat belajar dan bolos sekolah (dampak akademik), bahkan trauma dan rendah diri (dampak psikologis). Jadi yuk, kita sama-sama jaga lingkungan sekolah supaya aman, nyaman, dan bebas dari bullying. Nah, ayo kita lanjut ke halaman sekolah untuk mencari tahu bagaimana cara mencegah dan menangani bullying!'
            ],
            'assets' => [
                'rules_board' => 'images/petunjuk/papan-aturan.svg',
                'question_board' => 'images/petunjuk/papan-pertanyaan.svg',
                'rules_background' => 'images/petunjuk/background-rules.svg'
            ],
            'objects' => [
                'mading' => [
                    'image' => 'images/level3/mading.svg',
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
                    'image' => 'images/level3/bola-basket.svg',
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
                    'image' => 'images/level3/jam-dinding.svg',
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
                    'image' => 'images/level3/pensil-berjatuhan.svg',
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
            'background' => 'images/level4/background-level-4.svg',
            'title' => 'Lapangan Sekolah',
            'rules' => [
                'popup_text' => 'Kita sudah sampai di halaman sekolah! Wah, ternyata gerbang sekolah terkunci. Untuk bisa keluar, kita harus menemukan kunci rahasia. Tapi kuncinya hanya bisa terbuka kalau kita tahu cara mencegah dan menangani bullying.',
                'background_text' => 'Yuk, cari petunjuk di sekitar halaman ini, mungkin ada papan, tas, atau barang-barang yang menyimpan jawaban!',
                'completion_text' => 'Hebat sekali! Agar bullying tidak terjadi, kita perlu mencegahnya sejak awal. Caranya dengan mendidik anak penuh kasih sayang di rumah, menciptakan budaya sekolah yang aman, serta memberi contoh sikap baik dalam masyarakat. Nah jika bullying sudah terjadi, penanganannya bisa lewat komunikasi yang terbuka, memberi dukungan pada korban, menegur pelaku dengan tegas dan sanksi, dan melibatkan guru maupun orang tua. Dengan begitu, semua bisa merasa aman dan dihargai.'
            ],
            'assets' => ['rules_board' => 'images/petunjuk/papan-aturan.svg',],
            'objects' => [
                'kertas-berjatuhan' => [
                    'image' => 'images/level4/kertas-berjatuhan.svg',
                    'alt' => 'Kertas Berjatuhan',
                    'style' => 'top: 90%; left: 20%; width: 8%;',
                    'question' => [
                        'id' => 1,
                        'image' => 'images/pertanyaan-jawaban/p1-lv4.svg',
                        'answer' => 'konseling'
                    ]
                ],
                'kursi' => [
                    'image' => 'images/level4/kursi.svg',
                    'alt' => 'Kursi',
                    'style' => 'top: 70%; left: 11%; width: 20%;',
                    'question' => [
                        'id' => 2,
                        'image' => 'images/pertanyaan-jawaban/p2-lv4.svg',
                        'answer' => 'dukungan'
                    ]
                ],
                'pohon' => [
                    'image' => 'images/level4/pohon.svg',
                    'alt' => 'Pohon',
                    'style' => 'top: 45%; left: 10%; width: 25%;',
                    'question' => [
                        'id' => 3,
                        'image' => 'images/pertanyaan-jawaban/p3-lv4.svg',
                        'answer' => 'komunikasi'
                    ]
                ],
                'tempat-sampah' => [
                    'image' => 'images/level4/tempat-sampah.svg',
                    'alt' => 'Tempat Sampah',
                    'style' => 'top: 66%; left: 78%; width: 14%;',
                    'question' => [
                        'id' => 4,
                        'image' => 'images/pertanyaan-jawaban/p4-lv4.svg',
                        'answer' => 'melaporkan'
                    ]
                ],
                'bendera' => [
                    'image' => 'images/level4/bendera.svg',
                    'alt' => 'Bendera',
                    'style' => 'top: 50%; left: 55%; width: 15%;',
                    'question' => [
                        'id' => 5,
                        'image' => 'images/pertanyaan-jawaban/p5-lv4.svg',
                        'answer' => 'masyarakat'
                    ]
                ],
                'bambu' => [
                    'image' => 'images/level3/bola-basket.svg',
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

        // if (env('APP_ENV') == 'local') {
        //     $this->viewState = 'playing';
        // }

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
            $this->feedbackMessage = null;
            $this->userAnswer = '';
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
                $this->feedbackMessage = "Jawabanmu Benar! 👍";
                if (!in_array($this->activeObjectName, $this->answeredObjects)) {
                    $this->answeredObjects[] = $this->activeObjectName;
                }
                $this->dispatch('correct-answer');
            } else {
                $this->feedbackMessage = "Jawabanmu kurang tepat. Coba pikirkan lagi pilihan yang paling tepat.";
                $this->dispatch('incorrect-answer');
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
            $this->paginateCompletionText();
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
        $this->userAnswer = '';
    }

    /**
     * Membagi teks penyelesaian menjadi beberapa halaman.
     */
    public function paginateCompletionText()
    {
        $text = $this->levelConfig['rules']['completion_text'];
        $words = explode(' ', $text);
        $chunks = array_chunk($words, 30);
        $this->completionTextPages = array_map(function($chunk) {
            return implode(' ', $chunk);
        }, $chunks);

        $this->currentCompletionTextPage = 0;
    }


    public function nextCompletionPage()
    {
        if ($this->currentCompletionTextPage < count($this->completionTextPages) - 1) {
            $this->currentCompletionTextPage++;
        }
    }

    public function previousCompletionPage()
    {
        if ($this->currentCompletionTextPage > 0) {
            $this->currentCompletionTextPage--;
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

        $grid[9][0] = 'K';
        $grid[4][2] = 'K';
        $grid[7][6] = 'A';
        $grid[2][6] = 'M';
        $grid[0][10] = 'H';
        $this->crosswordGrid = $grid;
    }

    public function submitTtsAnswer()
    {
        if (!$this->currentQuestion) return;

        $correctAnswer = strtolower($this->currentQuestion['answer']);
        $submittedAnswer = strtolower(trim($this->userAnswer));

        if ($submittedAnswer === $correctAnswer) {
            $this->feedbackMessage = "Jawabanmu Benar! 👍";
            if (!in_array($correctAnswer, $this->filledAnswers)) {
                $this->filledAnswers[] = $correctAnswer;
            }
            // Panggil fungsi untuk mengisi huruf ke grid
            $this->fillWordInGrid($correctAnswer);
            $this->dispatch('correct-answer');
        } else {
            $this->feedbackMessage = "Jawabanmu kurang tepat. Coba lagi!";
            $this->dispatch('incorrect-answer');
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
