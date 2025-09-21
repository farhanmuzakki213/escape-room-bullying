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
                $this->feedbackMessage = "Jawabanmu kurang tepat. Coba pikirkan lagi pilihan yang paling tepat.";
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
    }

    /**
     * Membagi teks penyelesaian menjadi beberapa halaman.
     */
    public function paginateCompletionText()
    {
        $text = $this->levelConfig['rules']['completion_text'];
        $words = explode(' ', $text);
        $pages = [];
        $currentPage = '';
        $wordCount = 0;
        $maxWordsPerPage = 30; // Naikkan batas kata per halaman

        foreach ($words as $word) {
            // Cek jika penambahan kata baru akan melebihi batas
            if ($wordCount > 0 && $wordCount + str_word_count($word) > $maxWordsPerPage) {
                $pages[] = trim($currentPage);
                $currentPage = '';
                $wordCount = 0;
            }
            $currentPage .= $word . ' ';
            $wordCount++;
        }

        // Tambahkan sisa kalimat terakhir ke halaman
        if (!empty(trim($currentPage))) {
            $pages[] = trim($currentPage);
        }

        // Jika tidak ada halaman yang dibuat (teks pendek), masukkan semua teks
        if (empty($pages) && !empty($text)) {
            $pages[] = $text;
        }

        $this->completionTextPages = $pages;
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
     * Merender view komponen.
     */
    public function render()
    {
        return view('livewire.level-player');
    }
}
