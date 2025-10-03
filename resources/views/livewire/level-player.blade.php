<div class="bg-gray-800 min-h-screen flex items-center justify-center overflow-hidden">
    <div class="relative w-full max-h-full aspect-video">
        {{-- Latar Belakang Game --}}
        <img src="{{ $backgroundUrl }}" class="absolute top-0 left-0 w-full h-full object-cover z-0">

        {{-- Objek Interaktif --}}
        @foreach ($levelConfig['objects'] as $objectName => $objectData)
            @php
                $isAnswered = in_array($objectName, $answeredObjects);
            @endphp
            <button wire:click="objectClicked('{{ $objectName }}')"
                class="absolute transition-transform z-10 -translate-x-1/2 -translate-y-1/2 cursor-default {{ $isAnswered ? 'opacity-50' : 'hover:scale-101' }}"
                style="{{ $objectData['style'] }}" @disabled($isAnswered)>
                <img src="{{ asset($objectData['image']) }}" alt="{{ $objectData['alt'] }}">
            </button>
        @endforeach

        {{-- Tombol UI Game --}}
        <div class="absolute top-[4%] right-[1%] flex gap-[4%] z-20 w-[15%]">
            <button class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/volume-button.svg') }}" alt="Volume">
            </button>
            <button wire:click="backToPetaMisi" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/home-button.svg') }}" alt="Home">
            </button>
        </div>

        {{-- Overlay dan Popup --}}
        @if ($viewState !== 'playing' || $showQuestionModal)
            <div class="absolute inset-0 bg-black bg-opacity-50 z-30"></div>
        @endif

        {{-- STATE 1: POPUP ATURAN AWAL --}}
        @if ($viewState === 'rules_popup')
            <div class="absolute inset-0 w-full h-full flex items-center justify-center z-40">
                <div class="relative w-[50%] aspect-[4/3]">
                    {{-- Menampilkan gambar aturan secara dinamis --}}
                    <img src="{{ asset($levelConfig['assets']['rules_boards'][$currentRulesPage]) }}"
                        class="w-full h-full">

                    {{-- Tombol Navigasi Kiri --}}
                    @if ($currentRulesPage > 0)
                        <button wire:click="showPreviousRule"
                            class="absolute bottom-[2%] right-[25%] w-[25%] h-auto hover:scale-110 transition-transform">
                            <img src="{{ asset('images/utils/tombol-back.png') }}" alt="Kembali">
                        </button>
                    @else
                        {{-- Tombol kembali ke Peta Misi di halaman pertama --}}
                        <button wire:click="backToPetaMisi"
                            class="absolute bottom-[2%] right-[25%] w-[25%] h-auto hover:scale-110 transition-transform">
                            <img src="{{ asset('images/utils/tombol-back.png') }}" alt="Kembali ke Peta Misi">
                        </button>
                    @endif

                    {{-- Tombol Navigasi Kanan --}}
                    <button wire:click="showNextRule"
                        class="absolute bottom-[2%] right-[-2%] w-[25%] h-auto hover:scale-110 transition-transform">
                        <img src="{{ asset('images/utils/tombol-next.png') }}" alt="Lanjut">
                    </button>
                </div>
            </div>
        @endif

        {{-- POPUP SOAL (di dalam state 'playing') --}}
        @if ($showQuestionModal && $currentQuestion)
            <div class="absolute inset-0 flex items-center justify-center z-50">
                @if ($levelId == 4)
                    <div class="relative w-[70%] aspect-[4/3]">
                        {{-- Gambar Papan Tulis sebagai Latar Belakang --}}
                        <img src="{{ asset($currentQuestion['image']) }}" class="w-full h-full object-contain">

                        {{-- Kontainer untuk elemen-elemen di atas gambar --}}
                        <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center">

                            {{-- === BLOK TTS BARU YANG SUDAH DI-STYLING === --}}
                            <div
                                class="absolute top-[80%] xs:top-[75%] tablet:top-[42%] lg:top-[48%] left-[32%] lg:left-1/2 -translate-x-1/2 -translate-y-1/2 xs:w-[80%] sm:w-[20%] tablet:w-[20%] lg:w-[60%]">
                                <div class="inline-grid gap-1 w-full"
                                    style="grid-template-columns: repeat({{ $cols }}, 1fr);">
                                    @for ($r = 0; $r < $rows; $r++)
                                        @for ($c = 0; $c < $cols; $c++)
                                            @php
                                                $cell = $crosswordGrid[$r][$c] ?? null;
                                                $key = "{$r}_{$c}";
                                                $clueNo = $clueNumbers[$key] ?? null;
                                            @endphp

                                            @if ($cell === null)
                                                {{-- Area kosong --}}
                                                <div class="w-full aspect-square bg-transparent"></div>
                                            @else
                                                {{-- Kotak huruf yang benar (hanya w-full dan aspect-square) --}}
                                                <div
                                                    class="relative w-full aspect-square bg-white text-black text-center flex items-center justify-center font-bold rounded-sm">
                                                    @if ($clueNo)
                                                        <span
                                                            class="absolute top-0 left-0.5 text-[5px] sm:text-[8px] md:text-[0.6rem] lg:text-[0.7rem] leading-none text-gray-600">{{ $clueNo }}</span>
                                                    @endif
                                                    <span
                                                        class="select-none text-[5px] sm:text-xs md:text-sm lg:text-base">{{ $cell }}</span>
                                                </div>
                                            @endif
                                        @endfor
                                    @endfor
                                </div>
                            </div>
                            {{-- === AKHIR BLOK TTS === --}}

                            {{-- Form Jawaban --}}
                            <div
                                class="absolute bottom-[12%] left-[50%] -translate-x-1/2 w-[60%] h-[10%] md:w-[50%] lg:w-[40%] text-center">
                                @if (!$feedbackMessage || !str_contains($feedbackMessage, 'Benar'))
                                    <input type="text" id="userAnswer" wire:model.live="userAnswer"
                                        wire:keydown.enter="submitTtsAnswer"
                                        class="p-1 md:p-2 w-full text-center border border-gray-400 rounded-md shadow-sm text-sm md:text-base"
                                        placeholder="Ketik jawaban..." autocomplete="off">
                                    <button wire:click="submitTtsAnswer"
                                        class="mt-1 md:mt-3 px-4 py-1 md:px-6 md:py-2 bg-blue-500 text-white rounded-2xl hover:bg-blue-600 text-sm md:text-base">
                                        Kirim Jawaban
                                    </button>
                                @else
                                    <button wire:click="closeModalAndCheckCompletion"
                                        class="mt-1 md:mt-2 px-4 py-1 md:px-6 md:py-2 bg-green-500 text-white rounded-full hover:bg-green-600 text-sm md:text-base">
                                        Lanjut
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif ($levelId == 2)
                    <div class="relative w-[70%] aspect-[4/3]">
                        {{-- Latar belakang pertanyaan tetap ada --}}
                        <img src="{{ asset($currentQuestion['image']) }}" alt="Latar Belakang Pertanyaan"
                            class="w-full h-full">

                        {{-- Lapisan (overlay) untuk konten mini-game --}}
                        <div class="absolute inset-0 flex justify-center items-center">

                            {{-- Kontainer untuk mini-game, diposisikan lebih presisi --}}
                            <div class="w-[80%] h-[60%] mt-[8%] flex justify-between items-center gap-x-4 md:gap-x-8">

                                {{-- Kolom Kiri untuk Gambar --}}
                                <div class="w-1/2 h-full flex flex-col justify-center items-center space-y-1">
                                    @foreach ($matchingGameItems['images'] as $key => $image)
                                        {{-- Div ini sekarang hanya untuk layout, bukan untuk diklik --}}
                                        <div class="w-full h-1/4 flex items-center justify-center">
                                            @if (!in_array($key, $correctPairs))
                                                {{-- Semua interaktivitas dan style dipindahkan ke tag <img> --}}
                                                <img src="{{ asset($image) }}"
                                                    wire:click="selectItem('image', '{{ $key }}')"
                                                    class="max-w-full max-h-full object-contain bg-opacity-80 rounded-md cursor-pointer transition-all border-4 {{ $selectedImage === $key ? 'border-blue-500 scale-105' : 'border-transparent hover:border-blue-300' }}">
                                            @else
                                                {{-- Item yang sudah benar --}}
                                                <img src="{{ asset($image) }}"
                                                    class="max-w-full max-h-full object-contain opacity-20">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Kolom Kanan untuk Teks --}}
                                <div class="w-1/2 h-full flex flex-col justify-center items-center space-y-1">
                                    @foreach ($matchingGameItems['texts'] as $key => $text)
                                        @if (!in_array($key, $correctPairs))
                                            <div wire:click="selectItem('text', '{{ $key }}')"
                                                class="w-full h-1/4 flex items-center justify-center text-center p-1 bg-yellow-100 rounded-md text-[0.6rem] md:text-[0.65rem] lg:text-sm leading-tight border-4 cursor-pointer transition-all {{ $selectedText === $key ? 'border-blue-500 bg-blue-200 scale-105' : 'border-yellow-700 hover:border-blue-300' }}">
                                                {{ $text }}
                                            </div>
                                        @else
                                            {{-- Item yang sudah benar akan menjadi hijau dan transparan --}}
                                            <div
                                                class="w-full h-1/4 flex items-center justify-center text-center p-1 bg-green-200 border-4 border-green-500 rounded-md text-[0.6rem] md:text-[0.65rem] lg:text-sm opacity-50">
                                                {{ $text }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                @else
                    <div class="relative w-[70%] aspect-[4/3]">
                        <img src="{{ asset($currentQuestion['image']) }}" class="w-full h-full object-contain">
                        <div class="absolute inset-0 flex flex-col items-center justify-end p-[12%]">
                            {{-- Pilihan Jawaban --}}
                            @if ($levelId == 2)
                                <div class="grid grid-cols-2 gap-[5%] w-[50%] max-w-xl mb-[5%]">
                                    @foreach ($currentQuestion['options'] as $key => $option)
                                        <button wire:click.prevent="selectAnswer('{{ $key }}')"
                                            @disabled($feedbackMessage && str_contains($feedbackMessage, 'Benar'))
                                            class="p-1 rounded-lg hover:scale-105 transition-transform disabled:opacity-70">
                                            <img src="{{ asset($option) }}" class="w-full h-full object-contain">
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <div class="w-full max-w-4xl mb-4">
                                    <div class="flex justify-center gap-[4%]">
                                        <button wire:click.prevent="selectAnswer('a')" @disabled($feedbackMessage && str_contains($feedbackMessage, 'Benar'))
                                            class="w-2/5 p-1 rounded-lg hover:scale-105 transition-transform disabled:opacity-70">
                                            <img src="{{ asset($currentQuestion['options']['a']) }}"
                                                class="w-full h-full object-contain">
                                        </button>
                                        <button wire:click.prevent="selectAnswer('b')" @disabled($feedbackMessage && str_contains($feedbackMessage, 'Benar'))
                                            class="w-2/5 p-1 rounded-lg hover:scale-105 transition-transform disabled:opacity-70">
                                            <img src="{{ asset($currentQuestion['options']['b']) }}"
                                                class="w-full h-full object-contain">
                                        </button>
                                    </div>
                                    <div class="flex justify-center mt-4">
                                        <button wire:click.prevent="selectAnswer('c')" @disabled($feedbackMessage && str_contains($feedbackMessage, 'Benar'))
                                            class="w-2/5 p-1 rounded-lg hover:scale-105 transition-transform disabled:opacity-70">
                                            <img src="{{ asset($currentQuestion['options']['c']) }}"
                                                class="w-full h-full object-contain">
                                        </button>
                                    </div>
                                </div>
                            @endif
                            {{-- Feedback & Tombol Lanjut --}}
                            @if (str_contains($feedbackMessage, 'Benar'))
                                <button wire:click="closeModalAndCheckCompletion"
                                    class="mt-4 px-6 py-2 bg-green-500 text-white rounded-full hover:bg-green-600">Lanjut</button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- STATE 4: POPUP LEVEL SELESAI --}}
        @if ($viewState === 'level_complete_popup')
            <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center z-40">
                <div class="relative w-[50%] aspect-[4/3]">
                    {{-- Menampilkan gambar selesai secara dinamis --}}
                    <img src="{{ asset($levelConfig['assets']['completion_boards'][$currentCompletionPage]) }}"
                        class="w-full h-full">

                    {{-- Tombol Navigasi Kiri --}}
                    @if ($currentCompletionPage > 0)
                        <button wire:click="showPreviousCompletionPage"
                            class="absolute bottom-[2%] right-[25%] w-[25%] h-auto hover:scale-110 transition-transform">
                            <img src="{{ asset('images/utils/tombol-back.png') }}" alt="Kembali">
                        </button>
                    @endif

                    {{-- Tombol Navigasi Kanan atau Tombol Next Level --}}
                    @if ($currentCompletionPage < count($levelConfig['assets']['completion_boards']) - 1)
                        <button wire:click="showNextCompletionPage"
                            class="absolute bottom-[2%] right-[-2%] w-[25%] h-auto hover:scale-110 transition-transform">
                            <img src="{{ asset('images/utils/tombol-next.png') }}" alt="Lanjut">
                        </button>
                    @else
                        @if ($levelId == 4)
                            {{-- KHUSUS LV 4: Tombol "Next" untuk menuju halaman refleksi --}}
                            <button wire:click="completeLevelAndExit"
                                class="absolute bottom-[2%] right-[-2%] w-[25%] h-auto hover:scale-110 transition-transform">
                                <img src="{{ asset('images/utils/tombol-next.png') }}" alt="Lanjut">
                            </button>
                        @else
                            {{-- LV 1-3: Tombol "Next Level" untuk ke level selanjutnya --}}
                            <button wire:click="completeLevelAndExit"
                                class="absolute bottom-[2%] right-[-2%] w-[25%] h-auto hover:scale-110 transition-transform">
                                <img src="{{ asset('images/utils/tombol-next-level.png') }}" alt="Next Level">
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        @endif

        @if ($viewState === 'reflection')
            <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center z-40">
                <img src="{{ asset($reflectionPages[$currentReflectionPage]) }}" class="w-full h-full object-cover">

                {{-- Tombol Volume --}}
                <div class="absolute top-[4%] right-[0%] w-[10%] flex z-20">
                    <button class="w-[80%] hover:scale-110 transition-transform">
                        <img src="{{ asset('images/home/volume-button.svg') }}" alt="Volume">
                    </button>
                </div>

                {{-- Tombol Mengerti --}}
                @if ($currentReflectionPage < count($reflectionPages) - 1)
                    <button wire:click="nextReflectionPage"
                        class="absolute bottom-[8%] right-[25%] w-[25%] h-auto hover:scale-110 transition-transform">
                        <img src="{{ asset('images/utils/tombol-mengerti.png') }}" alt="Mengerti">
                    </button>
                @else
                    <button wire:click="nextReflectionPage"
                        class="absolute bottom-[8%] right-[25%] w-[25%] h-auto hover:scale-110 transition-transform">
                        <img src="{{ asset('images/utils/tombol-selesai.png') }}" alt="Selesai">
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
