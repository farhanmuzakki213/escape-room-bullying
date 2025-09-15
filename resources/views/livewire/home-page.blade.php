{{-- resources/views/livewire/home-page.blade.php --}}
<div class="bg-gray-800 min-h-screen flex items-center justify-center">

    {{-- Aspect Ratio Container: "Kanvas" utama yang menjaga proporsi semua elemen --}}
    <div class="relative w-full max-h-full aspect-video">

        {{-- Gambar Background --}}
        <img src="{{ asset('images/home/background-home.svg') }}" alt="Background"
            class="absolute top-0 left-0 w-full h-full object-cover z-0">

        {{-- Tombol UI Atas (Ukuran dan Posisi menggunakan Persentase) --}}
        <div class="absolute top-[4%] left-[2%] flex gap-2 z-20">
            <button class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/help-button.svg') }}" alt="Help">
            </button>
            <button class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/menu-button.svg') }}" alt="Home">
            </button>
        </div>
        <div class="absolute top-[4%] right-[2%] flex gap-2 z-20">
            <button class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/volume-button.svg') }}" alt="Volume">
            </button>
        </div>

        {{-- Judul Game (Posisi tunggal dan ukuran font fluid) --}}
        <div class="absolute top-[50%] left-[73%] -translate-x-1/2 -translate-y-1/2 z-20 text-center">
            <h2 class="font-luckiest-guy text-black mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                <span class="font-bold text-2xl md:text-3xl lg:text-5xl xl:text-8xl">
                    ESCAPE GAME
                    <br>
                    BULLYING
                </span>
            </h2>
            <button wire:click="startGame"
                class="w-[50%] max-w-[256px] hover:scale-110 transition-transform duration-300">
                <img src="{{ asset('images/home/start-button.svg') }}" alt="Start Game">
            </button>
        </div>

    </div>
</div>

{{-- Script dipindahkan ke luar div utama untuk kebersihan kode, tidak mempengaruhi fungsi --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        const savedProgress = JSON.parse(localStorage.getItem('gameProgress'));

        if (savedProgress) {
            console.log('Progres ditemukan:', savedProgress);
            @this.call('loadProgress', savedProgress);
        } else {
            console.log('Tidak ada progres tersimpan. Memulai game baru.');
        }
    });
</script>
