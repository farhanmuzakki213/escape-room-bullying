import './bootstrap';

// --- Elemen Audio Global ---
const bgMusic = document.getElementById('background-music');
const correctSound = document.getElementById('correct-answer-sound');
const incorrectSound = document.getElementById('incorrect-answer-sound');
const clickSound = document.getElementById('click-sound');

// --- State Audio ---
let isMuted = localStorage.getItem('isMuted') === 'true';
let hasInteracted = false;

// --- Fungsi Pengontrol Audio ---

/**
 * Mengatur status mute/unmute untuk semua suara dan menyimpannya.
 * @param {boolean} muted
 */
function setMuted(muted) {
    isMuted = muted;
    localStorage.setItem('isMuted', String(muted));
    if (bgMusic) bgMusic.muted = muted;
}

/**
 * Memainkan elemen suara jika tidak dalam mode mute.
 * @param {HTMLAudioElement} soundElement
 */
function playSound(soundElement) {
    if (!isMuted && soundElement) {
        soundElement.currentTime = 0;
        soundElement.play().catch(e => console.error("Gagal memutar suara:", e));
    }
}

// --- Inisialisasi dan Event Listener Utama ---

// 1. Atur status mute segera setelah skrip dimuat
setMuted(isMuted);

// 2. Mainkan musik latar HANYA pada interaksi pertama user dengan halaman
document.body.addEventListener('click', () => {
    if (!hasInteracted && bgMusic && bgMusic.paused) {
        hasInteracted = true;
        bgMusic.play().catch(e => console.error("Autoplay musik dicegah oleh browser."));
    }
}, { once: true }); // Opsi { once: true } memastikan ini hanya berjalan sekali

// 3. EVENT DELEGATION: Satu listener utama untuk menangani semua klik
document.addEventListener('click', function(event) {
    // Cari elemen <button> atau <a> terdekat dari elemen yang diklik (event.target)
    // Ini penting agar suara tetap berbunyi meskipun yang diklik adalah <img> di dalam <button>
    const button = event.target.closest('button, a');

    // Jika yang diklik bukan bagian dari sebuah tombol atau link, hentikan fungsi
    if (!button) {
        return;
    }

    // Cek apakah ini tombol volume
    const isVolumeButton = button.querySelector('img[alt="Volume"]');

    if (isVolumeButton) {
        // Jika ya, atur status mute dan jangan mainkan suara klik
        setMuted(!isMuted);
    } else {
        // Jika tidak, mainkan suara klik untuk semua tombol lainnya
        playSound(clickSound);
    }
});

// 4. Listener global untuk efek suara dari event Livewire (tidak berubah)
Livewire.on('correct-answer', () => playSound(correctSound));
Livewire.on('incorrect-answer', () => playSound(incorrectSound));
