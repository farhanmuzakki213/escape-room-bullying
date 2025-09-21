<div class="bg-gray-800 min-h-screen flex items-center justify-center overflow-hidden">
    <div class="relative w-full max-h-full aspect-video">
        {{-- Latar Belakang Game --}}
        <img src="{{ $backgroundUrl }}" class="absolute top-0 left-0 w-full h-full object-cover z-0">

        {{-- Objek Interaktif --}}
        @foreach ($levelConfig['objects'] as $objectName => $objectData)
            <button wire:click="objectClicked('{{ $objectName }}')"
                class="absolute transition-transform z-10 -translate-x-1/2 -translate-y-1/2 {{ in_array($objectName, $answeredObjects) ? 'opacity-50 cursor-default' : '' }}"
                style="{{ $objectData['style'] }}" @disabled(in_array($objectName, $answeredObjects))>
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
                <div class="relative w-[60%] aspect-[4/3]">
                    <img src="{{ asset($levelConfig['assets']['rules_board']) }}" class="w-full h-full">
                    <div class="absolute top-[20%] left-[36%] w-[60%] h-[55%] flex items-center justify-center p-2">
                        <p class="text-center font-semibold text-gray-800 md:text-xl lg:text-2xl">
                            {{ $levelConfig['rules']['popup_text'] }}</p>
                    </div>
                    {{-- Tombol Navigasi --}}
                    <button wire:click="backToPetaMisi"
                        class="absolute bottom-[8%] left-[42%] w-[15%] h-auto hover:scale-110 transition-transform">
                        <img src="{{ asset('images/petunjuk/panah-kiri-button.svg') }}" alt="Kembali">
                    </button>
                    <button wire:click="showRulesBackground"
                        class="absolute bottom-[8%] right-[22%] w-[15%] h-auto hover:scale-110 transition-transform">
                        <img src="{{ asset('images/petunjuk/panah-kanan-button.svg') }}" alt="Lanjut">
                    </button>
                </div>
            </div>
        @endif

        {{-- STATE 2: HALAMAN LATAR ATURAN --}}
        @if ($viewState === 'rules_background')
            <div class="absolute inset-0 w-full h-full flex items-center justify-center z-40">
                <div class="relative w-[60%] aspect-[4/3]">
                    <img src="{{ asset($levelConfig['assets']['rules_board']) }}" class="w-full h-full">
                    <div class="absolute top-[20%] left-[36%] w-[60%] h-[55%] flex items-center justify-center p-2">
                        <p class="text-center font-semibold text-gray-800 md:text-xl lg:text-2xl">
                            {{ $levelConfig['rules']['background_text'] }}</p>
                    </div>
                    {{-- Tombol Navigasi --}}
                    <button wire:click="backToRulesPopup"
                        class="absolute bottom-[8%] left-[42%] w-[15%] h-auto hover:scale-110 transition-transform">
                        <img src="{{ asset('images/petunjuk/panah-kiri-button.svg') }}" alt="Kembali">
                    </button>
                    <button wire:click="startGameplay"
                        class="absolute bottom-[8%] right-[22%] w-[15%] h-auto hover:scale-110 transition-transform">
                        <img src="{{ asset('images/petunjuk/panah-kanan-button.svg') }}" alt="Mulai Bermain">
                    </button>
                </div>
            </div>
        @endif

        {{-- POPUP SOAL (di dalam state 'playing') --}}
        @if ($showQuestionModal && $currentQuestion)
            <div class="absolute inset-0 flex items-center justify-center z-50">
                <div class="relative w-[70%] aspect-[4/3]">
                    <img src="{{ asset($currentQuestion['image']) }}" class="w-full h-full object-contain">

                    <div class="absolute inset-0 flex flex-col items-center justify-end p-[12%]">
                        {{-- Pilihan Jawaban --}}
                        @if ($levelId == 2)
                            {{-- Layout Grid 2x2 untuk Level 2 (4 Pilihan) --}}
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
                            {{-- Layout Segitiga untuk Level 1 & 3 (3 Pilihan) --}}
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
                        @if ($feedbackMessage)
                            <div
                                class="mt-4 font-semibold text-lg {{ str_contains($feedbackMessage, 'Benar') ? 'text-green-400' : 'text-red-400' }}">
                                {{ $feedbackMessage }}
                            </div>
                            @if (str_contains($feedbackMessage, 'Benar'))
                                <button wire:click="closeModalAndCheckCompletion"
                                    class="mt-4 px-6 py-2 bg-green-500 text-white rounded-full hover:bg-green-600">Lanjut</button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- STATE 4: POPUP LEVEL SELESAI --}}
        @if ($viewState === 'level_complete_popup')
            <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center z-40">
                <div class="relative w-[60%] aspect-[4/3]">
                    <img src="{{ asset($levelConfig['assets']['rules_board']) }}" class="w-full h-full">
                    <div class="absolute top-[28%] left-[35%] w-[63%] h-[40%] flex items-center justify-center p-2">
                        <p class="text-center font-semibold text-gray-800 md:text-xl lg:text-2xl">
                            {{ $completionTextPages[$currentCompletionTextPage] ?? '' }}
                        </p>
                    </div>
                    {{-- Tombol Navigasi Kiri --}}
                    @if ($currentCompletionTextPage > 0)
                        <button wire:click="previousCompletionPage"
                            class="absolute bottom-[8%] left-[42%] w-[15%] h-auto hover:scale-110 transition-transform">
                            <img src="{{ asset('images/petunjuk/panah-kiri-button.svg') }}" alt="Kembali">
                        </button>
                    @endif

                    {{-- Tombol Navigasi Kanan atau Tombol Silang --}}
                    @if ($currentCompletionTextPage < count($completionTextPages) - 1)
                        <button wire:click="nextCompletionPage"
                            class="absolute bottom-[8%] right-[22%] w-[15%] h-auto hover:scale-110 transition-transform">
                            <img src="{{ asset('images/petunjuk/panah-kanan-button.svg') }}" alt="Lanjut">
                        </button>
                    @else
                        <button wire:click="completeLevelAndExit"
                            class="absolute top-[5%] right-[-5%] w-[12%] h-auto hover:scale-110 transition-transform text-gray-700">
                            <img src="{{ asset('images/home/exit-button.svg') }}" alt="Selesai">
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
