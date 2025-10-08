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

    // -- Cek duplikat dan hapus jika ada --
    const existingNotif = container.querySelector(`[data-message="${message}"]`);
    if (existingNotif) {
        existingNotif.remove();
    }

    // -- Buat notifikasi baru --
    const notification = document.createElement('div');
    notification.setAttribute('data-message', message);

    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    notification.className = `p-4 mb-2 text-white ${bgColor} rounded-lg shadow-lg transition-all duration-300 ease-in-out transform translate-x-full opacity-0 relative flex items-center justify-between min-w-[250px] max-w-sm`;

    notification.innerHTML = `
        <span class="mr-4">${message}</span>
        <button class="flex-shrink-0 text-white hover:text-gray-100 focus:outline-none" aria-label="Close">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    `;

    // -- Tambahkan di paling atas container --
    if (container.firstChild) {
        container.insertBefore(notification, container.firstChild);
    } else {
        container.appendChild(notification);
    }

    // -- Animasi masuk langsung --
    requestAnimationFrame(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    });

    // -- Auto dismiss --
    const dismissTimeout = setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full', 'opacity-0');
            notification.addEventListener('transitionend', () => notification.remove(), { once: true });
        }
    }, 1000);

    // -- Tombol close --
    notification.querySelector('button').addEventListener('click', () => {
        clearTimeout(dismissTimeout);
        notification.classList.add('translate-x-full', 'opacity-0');
        notification.addEventListener('transitionend', () => notification.remove(), { once: true });
    });

    // -- Batasi maksimal 3 notifikasi --
    const allNotifications = container.querySelectorAll('div');
    if (allNotifications.length > 3) {
        const oldestNotification = allNotifications[allNotifications.length - 1];
        oldestNotification.classList.add('translate-x-full', 'opacity-0');
        oldestNotification.addEventListener('transitionend', () => oldestNotification.remove(), { once: true });
    }
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

    let drawnArrows = new Map();

    Livewire.on('clear-arrows', () => {
        drawnArrows.clear();
        if (window.redrawAllCorrectArrows) {
            window.redrawAllCorrectArrows();
        }
    });

    const observer = new MutationObserver((mutations) => {
        const canvas = document.getElementById('arrow-canvas');
        if (canvas && !canvas.getAttribute('data-initialized')) {
            console.log('Canvas ditemukan, menginisialisasi...');
            initializeArrowCanvas(canvas, drawnArrows);
            canvas.setAttribute('data-initialized', 'true');

            // Coba gambar ulang setelah inisialisasi
            setTimeout(() => {
                if (window.redrawAllCorrectArrows) {
                    window.redrawAllCorrectArrows();
                }
            }, 300);
        } else if (!canvas) {
            const oldCanvas = document.querySelector('[data-initialized="true"]');
            if (oldCanvas) {
                oldCanvas.removeAttribute('data-initialized');
                console.log('Canvas dihapus, reset initialized state');
            }
        }
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    Livewire.on('draw-correct-arrow', ([data]) => {
        const canvas = document.getElementById('arrow-canvas');
        if (canvas && window.drawCorrectArrow) {
            window.drawCorrectArrow(canvas, data.startKey, data.endKey, data.initialLoad);
        }
    });

    // Tambahkan event listener untuk redraw semua panah
    Livewire.on('redraw-all-arrows', () => {
        const canvas = document.getElementById('arrow-canvas');
        if (canvas && window.redrawAllCorrectArrows) {
            console.log('Redraw semua panah dipanggil');
            window.redrawAllCorrectArrows();
        }
    });

    function initializeArrowCanvas(canvas, drawnArrows) {
        const ctx = canvas.getContext('2d');
        const imageOptions = document.getElementById('image-options');
        const textOptions = document.getElementById('text-options');

        if (!imageOptions || !textOptions) return;

        let isDrawing = false;
        let startImageElement = null;
        let currentTargetTextElement = null;

        // --- FUNGSI BANTU ---
        const getElementStartPoint = (el) => {
            const rect = el.getBoundingClientRect();
            const canvasRect = canvas.getBoundingClientRect();
            return {
                x: rect.right - canvasRect.left + 8,
                y: rect.top + rect.height / 2 - canvasRect.top
            };
        };

        const getElementEndPoint = (el) => {
            const rect = el.getBoundingClientRect();
            const canvasRect = canvas.getBoundingClientRect();
            return {
                x: rect.left - canvasRect.left - 8,
                y: rect.top + rect.height / 2 - canvasRect.top
            };
        };

        const drawArrowhead = (ctx, fromX, fromY, toX, toY, color = '#60A5FA') => {
            const headlen = 12;
            const dx = toX - fromX;
            const dy = toY - fromY;
            const angle = Math.atan2(dy, dx);

            ctx.save();
            ctx.strokeStyle = color;
            ctx.fillStyle = color;

            ctx.beginPath();
            ctx.moveTo(toX, toY);
            ctx.lineTo(toX - headlen * Math.cos(angle - Math.PI / 6), toY - headlen * Math.sin(angle - Math.PI / 6));
            ctx.lineTo(toX - headlen * Math.cos(angle + Math.PI / 6), toY - headlen * Math.sin(angle + Math.PI / 6));
            ctx.closePath();
            ctx.fill();

            ctx.restore();
        };

        const redrawAllCorrectArrows = () => {
            if (!canvas) {
                console.log('Canvas tidak ditemukan');
                return;
            }

            try {
                // Clear canvas terlebih dahulu
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Pastikan canvas size sesuai
                if (canvas.width !== canvas.offsetWidth || canvas.height !== canvas.offsetHeight) {
                    canvas.width = canvas.offsetWidth;
                    canvas.height = canvas.offsetHeight;
                }

                console.log('Menggambar ulang', drawnArrows.size, 'panah');

                // Gambar ulang semua panah yang benar
                drawnArrows.forEach((arrowData, key) => {
                    const startEl = arrowData.startEl;
                    const endEl = arrowData.endEl;

                    // Pastikan elemen masih ada di DOM dan terlihat
                    if (!startEl || !endEl || !document.contains(startEl) || !document.contains(endEl)) {
                        console.log('Menghapus panah untuk elemen yang tidak ditemukan:', key);
                        drawnArrows.delete(key);
                        return;
                    }

                    // Pastikan elemen sudah ter-render sepenuhnya
                    const startRect = startEl.getBoundingClientRect();
                    const endRect = endEl.getBoundingClientRect();

                    if (startRect.width === 0 || startRect.height === 0 || endRect.width === 0 || endRect.height === 0) {
                        console.log('Elemen belum siap untuk digambar:', key);
                        return;
                    }

                    const startPoint = getElementStartPoint(startEl);
                    const endPoint = getElementEndPoint(endEl);

                    // Gambar garis panah
                    ctx.beginPath();
                    ctx.moveTo(startPoint.x, startPoint.y);
                    ctx.lineTo(endPoint.x, endPoint.y);
                    ctx.strokeStyle = arrowData.color;
                    ctx.lineWidth = 4;
                    ctx.lineCap = 'round';
                    ctx.stroke();

                    // Gambar kepala panah
                    drawArrowhead(ctx, startPoint.x, startPoint.y, endPoint.x, endPoint.y, arrowData.color);
                });
            } catch (error) {
                console.error('Error dalam redrawAllCorrectArrows:', error);
            }
        };

        window.drawCorrectArrow = (canvas, startKey, endKey, initialLoad = false) => {
            const startEl = document.querySelector(`.matching-image[data-key="${startKey}"]`);
            const endEl = document.querySelector(`.matching-text[data-key="${endKey}"]`);

            if (startEl && endEl) {
                // Logika Anda untuk menambahkan style, ini sudah benar
                startEl.classList.add('correct-paired');
                endEl.classList.add('correct-paired');
                endEl.classList.remove('bg-yellow-100', 'border-yellow-700', 'hover:border-blue-400');
                endEl.classList.add('bg-green-200', 'border-green-500');

                // Simpan ke memori visual, ini sudah benar
                drawnArrows.set(`${startKey}-${endKey}`, {
                    startEl: startEl,
                    endEl: endEl,
                    color: '#10B981'
                });

                // Pastikan canvas di-redraw untuk menampilkan panah
                setTimeout(() => {
                    redrawAllCorrectArrows();
                }, 0);
            } else if (retryCount < MAX_RETRIES) {
                console.log(`Elemen belum ditemukan, mencoba lagi (${retryCount + 1}/${MAX_RETRIES})...`);
                setTimeout(() => drawArrow(retryCount + 1), RETRY_DELAY);
            } else {
                console.error('Gagal menemukan elemen untuk panah:', startKey, endKey);
            }
        };

        // --- FUNGSI UTAMA DRAG AND DROP ---
        const handleDragStart = (e) => {
            // Prevent default untuk mencegah behavior browser yang tidak diinginkan
            if (e.type === 'touchstart') {
                e.preventDefault();
            }

            let startElement = e.target;

            // Untuk touch, dapatkan elemen dari titik sentuh
            if (e.type === 'touchstart') {
                const touch = e.touches[0];
                startElement = document.elementFromPoint(touch.clientX, touch.clientY);
            }

            if (startElement && startElement.classList.contains('matching-image') &&
                !startElement.classList.contains('correct-paired')) {
                isDrawing = true;
                startImageElement = startElement;

                // **PERBAIKAN: Langsung aktifkan state drag untuk touch**
                startImageElement.classList.add('border-blue-400', 'border-opacity-100', 'active-drag-source');
                startImageElement.classList.remove('hover:border-blue-400');
                canvas.style.pointerEvents = 'auto';

                // **PERBAIKAN: Untuk touch, langsung panggil handleDragMove dengan posisi awal**
                if (e.type === 'touchstart') {
                    const touch = e.touches[0];
                    handleDragMove({
                        type: 'touchmove',
                        touches: [touch],
                        preventDefault: () => { }
                    });
                } else {
                    handleDragMove({
                        type: 'mousemove',
                        clientX: e.clientX,
                        clientY: e.clientY,
                        preventDefault: () => { }
                    });
                }
            }
        };

        const handleDragMove = (e) => {
            if (!isDrawing) return;

            // **PERBAIKAN: Selalu prevent default untuk touchmove**
            if (e.type === 'touchmove') {
                e.preventDefault();
            }

            let currentX, currentY;
            if (e.type === 'touchmove') {
                currentX = e.touches[0].clientX;
                currentY = e.touches[0].clientY;
            } else {
                currentX = e.clientX;
                currentY = e.clientY;
            }

            const canvasRect = canvas.getBoundingClientRect();
            const startPoint = getElementStartPoint(startImageElement);

            const targetTextElements = textOptions.querySelectorAll('.matching-text');
            let foundTarget = false;
            let endX = currentX - canvasRect.left;
            let endY = currentY - canvasRect.top;

            // **PERBAIKAN: Optimasi deteksi target untuk performa touch yang lebih baik**
            targetTextElements.forEach(el => {
                const rect = el.getBoundingClientRect();

                // **PERBAIKAN: Gunakan area yang sedikit lebih besar untuk touch**
                const tolerance = 10; // 10px tolerance untuk touch
                if (currentX >= rect.left - tolerance && currentX <= rect.right + tolerance &&
                    currentY >= rect.top - tolerance && currentY <= rect.bottom + tolerance) {

                    const endPoint = getElementEndPoint(el);
                    endX = endPoint.x;
                    endY = endPoint.y;

                    if (currentTargetTextElement && currentTargetTextElement !== el) {
                        currentTargetTextElement.classList.remove('border-blue-400', 'active-drag-target');
                        currentTargetTextElement.classList.add('hover:border-blue-400');
                    }
                    if (!el.classList.contains('correct-paired')) {
                        el.classList.add('border-blue-400', 'border-opacity-100', 'active-drag-target');
                        el.classList.remove('hover:border-blue-400');
                        currentTargetTextElement = el;
                    }
                    foundTarget = true;
                }
            });

            if (!foundTarget && currentTargetTextElement) {
                currentTargetTextElement.classList.remove('border-blue-400', 'active-drag-target');
                currentTargetTextElement.classList.add('hover:border-blue-400');
                currentTargetTextElement = null;

                // **PERBAIKAN: Jika tidak ada target, gunakan posisi touch langsung**
                endX = currentX - canvasRect.left;
                endY = currentY - canvasRect.top;
            }

            // **PERBAIKAN: Optimasi performa - hanya resize canvas jika diperlukan**
            if (canvas.width !== canvas.offsetWidth || canvas.height !== canvas.offsetHeight) {
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
            }

            redrawAllCorrectArrows();

            // Gambar panah sementara
            ctx.beginPath();
            ctx.moveTo(startPoint.x, startPoint.y);
            ctx.lineTo(endX, endY);
            ctx.strokeStyle = '#60A5FA';
            ctx.lineWidth = 4;
            ctx.lineCap = 'round';
            ctx.stroke();
            drawArrowhead(ctx, startPoint.x, startPoint.y, endX, endY, '#60A5FA');
        };

        const handleDragEnd = (e) => {
            if (!isDrawing) return;

            let endX, endY;
            if (e.type === 'touchend') {
                const touch = e.changedTouches[0];
                endX = touch.clientX;
                endY = touch.clientY;
            } else {
                endX = e.clientX;
                endY = e.clientY;
            }

            // **PERBAIKAN: Gunakan currentTargetTextElement yang sudah disimpan**
            let endElement = currentTargetTextElement;

            // **PERBAIKAN: Fallback untuk touch - cari elemen dengan tolerance yang lebih besar**
            if (!endElement) {
                const elements = document.elementsFromPoint(endX, endY);
                endElement = elements.find(el => el.classList.contains('matching-text'));
            }

            if (startImageElement && endElement && endElement.classList.contains('matching-text')) {
                const startKey = startImageElement.dataset.key;
                const endKey = endElement.dataset.key;

                if (startKey && endKey) {
                    Livewire.dispatch('itemSelected', {
                        type: 'image',
                        key: startKey
                    });

                    Livewire.dispatch('itemSelected', {
                        type: 'text',
                        key: endKey
                    });
                }
            }

            cleanupDragState();
        };

        const cleanupDragState = () => {
            if (!isDrawing && !startImageElement && !currentTargetTextElement) return;
            isDrawing = false;
            canvas.style.pointerEvents = 'none';

            if (startImageElement) {
                startImageElement.classList.remove('border-blue-400', 'border-opacity-100', 'active-drag-source');
                startImageElement.classList.add('hover:border-blue-400');
            }

            if (currentTargetTextElement) {
                currentTargetTextElement.classList.remove('border-blue-400', 'border-opacity-100', 'active-drag-target');
                currentTargetTextElement.classList.add('hover:border-blue-400');
            }

            startImageElement = null;
            currentTargetTextElement = null;

            redrawAllCorrectArrows();
        };

        // **PERBAIKAN: Fungsi global untuk touch events**
        const handleGlobalTouchMove = (e) => {
            if (isDrawing) {
                handleDragMove(e);
            }
        };

        const handleGlobalTouchEnd = (e) => {
            if (isDrawing) {
                handleDragEnd(e);
            }
        };

        // --- SETUP EVENT LISTENER ---
        // Hapus listener lama
        imageOptions.removeEventListener('mousedown', handleDragStart);
        document.removeEventListener('mousemove', handleDragMove);
        document.removeEventListener('mouseup', handleDragEnd);
        imageOptions.removeEventListener('touchstart', handleDragStart);
        document.removeEventListener('touchmove', handleDragMove);
        document.removeEventListener('touchend', handleDragEnd);

        // 1. Daftarkan event 'start' (mousedown/touchstart) pada container gambar.
        //    Ini adalah titik awal interaksi.
        imageOptions.addEventListener('mousedown', handleDragStart);
        imageOptions.addEventListener('touchstart', handleDragStart, { passive: false });

        // 2. Daftarkan event 'move' dan 'end' pada 'document'.
        //    Ini memastikan aksi tetap terlacak bahkan jika kursor/jari keluar dari
        //    area canvas atau imageOptions. Inilah yang membuat perilakunya andal.
        document.addEventListener('mousemove', handleDragMove);
        document.addEventListener('mouseup', handleDragEnd);
        document.addEventListener('touchmove', handleDragMove, { passive: false });
        document.addEventListener('touchend', handleDragEnd);
    }
});
