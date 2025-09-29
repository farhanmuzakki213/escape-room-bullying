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
document.addEventListener('click', function (event) {
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

/**
 * Menampilkan notifikasi mengambang.
 * @param {string} message Pesan notifikasi.
 * @param {string} type 'success' untuk hijau, 'error' untuk merah.
 */
function showNotification(message, type = 'success') {
    const container = document.getElementById('notification-container');
    if (!container) return;

    const notification = document.createElement('div');

    // Logika ini memastikan 'bgColor' selalu mendapatkan nilai yang benar.
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

    const textColor = 'text-white';
    const padding = 'p-4';
    const margin = 'mb-2';
    const rounded = 'rounded-lg';
    const shadow = 'shadow-lg';
    const transition = 'transition-all duration-300 ease-in-out';

    // Baris ini sangat rentan terhadap typo. Versi di bawah ini sudah dipastikan benar.
    notification.className = `${padding} ${margin} ${textColor} ${bgColor} ${rounded} ${shadow} ${transition} transform translate-x-full opacity-0 relative flex items-center justify-between min-w-[250px] max-w-sm`;

    notification.innerHTML = `
        <span class="mr-4">${message}</span>
        <button class="flex-shrink-0 text-white hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50" aria-label="Close">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;

    container.appendChild(notification);

    requestAnimationFrame(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    });

    // Kode timer dan tombol close akan berjalan dengan benar setelah `className` diperbaiki.
    const dismissTimeout = setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full', 'opacity-0');
            notification.addEventListener('transitionend', () => notification.remove(), { once: true });
        }
    }, 1000);

    notification.querySelector('button').addEventListener('click', () => {
        clearTimeout(dismissTimeout);
        notification.classList.add('translate-x-full', 'opacity-0');
        notification.addEventListener('transitionend', () => notification.remove(), { once: true });
    });
}

// Listener ini sudah benar, akan meneruskan 'type' dengan benar.
Livewire.on('show-notification', ({ message, type }) => {
    if (message) {
        showNotification(message, type);
    }
});
document.addEventListener('livewire:initialized', () => {

    /**
     * ===================================================================
     * FUNGSI PROGRES GAME (dari home-page.blade.php dan guest.blade.php)
     * ===================================================================
     */

    // Saat halaman utama dimuat, periksa local storage untuk progres game.
    // Kita hanya menjalankan ini jika komponen home-page ada di halaman.
    if (document.querySelector('livewire\\:home-page')) {
        const savedProgress = JSON.parse(localStorage.getItem('gameProgress'));
        if (savedProgress) {
            console.log('Progres ditemukan di localStorage:', savedProgress);
            // Kirim progres yang ditemukan ke komponen Livewire 'home-page'
            Livewire.dispatchTo('home-page', 'loadProgress', { progress: savedProgress });
        }
    }

    // Listener global untuk menghapus progres dari local storage.
    // Dipicu oleh GameManager saat 'startNewGame' dijalankan.
    Livewire.on('clear-local-storage', () => {
        localStorage.removeItem('gameProgress');
        console.log('Progres di local storage telah dihapus.');
    });

    // Listener global untuk menyimpan progres ke local storage.
    // Dipicu oleh GameManager setiap kali progres perlu disimpan.
    Livewire.on('save-progress-to-local-storage', (event) => {
        const progress = event.progress; // Akses data dari event Livewire 3
        if (progress) {
            localStorage.setItem('gameProgress', JSON.stringify(progress));
            console.log('Progres disimpan ke local storage.', progress);
        }
    });
});
