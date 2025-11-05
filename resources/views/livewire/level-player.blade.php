<div class="bg-gray-800 min-h-screen flex items-center justify-center overflow-hidden" wire:poll.1000ms="decrementTimer">
    <div class="relative w-full max-h-full aspect-video">
        {{-- Latar Belakang Level --}}
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

        {{-- BARU: Tampilan Timer --}}
        <div x-data="{
            timeLeft: @entangle('timeLeft'),
            formatTime() {
                if (this.timeLeft <= 0) return '00:00';
                const minutes = Math.floor(this.timeLeft / 60);
                const seconds = this.timeLeft % 60;
                return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        }" class="absolute top-[4%] left-[2%] z-20">
            <div
                class="flex items-center gap-2 bg-black bg-opacity-40 px-4 py-2 rounded-full shadow-lg border border-white/20">
                {{-- Ikon Jam --}}
                <svg class="w-6 h-6 text-white" fill="none" stroke-width="2" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                </svg>
                {{-- Teks Waktu --}}
                <span x-text="formatTime()" class="text-white font-bold text-xl md:text-2xl tabular-nums tracking-wider"
                    :class="{ 'text-red-500 animate-pulse': timeLeft > 0 && timeLeft <= 10 }">
                </span>
            </div>
        </div>

        {{-- Tombol UI Global (Volume & Kembali) --}}
        <div class="absolute top-[4%] right-[1%] flex gap-[4%] z-20 w-[15%]">
            <button class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/volume-button.svg') }}" alt="Volume">
            </button>
            <button wire:click="backToPetaMisi" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/home-button.svg') }}" alt="Home">
            </button>
        </div>

        {{-- Overlay gelap saat popup aktif --}}
        @if ($viewState !== 'playing' || $showQuestionModal || $showTimesUpPopup)
            <div class="absolute inset-0 bg-black bg-opacity-50 z-30"></div>
        @endif

        {{-- Tampilan: Popup Aturan --}}
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

        {{-- Tampilan: Popup Pertanyaan (Semua Level) --}}
        @if ($showQuestionModal && $currentQuestion)
            <div class="absolute inset-0 flex items-center justify-center z-50">
                @if ($levelId == 4)
                    {{-- Tampilan Pertanyaan: Level 4 (Teka-Teki Silang) --}}
                    <div class="relative w-[70%] aspect-[4/3]">
                        <img src="{{ asset($currentQuestion['image']) }}" class="w-full h-full object-contain">
                        <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center">

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
                                                {{-- Kotak huruf --}}
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
                    {{-- Tampilan Pertanyaan: Level 2 (Game Menjodohkan) --}}
                    <div class="relative w-[70%] aspect-[4/3]">
                        <canvas id="arrow-canvas"
                            class="absolute top-0 left-0 w-full h-full z-20 pointer-events-none"></canvas>

                        <img src="{{ asset($currentQuestion['image']) }}" alt="Latar Belakang Pertanyaan"
                            class="w-full h-full">

                        <div class="absolute inset-0 flex justify-center items-center z-10">
                            <div class="w-[80%] h-[60%] mt-[8%] flex justify-between items-center gap-x-4 md:gap-x-8">

                                {{-- Kolom Kiri untuk Gambar --}}
                                <div id="image-options"
                                    class="w-1/2 h-full flex flex-col justify-center items-center space-y-1">
                                    @foreach ($matchingGameItems['images'] as $key => $image)
                                        <div class="w-full h-1/4 flex items-center justify-center">
                                            <img src="{{ asset($image) }}" data-key="{{ $key }}"
                                                class="matching-image max-w-full max-h-full object-contain bg-opacity-80 rounded-md cursor-pointer transition-all border-4
        @if (isset($correctPairs[$key])) border-green-500 correct-paired
        @else border-transparent hover:border-blue-400 @endif">
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Kolom Kanan untuk Teks --}}
                                <div id="text-options"
                                    class="w-1/2 h-full flex flex-col justify-center items-center space-y-2">
                                    @foreach ($matchingGameItems['texts'] as $key => $text)
                                        <div data-key="{{ $key }}"
                                            class="matching-text w-full h-1/4 flex items-center justify-center text-center p-1 rounded-md text-[0.6rem] md:text-[0.65rem] lg:text-sm leading-tight border-4 cursor-pointer transition-all
            @if (in_array($key, $correctPairs)) bg-green-200 border-green-500 correct-paired
            @else
            bg-yellow-100 border-yellow-700 hover:border-blue-400 @endif">
                                            {{ $text }}
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                @else
                    {{-- Tampilan Pertanyaan: Level 1 & 3 (Pilihan Ganda) --}}
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

        {{-- Tampilan: Popup Level Selesai --}}
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

        {{-- BARU: Tampilan: Popup Waktu Habis --}}
        @if ($showTimesUpPopup)
            <div class="absolute inset-0 w-full h-full flex items-center justify-center z-50 p-4">

                {{-- Card Popup --}}
                <div x-data="{ show: false }" x-init="$nextTick(() => show = true)" x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-90"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-90"
                    class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-8 text-center flex flex-col items-center">

                    {{-- Ikon --}}
                    <svg class="w-16 h-16 text-red-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>

                    {{-- Judul --}}
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">
                        Waktu Habis!
                    </h2>

                    {{-- Pesan --}}
                    <p class="text-lg text-gray-600 mb-6">
                        Yah, waktumu habis. Jangan khawatir, kamu bisa coba lagi nanti!
                    </p>

                    {{-- Tombol Aksi --}}
                    <button wire:click="backToPetaMisi"
                        class="w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-full transition-all hover:bg-blue-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Kembali ke Peta Misi
                    </button>
                </div>

            </div>
        @endif

        {{-- Tampilan: Layar Refleksi (Khusus setelah Level 4) --}}
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
                @if ($currentReflectionPage === count($reflectionPages))
                    <button wire:click="nextReflectionPage"
                        class="absolute bottom-[8%] right-[25%] w-[25%] h-auto hover:scale-110 transition-transform">
                        <img src="{{ asset('images/utils/tombol-selesai.png') }}" alt="Selesai">
                    </button>
                @else
                    <button wire:click="nextReflectionPage"
                        class="absolute bottom-[8%] right-[25%] w-[25%] h-auto hover:scale-110 transition-transform">
                        <img src="{{ asset('images/utils/tombol-mengerti.png') }}" alt="Mengerti">
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
