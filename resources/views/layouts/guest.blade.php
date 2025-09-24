{{-- resources/views/layouts/game.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('images/logo/auth-logo128.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <audio id="background-music" loop preload="auto">
        <source src="{{ asset('suara/sound-latar-game.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="correct-answer-sound" preload="auto">
        <source src="{{ asset('suara/jwban-yg-benar.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="incorrect-answer-sound" preload="auto">
        <source src="{{ asset('suara/jawaban-salah.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="click-sound" preload="auto">
        <source src="{{ asset('suara/untuk-klik-tombol.mp3') }}" type="audio/mpeg">
    </audio>
    {{ $slot }}
    @livewireScripts
    <div id="notification-container" class="fixed top-5 right-5 z-[100] flex flex-col gap-2"></div>
</body>

</html>
