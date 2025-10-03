{{-- resources/views/livewire/home-page.blade.php --}}
<div class="bg-gray-800 min-h-screen flex items-center justify-center">

    {{-- Aspect Ratio Container: "Kanvas" utama yang menjaga proporsi semua elemen --}}
    <div class="relative w-full max-h-full aspect-video">

        {{-- Gambar Background --}}
        <img src="{{ asset('images/home/background-home.jpg') }}" alt="Background"
            class="absolute top-0 left-0 w-full h-full object-cover z-0">

        {{-- Tombol UI Atas (Ukuran dan Posisi menggunakan Persentase) --}}
        <div class="absolute top-[4%] left-[2%] flex gap-[4%] z-20 w-[15%]">
            <button wire:click="$dispatch('showHelp')" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/help-button.svg') }}" alt="Help">
            </button>
            <button wire:click="$dispatch('showProfile')" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/menu-button.svg') }}" alt="Home">
            </button>
        </div>
        <div class="absolute top-[4%] right-[0%] w-[10%] flex z-20">
            <button class="w-[80%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/volume-button.svg') }}" alt="Volume">
            </button>
        </div>

        {{-- Judul Game (Posisi tunggal dan ukuran font fluid) --}}
        <div class="absolute top-[70%] left-[73%] -translate-x-1/2 -translate-y-1/2 z-20 text-center">
            {{-- Tombol Start biasa jika tidak ada progres --}}
            <button wire:click="startNewGame" class="max-w-[256px] hover:scale-110 transition-transform duration-300">
                <img src="{{ asset('images/home/start-button.svg') }}" alt="Start Game">
            </button>
        </div>

        @if ($showContinuePopup)
            <div class="absolute inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70">
                {{-- Anda bisa mengganti div ini dengan gambar papan/background pop-up jika ada --}}
                <div class="bg-gray-800/80 border-4 border-yellow-300 rounded-2xl p-8 text-white text-center flex flex-col items-center justify-center w-1/3">

                    <h2 class="text-3xl font-bold mb-4 text-yellow-300">Permainan Ditemukan!</h2>

                    <p class="mb-8 text-lg">
                        Kami menemukan progres permainan yang tersimpan. Apakah Anda ingin melanjutkannya?
                    </p>

                    <div class="flex gap-x-6">
                        {{-- Tombol Lanjutkan --}}
                        <button wire:click="continueGame"
                            class="px-6 py-2 bg-blue-600 rounded-full hover:bg-blue-500 transition-colors transform hover:scale-105">
                            <span class="text-xl font-bold">Ya, Lanjutkan</span>
                        </button>

                        {{-- Tombol Mulai Baru --}}
                        <button wire:click="startNewGame"
                            class="px-6 py-2 bg-red-600 rounded-full hover:bg-red-500 transition-colors transform hover:scale-105">
                            <span class="text-xl font-bold">Mulai Baru</span>
                        </button>
                    </div>

                </div>
            </div>
        @endif
    </div>
</div>
