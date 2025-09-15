<div class="relative w-screen h-screen overflow-hidden">

    <img src="{{ $backgroundUrl }}" alt="Background Level {{ $levelId }}" class="absolute top-0 left-0 w-full h-full object-cover z-0">

    <div class="absolute top-3 left-3 md:top-4 md:left-4 flex gap-2 md:gap-3 z-20">
        <button class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform"><img
                src="{{ asset('images/home/help-button.svg') }}" alt="Help"></button>
    </div>
    <div class="absolute top-3 right-3 md:top-4 md:right-4 flex gap-2 md:gap-3 z-20">
        <button class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform"><img
                src="{{ asset('images/home/volume-button.svg') }}" alt="Volume"></button>
        <button wire:click="backToPetaMisi" class="w-12 h-12 md:w-16 md:h-16 hover:scale-110 transition-transform">
            <img src="{{ asset('images/petamisi/home-button.svg') }}" alt="Kembali ke Peta Misi">
        </button>
    </div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <h1 class="font-luckiest-guy text-5xl text-white bg-black bg-opacity-50 px-4 py-2 rounded-lg">
            Anda di Level {{ $levelId }}: {{ $levelData[$levelId]['title'] }}
        </h1>
    </div>

</div>
