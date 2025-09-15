<div class="bg-gray-800 min-h-screen flex items-center justify-center">

    <div class="relative w-full max-h-full aspect-video">

        {{-- Background Utama Level --}}
        <img src="{{ $backgroundUrl }}" alt="Background Level {{ $levelId }}" class="absolute top-0 left-0 w-full h-full object-cover z-0">

        {{-- Tombol UI Atas --}}
        <div class="absolute top-[4%] left-[2%] flex gap-2 z-20">
            <button class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/help-button.svg') }}" alt="Help">
            </button>
        </div>
        <div class="absolute top-[4%] right-[2%] flex gap-2 z-20">
            <button class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/volume-button.svg') }}" alt="Volume">
            </button>
            <button wire:click="backToPetaMisi" class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/home-button.svg') }}" alt="Kembali ke Peta Misi">
            </button>
        </div>

        {{-- Judul Level (Opsional, bisa dihapus jika tidak perlu) --}}
        <div class="absolute top-[10%] left-1/2 -translate-x-1/2 text-center z-10">
            <h1 class="font-luckiest-guy text-xl md:text-3xl text-white bg-black bg-opacity-50 px-4 py-2 rounded-lg">
                {{ $levelConfig['title'] }}
            </h1>
        </div>

        {{-- Loop untuk menampilkan semua objek interaktif --}}
        @foreach ($levelConfig['objects'] as $objectName => $objectData)
            <button wire:click="objectClicked('{{ $objectName }}')"
                class="absolute transition-transform z-10 -translate-x-1/2 -translate-y-1/2"
                style="{{ $objectData['style'] }}">
                <img src="{{ asset($objectData['image']) }}" alt="{{ $objectData['alt'] }}">
            </button>
        @endforeach

        @if ($showQuestionModal && $currentQuestion)
            <div
                class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4 transition-opacity"
                x-data="{ show: @entangle('showQuestionModal') }" x-show="show" x-transition.opacity>

                {{-- Kotak Konten Popup --}}
                <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 w-full max-w-2xl text-center transform transition-transform"
                    x-show="show" x-transition.scale.duration.300ms>

                    {{-- Isi Soal --}}
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-6">
                        {{ $currentQuestion['text'] }}
                    </h2>

                    {{-- Pilihan Jawaban --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        @foreach ($currentQuestion['options'] as $key => $option)
                            <button wire:click.prevent="selectAnswer('{{ $key }}')"
                                @disabled($feedbackMessage)
                                class="w-full p-4 rounded-lg border-2 border-blue-500 text-blue-700 font-semibold hover:bg-blue-100 transition-colors disabled:opacity-50 disabled:hover:bg-transparent">
                                <span class="font-bold">{{ $key }}.</span> {{ $option }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Feedback Message --}}
                    @if ($feedbackMessage)
                        <div class="mt-4 p-3 rounded-lg font-semibold {{ str_contains($feedbackMessage, 'Benar') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $feedbackMessage }}
                        </div>
                    @endif


                    {{-- Tombol Tutup --}}
                    <button wire:click.prevent="closeModal"
                        class="mt-8 px-8 py-3 bg-gray-600 text-white font-bold rounded-full hover:bg-gray-700 transition-transform hover:scale-105">
                        Tutup
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
