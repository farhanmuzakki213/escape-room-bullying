<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.guest')]
class LevelPlayer extends Component
{
    public int $levelId;
    public string $backgroundUrl;
    public array $levelConfig;

    public bool $showQuestionModal = false;
    public ?array $currentQuestion = null;
    public ?string $feedbackMessage = null;

    public array $answeredObjects = [];
    public ?string $activeObjectName = null;


    public array $levelData = [
        1 => [
            'background' => 'images/level1/background-level-1.svg',
            'title' => 'Ruang Kelas',
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
    ];

    public function mount()
    {
        $this->levelConfig = $this->levelData[$this->levelId];
        $this->backgroundUrl = asset($this->levelConfig['background']);
    }

    public function backToPetaMisi()
    {
        $this->dispatch('backToPetaMisi');
    }

    public function objectClicked(string $objectName)
    {
        if (in_array($objectName, $this->answeredObjects)) {
            return;
        }

        if (isset($this->levelConfig['objects'][$objectName]['question'])) {
            $this->currentQuestion = $this->levelConfig['objects'][$objectName]['question'];
            $this->showQuestionModal = true;
            $this->feedbackMessage = null; // Reset feedback
        }
    }

    public function selectAnswer(string $selectedOption)
    {
        if ($this->currentQuestion) {
            if ($selectedOption == $this->currentQuestion['correct_answer']) {
                $this->feedbackMessage = "Jawabanmu Benar! 👍";

                if (!in_array($this->activeObjectName, $this->answeredObjects)) {
                    $this->answeredObjects[] = $this->activeObjectName;
                }

                $this->checkLevelCompletion();
            } else {
                $this->feedbackMessage = "Jawabanmu kurang tepat, coba lagi ya.";
            }
        }
    }

    public function checkLevelCompletion()
    {
        $totalObjectsWithQuestions = count($this->levelConfig['objects']);
        if (count($this->answeredObjects) >= $totalObjectsWithQuestions) {
            $this->dispatch('levelCompleted', level: $this->levelId);
            sleep(2);
            $this->closeModal();
            $this->backToPetaMisi();
        }
    }

    public function closeModal()
    {
        $this->showQuestionModal = false;
        $this->currentQuestion = null;
        $this->feedbackMessage = null;
    }

    public function render()
    {
        return view('livewire.level-player');
    }
}
