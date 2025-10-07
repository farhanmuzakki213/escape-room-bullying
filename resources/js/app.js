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

    // -- BAGIAN BARU: Mencegah notifikasi duplikat --
    // Cek apakah sudah ada notifikasi dengan pesan yang sama
    const existingNotif = container.querySelector(`[data-message="${message}"]`);
    if (existingNotif) {
        // Jika ada, jangan tampilkan notifikasi baru
        return;
    }
    // -- AKHIR BAGIAN BARU --

    const notification = document.createElement('div');

    // -- BAGIAN BARU: Tambahkan atribut data-message --
    notification.setAttribute('data-message', message);
    // -- AKHIR BAGIAN BARU --

    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    notification.className = `p-4 mb-2 text-white ${bgColor} rounded-lg shadow-lg transition-all duration-300 ease-in-out transform translate-x-full opacity-0 relative flex items-center justify-between min-w-[250px] max-w-sm`;

    notification.innerHTML = `
        <span class="mr-4">${message}</span>
        <button class="flex-shrink-0 text-white hover:text-gray-100 focus:outline-none" aria-label="Close">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    `;

    container.appendChild(notification);

    requestAnimationFrame(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    });

    const dismissTimeout = setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full', 'opacity-0');
            notification.addEventListener('transitionend', () => notification.remove(), { once: true });
        }
    }, 3000);

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

    // --- FUNGSI PROGRES GAME ---
    if (document.querySelector('livewire\\:home-page')) {
        const savedProgress = JSON.parse(localStorage.getItem('gameProgress'));
        if (savedProgress) {
            Livewire.dispatchTo('home-page', 'loadProgress', { progress: savedProgress });
        }
    }
    Livewire.on('clear-local-storage', () => {
        localStorage.removeItem('gameProgress');
    });
    Livewire.on('save-progress-to-local-storage', (event) => {
        const progress = event.progress;
        if (progress) {
            localStorage.setItem('gameProgress', JSON.stringify(progress));
        }
    });

    const observer = new MutationObserver(() => {
        const canvas = document.getElementById('arrow-canvas');
        // Cek apakah canvas ada di DOM dan BELUM diinisialisasi
        if (canvas && !canvas.getAttribute('data-initialized')) {
            // Hapus semua panah yang mungkin tersisa dari pertanyaan sebelumnya
            const drawnArrows = new Map();
            initializeArrowCanvas(canvas, drawnArrows);
            // Tandai sebagai sudah diinisialisasi agar tidak berjalan dua kali untuk modal yang sama
            canvas.setAttribute('data-initialized', 'true');
        } else if (!canvas) {
            // Jika canvas hilang (modal ditutup), cari penanda dan hapus
            // agar bisa diinisialisasi lagi saat modal berikutnya muncul.
            const oldCanvas = document.querySelector('[data-initialized="true"]');
            if (oldCanvas) oldCanvas.removeAttribute('data-initialized');
        }
    });

    // Amati perubahan pada seluruh body untuk mendeteksi kapan modal muncul atau hilang
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    // AKHIR BLOK PERUBAHAN


    // (BARU): Event listener untuk menggambar panah yang benar dari Livewire
    Livewire.on('draw-correct-arrow', ([data]) => {
        const canvas = document.getElementById('arrow-canvas');
        if (canvas) {
            // Pastikan fungsi global drawCorrectArrow dipanggil dengan benar
            if(window.drawCorrectArrow) {
               window.drawCorrectArrow(canvas, data.startKey, data.endKey, data.initialLoad);
            }
        }
    });


    // (DIUBAH) Pindahkan drawnArrows ke luar fungsi agar bisa diakses
    // dan di-reset oleh observer
    function initializeArrowCanvas(canvas, drawnArrows) {
        // ... (sisa fungsi initializeArrowCanvas Anda dari sini ke bawah tidak perlu diubah sama sekali)
        const ctx = canvas.getContext('2d');
        const imageOptions = document.getElementById('image-options');
        const textOptions = document.getElementById('text-options');

        if (!imageOptions || !textOptions) return;

        let isDrawing = false;
        let startImageElement = null;
        let startImageRect = null;
        let currentTargetTextElement = null;

        const getElementStartPoint = (el) => {
            const rect = el.getBoundingClientRect();
            const canvasRect = canvas.getBoundingClientRect();
            return {
                x: rect.left + rect.width - canvasRect.left,
                y: rect.top + rect.height / 2 - canvasRect.top,
            };
        };

        const getElementEndPoint = (el) => {
            const rect = el.getBoundingClientRect();
            const canvasRect = canvas.getBoundingClientRect();
            return {
                x: rect.left - canvasRect.left,
                y: rect.top + rect.height / 2 - canvasRect.top,
            };
        };

        const drawArrowhead = (ctx, fromX, fromY, toX, toY) => {
            const headlen = 12;
            const dx = toX - fromX;
            const dy = toY - fromY;
            const angle = Math.atan2(dy, dx);
            ctx.beginPath();
            ctx.moveTo(toX, toY);
            ctx.lineTo(toX - headlen * Math.cos(angle - Math.PI / 6), toY - headlen * Math.sin(angle - Math.PI / 6));
            ctx.moveTo(toX, toY);
            ctx.lineTo(toX - headlen * Math.cos(angle + Math.PI / 6), toY - headlen * Math.sin(angle + Math.PI / 6));
            ctx.stroke();
        };

        const redrawAllCorrectArrows = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            drawnArrows.forEach((arrowData, key) => {
                const startEl = arrowData.startEl;
                const endEl = arrowData.endEl;
                if (!startEl || !endEl) return;
                const startPoint = getElementStartPoint(startEl);
                const endPoint = getElementEndPoint(endEl);
                ctx.beginPath();
                ctx.moveTo(startPoint.x, startPoint.y);
                ctx.lineTo(endPoint.x, endPoint.y);
                ctx.strokeStyle = arrowData.color;
                ctx.lineWidth = 5;
                ctx.lineCap = 'round';
                ctx.stroke();
                drawArrowhead(ctx, startPoint.x, startPoint.y, endPoint.x, endPoint.y);
            });
        };

        window.drawCorrectArrow = (canvas, startKey, endKey, initialLoad = false) => {
            const startEl = document.querySelector(`.matching-image[data-key="${startKey}"]`);
            const endEl = document.querySelector(`.matching-text[data-key="${endKey}"]`);
            if (startEl && endEl) {
                startEl.classList.add('correct-paired');
                endEl.classList.add('correct-paired');
                endEl.classList.remove('bg-yellow-100', 'border-yellow-700', 'hover:border-blue-300');
                endEl.classList.add('bg-green-200', 'border-green-500');
                drawnArrows.set(`${startKey}-${endKey}`, {
                    startEl: startEl,
                    endEl: endEl,
                    color: '#10B981'
                });
                redrawAllCorrectArrows();
            }
        };

        imageOptions.addEventListener('mousedown', (e) => {
            if (e.target.classList.contains('matching-image') && !e.target.classList.contains('correct-paired')) {
                isDrawing = true;
                startImageElement = e.target;
                startImageRect = startImageElement.getBoundingClientRect();
                startImageElement.classList.add('border-blue-500', 'border-opacity-100', 'active-drag-source');
                startImageElement.classList.remove('hover:border-blue-300');
                canvas.style.pointerEvents = 'auto';
            }
        });

        canvas.addEventListener('mousemove', (e) => {
            if (!isDrawing) return;
            const canvasRect = canvas.getBoundingClientRect();
            const currentMouseX = e.clientX;
            const currentMouseY = e.clientY;
            const startPoint = getElementStartPoint(startImageElement);
            const targetTextElements = textOptions.querySelectorAll('.matching-text');
            let foundTarget = false;
            let endX = currentMouseX - canvasRect.left;
            let endY = currentMouseY - canvasRect.top;
            targetTextElements.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (currentMouseX >= rect.left && currentMouseX <= rect.right &&
                    currentMouseY >= rect.top && currentMouseY <= rect.bottom) {
                    const endPoint = getElementEndPoint(el);
                    endX = endPoint.x;
                    endY = endPoint.y;
                    if (currentTargetTextElement && currentTargetTextElement !== el) {
                        currentTargetTextElement.classList.remove('border-blue-500', 'active-drag-target');
                        currentTargetTextElement.classList.add('hover:border-blue-300');
                    }
                    if(!el.classList.contains('correct-paired')) {
                        el.classList.add('border-blue-500', 'border-opacity-100', 'active-drag-target');
                        el.classList.remove('hover:border-blue-300');
                        currentTargetTextElement = el;
                    }
                    foundTarget = true;
                }
            });
            if (!foundTarget && currentTargetTextElement) {
                currentTargetTextElement.classList.remove('border-blue-500', 'active-drag-target');
                currentTargetTextElement.classList.add('hover:border-blue-300');
                currentTargetTextElement = null;
            }
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
            redrawAllCorrectArrows();
            ctx.beginPath();
            ctx.moveTo(startPoint.x, startPoint.y);
            ctx.lineTo(endX, endY);
            ctx.strokeStyle = '#3B82F6';
            ctx.lineWidth = 5;
            ctx.lineCap = 'round';
            ctx.stroke();
            drawArrowhead(ctx, startPoint.x, startPoint.y, endX, endY);
        });

        canvas.addEventListener('mouseup', (e) => {
            if (!isDrawing) return;
            isDrawing = false;
            canvas.style.pointerEvents = 'none';
            if (startImageElement) {
                startImageElement.classList.remove('border-blue-500', 'border-opacity-100', 'active-drag-source');
                startImageElement.classList.add('hover:border-blue-300');
            }
            if (currentTargetTextElement) {
                currentTargetTextElement.classList.remove('border-blue-500', 'border-opacity-100', 'active-drag-target');
                currentTargetTextElement.classList.add('hover:border-blue-300');
            }
            redrawAllCorrectArrows();
            const endElement = document.elementFromPoint(e.clientX, e.clientY);
            if (endElement && endElement.classList.contains('matching-text')) {
                const startKey = startImageElement.dataset.key;
                const endKey = endElement.dataset.key;
                if (startKey && endKey) {
                    Livewire.dispatch('selectItem', { type: 'image', key: startKey });
                    Livewire.dispatch('selectItem', { type: 'text', key: endKey });
                }
            }
            startImageElement = null;
            currentTargetTextElement = null;
        });

        canvas.addEventListener('mouseleave', () => {
            if (isDrawing) {
                isDrawing = false;
                canvas.style.pointerEvents = 'none';
                if (startImageElement) {
                    startImageElement.classList.remove('border-blue-500', 'border-opacity-100', 'active-drag-source');
                    startImageElement.classList.add('hover:border-blue-300');
                }
                if (currentTargetTextElement) {
                    currentTargetTextElement.classList.remove('border-blue-500', 'border-opacity-100', 'active-drag-target');
                    currentTargetTextElement.classList.add('hover:border-blue-300');
                }
                redrawAllCorrectArrows();
                startImageElement = null;
                currentTargetTextElement = null;
            }
        });

        redrawAllCorrectArrows();
    }
});
