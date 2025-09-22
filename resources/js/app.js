import './bootstrap';

document.addEventListener('livewire:navigated', () => {
    // Ambil semua elemen audio
    const bgMusic = document.getElementById('background-music');
    const correctSound = document.getElementById('correct-answer-sound');
    const incorrectSound = document.getElementById('incorrect-answer-sound');
    const clickSound = document.getElementById('click-sound');

    // Manajemen state Mute/Unmute
    let isMuted = localStorage.getItem('isMuted') === 'true';

    function setMuted(muted) {
        isMuted = muted;
        localStorage.setItem('isMuted', muted);
        if (bgMusic) {
            bgMusic.muted = muted;
        }
        // Di sini Anda bisa menambahkan logika untuk mengubah ikon volume jika ada
    }

    // Terapkan state mute saat halaman dimuat
    setMuted(isMuted);

    // Coba putar musik latar setelah ada interaksi pertama dari user
    let hasInteracted = false;
    document.body.addEventListener('click', () => {
        if (!hasInteracted && bgMusic) {
            hasInteracted = true;
            bgMusic.play().catch(e => console.error("Autoplay musik dicegah oleh browser."));
        }
    }, { once: true });


    // Fungsi untuk memutar suara
    function playSound(soundElement) {
        if (!isMuted && soundElement) {
            soundElement.currentTime = 0;
            soundElement.play().catch(e => console.error("Gagal memutar suara:", e));
        }
    }

    // Event listener untuk semua tombol volume
    document.querySelectorAll('img[alt="Volume"]').forEach(button => {
        // Periksa apakah listener sudah ada untuk menghindari duplikasi
        if (!button.hasAttribute('data-listener-attached')) {
            button.addEventListener('click', (e) => {
                e.stopPropagation(); // Mencegah trigger suara klik umum
                setMuted(!isMuted);
            });
            button.setAttribute('data-listener-attached', 'true');
        }
    });

    // Event listener untuk suara klik pada tombol
    document.querySelectorAll('button, a').forEach(el => {
        if (!el.hasAttribute('data-click-listener')) {
            el.addEventListener('click', () => {
                // Jangan putar suara klik untuk tombol volume itu sendiri
                if (el.querySelector('img[alt="Volume"]')) return;
                playSound(clickSound);
            });
            el.setAttribute('data-click-listener', 'true');
        }
    });

    // Listener untuk event dari Livewire
    Livewire.on('correct-answer', () => playSound(correctSound));
    Livewire.on('incorrect-answer', () => playSound(incorrectSound));
});
