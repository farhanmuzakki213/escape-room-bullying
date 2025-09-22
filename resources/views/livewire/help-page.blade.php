<div class="bg-gray-800 min-h-screen flex items-center justify-center">
    <div class="relative w-full max-h-full aspect-video">
        <img src="{{ asset('images/petunjuk/background-petunjuk.svg') }}" alt="Petunjuk"
            class="absolute top-0 left-0 w-full h-full object-cover z-0">

        {{-- Tombol UI pada Layar Bantuan --}}
        <div class="absolute top-[4%] right-[1%] flex gap-[4%] z-20 w-[15%]">
            <button class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/home/volume-button.svg') }}" alt="Volume">
            </button>
            <button wire:click="back" class="w-[60%] hover:scale-110 transition-transform">
                <img src="{{ asset('images/petamisi/home-button.svg') }}" alt="Kembali">
            </button>
        </div>
    </div>
</div>
