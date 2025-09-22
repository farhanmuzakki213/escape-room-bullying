<div class="bg-gray-800 min-h-screen flex items-center justify-center">

    <div class="relative w-full max-h-full aspect-video">

        <img src="{{ asset('images/petamisi/background-peta-misi.svg') }}" alt="Background Peta Misi"
            class="absolute top-0 left-0 w-full h-full object-cover z-0">

        {{-- Tombol UI Atas --}}
        <div class="absolute top-[4%] left-[2%] w-[15%] flex z-20">
            <button wire:click="$dispatch('showHelp')" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/help-button.svg') }}" class="w-[80%]" alt="Help">
            </button>
        </div>
        <div class="absolute top-[4%] right-[1%] flex gap-[4%] z-20 w-[15%]">
            <button class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/volume-button.svg') }}" alt="Volume">
            </button>
            <button wire:click="goHome" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/home-button.svg') }}" alt="Home">
            </button>
        </div>

        {{-- GRUP LEVEL 1 --}}
        <div
            class="absolute top-[66%] left-[10%] -translate-x-1/2 -translate-y-1/2 flex items-center gap-[2%] z-20 w-[15%]">
            <img src="{{ asset('images/petamisi/number-1.svg') }}" alt="Nomor 1" class="w-[25%] pointer-events-none">
            <button wire:click="selectLevel(1)" class="w-[70%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/meja-belajar.svg') }}" alt="Level 1: Kelas">
            </button>
        </div>

        {{-- GRUP LEVEL 2 --}}
        <div
            class="absolute top-[40%] left-[28%] -translate-x-1/2 -translate-y-1/2 flex items-center gap-[2%] z-20 w-[15%]">
            <img src="{{ asset('images/petamisi/number-2.svg') }}" alt="Nomor 2"
                class="w-[25%] pointer-events-none {{ $unlockedLevel < 2 ? 'grayscale' : '' }}"
                @disabled($unlockedLevel < 2)>
            <button wire:click="selectLevel(2)" class="w-[65%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/rak-buku.svg') }}" alt="Level 2: Perpustakaan"
                    class="{{ $unlockedLevel < 2 ? 'grayscale' : '' }}">
            </button>
            @if ($unlockedLevel < 2)
                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                </div>
            @endif
        </div>

        {{-- GRUP LEVEL 3 --}}
        <div
            class="absolute top-[64%] left-[43%] -translate-x-1/2 -translate-y-1/2 flex items-center gap-[2%] z-20 w-[15%]">
            <img src="{{ asset('images/petamisi/number-3.svg') }}" alt="Nomor 3" class="w-[25%] pointer-events-none">
            <button wire:click="selectLevel(3)" class="w-[70%] hover:scale-110 transition-transform"
                {{ $unlockedLevel < 3 ? 'grayscale' : '' }}" @disabled($unlockedLevel < 3)>
                <img src="{{ asset('images/petamisi/locker-bola.svg') }}" alt="Level 3: Lorong"
                    class="{{ $unlockedLevel < 3 ? 'grayscale' : '' }}">
            </button>
            @if ($unlockedLevel < 3)
                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                </div>
            @endif
        </div>

        {{-- GRUP LEVEL 4 --}}
        <div
            class="absolute top-[83%] left-[60%] -translate-x-1/2 -translate-y-1/2 flex items-center gap-[2%] z-20 w-[15%]">
            <img src="{{ asset('images/petamisi/number-4.svg') }}" alt="Nomor 4" class="w-[25%] pointer-events-none">
            <button wire:click="selectLevel(4)" class="w-[60%] hover:scale-110 transition-transform"
                {{ $unlockedLevel < 4 ? 'grayscale' : '' }}" @disabled($unlockedLevel < 4)>
                <img src="{{ asset('images/petamisi/tiang-bendera-v1.svg') }}" alt="Level 4: Lapangan"
                    class="{{ $unlockedLevel < 4 ? 'grayscale' : '' }}">
            </button>
            @if ($unlockedLevel < 4)
                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                </div>
            @endif
        </div>

    </div>
</div>
