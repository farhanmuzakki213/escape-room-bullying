<div class="bg-gray-800 min-h-screen flex items-center justify-center overflow-hidden">

    {{-- STATE 1: POPUP ATURAN AWAL --}}
    @if ($viewState === 'rules_popup')
        <div class="relative w-full max-h-full aspect-video flex items-center justify-center"
            style="background-image: url('{{ asset($backgroundUrl) }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            <div class="relative w-[60%] aspect-[4/3]">
                <img src="{{ asset($levelConfig['assets']['rules_board']) }}" class="w-full h-full">
                <div class="absolute top-[20%] left-[36%] w-[60%] h-[55%] flex items-center justify-center p-2">
                    <p class="text-center font-semibold text-gray-800 md:text-xl lg:text-2xl">
                        {{ $levelConfig['rules']['popup_text'] }}</p>
                </div>
                {{-- Tombol Navigasi --}}
                <button wire:click="backToPetaMisi"
                    class="absolute bottom-[8%] left-[8%] w-[15%] h-auto hover:scale-110 transition-transform">
                    <img src="{{ asset('images/petunjuk/panah-kiri-button.svg') }}" alt="Kembali">
                </button>
                <button wire:click="showRulesBackground"
                    class="absolute bottom-[8%] right-[8%] w-[15%] h-auto hover:scale-110 transition-transform">
                    <img src="{{ asset('images/petunjuk/panah-kanan-button.svg') }}" alt="Lanjut">
                </button>
            </div>
        </div>
    @endif

    {{-- STATE 2: HALAMAN LATAR ATURAN --}}
    @if ($viewState === 'rules_background')
        <div class="relative w-full max-h-full aspect-video flex items-center justify-center"
            style="background-image: url('{{ asset($backgroundUrl) }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            <div class="relative w-[60%] aspect-[4/3]">
                <img src="{{ asset($levelConfig['assets']['rules_board']) }}" class="w-full h-full">
                <div class="absolute top-[20%] left-[36%] w-[60%] h-[55%] flex items-center justify-center p-2">
                    <p class="text-center font-semibold text-gray-800 md:text-xl lg:text-2xl">
                        {{ $levelConfig['rules']['background_text'] }}</p>
                </div>
                {{-- Tombol Navigasi --}}
                <button wire:click="backToRulesPopup"
                    class="absolute bottom-[8%] left-[8%] w-[15%] h-auto hover:scale-110 transition-transform">
                    <img src="{{ asset('images/petunjuk/panah-kiri-button.svg') }}" alt="Kembali">
                </button>
                <button wire:click="startGameplay"
                    class="absolute bottom-[8%] right-[8%] w-[15%] h-auto hover:scale-110 transition-transform">
                    <img src="{{ asset('images/petunjuk/panah-kanan-button.svg') }}" alt="Mulai Bermain">
                </button>
            </div>
        </div>
    @endif

    {{-- STATE 3: GAMEPLAY UTAMA --}}
    @if ($viewState === 'playing')
        <div class="relative w-full max-h-full aspect-video">
            {{-- Latar Belakang Game --}}
            <img src="{{ $backgroundUrl }}" class="absolute top-0 left-0 w-full h-full object-cover z-0">

            {{-- Tombol UI Game --}}
            <div class="absolute top-[4%] right-[2%] flex gap-2 z-20">
                <button wire:click="backToPetaMisi"
                    class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform">
                    <img src="{{ asset('images/petamisi/home-button.svg') }}" alt="Kembali ke Peta Misi">
                </button>
            </div>

            {{-- Objek Interaktif --}}
            @foreach ($levelConfig['objects'] as $objectName => $objectData)
                <button wire:click="objectClicked('{{ $objectName }}')"
                    class="absolute transition-transform z-10 -translate-x-1/2 -translate-y-1/2 {{ in_array($objectName, $answeredObjects) ? 'opacity-50 cursor-default' : '' }}"
                    style="{{ $objectData['style'] }}" @disabled(in_array($objectName, $answeredObjects))>
                    <img src="{{ asset($objectData['image']) }}" alt="{{ $objectData['alt'] }}">
                </button>
            @endforeach

            {{-- POPUP SOAL (di dalam state 'playing') --}}
            @if ($showQuestionModal && $currentQuestion)
                <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
                    <div class="relative w-[70%] aspect-[4/3]">
                        <img src="{{ asset($levelConfig['assets']['question_board']) }}" class="w-full h-full">
                        <div class="absolute inset-0 p-[12%] flex flex-col items-center justify-start text-center">
                            {{-- Soal --}}
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-6">{{ $currentQuestion['text'] }}
                            </h2>
                            {{-- Pilihan Jawaban --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                                @foreach ($currentQuestion['options'] as $key => $option)
                                    <button wire:click.prevent="selectAnswer('{{ $key }}')"
                                        @disabled($feedbackMessage && str_contains($feedbackMessage, 'Benar'))
                                        class="p-3 rounded-lg border-2 border-purple-500 text-purple-700 font-semibold hover:bg-blue-100 disabled:opacity-70">
                                        {{ $key }}. {{ $option }}
                                    </button>
                                @endforeach
                            </div>
                            {{-- Feedback & Tombol Lanjut --}}
                            @if ($feedbackMessage)
                                <div
                                    class="mt-4 font-semibold {{ str_contains($feedbackMessage, 'Benar') ? 'text-green-700' : 'text-red-700' }}">
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
        </div>
    @endif

    {{-- STATE 4: POPUP LEVEL SELESAI --}}
    @if ($viewState === 'level_complete_popup')
        <div class="relative w-full max-h-full aspect-video flex items-center justify-center"
            style="background-image: url('{{ asset($backgroundUrl) }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            <div class="relative w-[60%] aspect-[4/3]">
                <img src="{{ asset($levelConfig['assets']['rules_board']) }}" class="w-full h-full">
                <div class="absolute top-[28%] left-[35%] w-[63%] h-[40%] flex items-center justify-center p-2">
                    <p class="text-center font-semibold text-gray-800 md:text-xl lg:text-2xl">
                        {{ $levelConfig['rules']['completion_text'] }}</p>
                </div>
                {{-- Tombol Exit --}}
                <button wire:click="completeLevelAndExit"
                    class="absolute top-[5%] right-[-5%] w-[12%] h-auto hover:scale-110 transition-transform text-gray-700">
                    <img src="{{ asset('images/home/exit-button.svg') }}" alt="Home">
                </button>
            </div>
        </div>
    @endif

</div>
