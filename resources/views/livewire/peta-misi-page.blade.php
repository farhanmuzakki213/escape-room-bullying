
<div class="bg-gray-800 min-h-screen flex items-center justify-center">

    <div class="relative w-full max-h-full aspect-video">

        <img src="{{ asset('images/petamisi/background-peta-misi.svg') }}" alt="Background Peta Misi"
            class="absolute top-0 left-0 w-full h-full object-cover z-0">

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
            <button wire:click="goHome" class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/home-button.svg') }}" alt="Home">
            </button>
        </div>

        <div class="absolute top-[10%] left-1/2 -translate-x-1/2 w-full text-center z-20">
            <h2 class="font-luckiest-guy font-bold text-black" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                <span class="text-1xl md:text-3xl lg:text-4xl xl:text-6xl">
                    PETA MISI: ESCAPE THE SILENCE
                </span>
            </h2>
        </div>

        {{-- GRUP LEVEL 1 --}}
        <div
            class="absolute top-[65%] left-[10%] -translate-x-1/2 -translate-y-1/2 flex items-center gap-[2%] z-20 w-[15%]">
            <img src="{{ asset('images/petamisi/number-1.svg') }}" alt="Nomor 1" class="w-[30%] pointer-events-none">
            <button wire:click="selectLevel(1)" class="w-[70%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/meja-belajar.svg') }}" alt="Level 1: Kelas">
            </button>
        </div>

        {{-- GRUP LEVEL 2 --}}
        <div
            class="absolute top-[50%] left-[28%] -translate-x-1/2 -translate-y-1/2 flex items-center gap-[2%] z-20 w-[15%]">
            <img src="{{ asset('images/petamisi/number-2.svg') }}" alt="Nomor 2" class="w-[30%] pointer-events-none">
            <button wire:click="selectLevel(2)" class="w-[65%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/rak-buku.svg') }}" alt="Level 2: Perpustakaan">
            </button>
        </div>

        {{-- GRUP LEVEL 3 --}}
        <div
            class="absolute top-[68%] left-[45%] -translate-x-1/2 -translate-y-1/2 flex items-center gap-[2%] z-20 w-[15%]">
            <img src="{{ asset('images/petamisi/number-3.svg') }}" alt="Nomor 3" class="w-[30%] pointer-events-none">
            <button wire:click="selectLevel(3)" class="w-[70%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/locker-bola.svg') }}" alt="Level 3: Lorong">
            </button>
        </div>

        {{-- GRUP LEVEL 4 --}}
        <div
            class="absolute top-[85%] left-[60%] -translate-x-1/2 -translate-y-1/2 flex items-center gap-[2%] z-20 w-[15%]">
            <img src="{{ asset('images/petamisi/number-4.svg') }}" alt="Nomor 4" class="w-[30%] pointer-events-none">
            <button wire:click="selectLevel(4)" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/tiang-bendera-v1.svg') }}" alt="Level 4: Lapangan">
            </button>
        </div>

    </div>
</div>
