<div class="bg-gray-800 min-h-screen flex items-center justify-center">

    {{-- Aspect Ratio Container --}}
    <div class="relative w-full max-h-full aspect-video">

        {{-- Gambar Background Dinamis --}}
        <img src="{{ asset($currentBackground) }}" alt="Start Sequence Background"
            class="absolute top-0 left-0 w-full h-full object-cover z-0">

        {{-- Tombol UI Atas (Sesuai permintaan: Help, Menu, Volume, Home) --}}
        <div class="absolute top-[4%] left-[2%] flex gap-[4%] z-20 w-[15%]">
            {{-- Tombol Help --}}
            <button wire:click="$dispatch('showHelp')" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/help-button.svg') }}" alt="Help">
            </button>
            {{-- Tombol Menu/Profile --}}
            <button wire:click="$dispatch('showProfile')" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/menu-button.svg') }}" alt="Menu">
            </button>
        </div>
        <div class="absolute top-[4%] right-[1%] flex gap-[4%] z-20 w-[15%]">
            {{-- Tombol Volume --}}
            <button class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/volume-button.svg') }}" alt="Volume">
            </button>
            {{-- Tombol Home --}}
            <button wire:click="goHome" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/home-button.svg') }}" alt="Home">
            </button>
        </div>

        {{-- Tombol Navigasi Step --}}

        @if ($step === 1)
            {{-- Tombol Mengerti (Step 1) - Tengah Bawah Papan --}}
            {{-- Sesuaikan 'bottom' dan 'left' di sini jika perlu --}}
            <button wire:click="nextStep"
                class="absolute bottom-[8%] right-[25%] w-[25%] h-auto hover:scale-110 transition-transform">
                <img src="{{ asset('images/utils/tombol-mengerti.png') }}" alt="Mengerti">
            </button>
        @elseif ($step === 2)
            {{-- Tombol Next (Step 2) - Kanan Bawah --}}
                <button wire:click="nextStep" class="absolute bottom-[9%] right-[3%] w-[20%] h-auto hover:scale-110 transition-transform">
                    <img src="{{ asset('images/utils/tombol-next.png') }}" alt="Next">
                </button>
        @elseif ($step === 3)
            {{-- Tombol Next (Step 3) -> Finish - Kanan Bawah --}}
                <button wire:click="finishSequence" class="absolute bottom-[9%] right-[3%] w-[20%] h-auto hover:scale-110 transition-transform">
                    <img src="{{ asset('images/home/tombol-bermain.svg') }}" alt="Next">
                </button>
        @endif

    </div>
</div>
