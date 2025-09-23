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
            <button wire:click="startGame" class="max-w-[256px] hover:scale-110 transition-transform duration-300">
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
