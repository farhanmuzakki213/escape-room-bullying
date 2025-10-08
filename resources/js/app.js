import './bootstrap';

/**
 * ----------------------------------------------------------------
 * PENGELOLAAN AUDIO GLOBAL
 * ----------------------------------------------------------------
 * Mengelola semua elemen audio dan status mute/unmute.
 */
const bgMusic = document.getElementById('background-music');
const correctSound = document.getElementById('correct-answer-sound');
const incorrectSound = document.getElementById('incorrect-answer-sound');
const clickSound = document.getElementById('click-sound');

let isMuted = localStorage.getItem('isMuted') === 'true';
let hasInteracted = false;

/**
 * Mengatur status mute/unmute untuk semua suara dan menyimpannya.
 * @param {boolean} muted Status mute yang baru.
 */
function setMuted(muted) {
    isMuted = muted;
    localStorage.setItem('isMuted', String(muted));
    if (bgMusic) bgMusic.muted = muted;
}

/**
 * Memainkan elemen suara jika tidak dalam mode mute.
 * @param {HTMLAudioElement} soundElement Elemen audio yang akan dimainkan.
 */
function playSound(soundElement) {
    if (!isMuted && soundElement) {
        soundElement.currentTime = 0;
        soundElement.play().catch(e => console.error("Gagal memutar suara:", e));
    }
}

// Inisialisasi status mute saat halaman dimuat.
setMuted(isMuted);

// Mainkan musik latar pada interaksi pertama pengguna untuk mematuhi kebijakan autoplay browser.
document.body.addEventListener('click', () => {
    if (!hasInteracted && bgMusic && bgMusic.paused) {
        hasInteracted = true;
        bgMusic.play().catch(e => console.error("Autoplay musik dicegah oleh browser."));
    }
}, { once: true });

// Menggunakan event delegation untuk menangani semua klik pada tombol secara efisien.
document.addEventListener('click', function (event) {
    // Cari elemen interaktif terdekat (button atau link) dari target klik.
    const button = event.target.closest('button, a');
    if (!button) {
        return;
    }

    const isVolumeButton = button.querySelector('img[alt="Volume"]');
    if (isVolumeButton) {
        setMuted(!isMuted);
    } else {
        playSound(clickSound);
    }
});

// Listener global untuk efek suara dari event Livewire (tidak berubah)
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

    // Hapus notifikasi duplikat yang mungkin masih ada.
    const existingNotif = container.querySelector(`[data-message="${message}"]`);
    if (existingNotif) {
        existingNotif.remove();
    }

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

    // Tambahkan notifikasi baru di bagian atas container.
    if (container.firstChild) {
        container.insertBefore(notification, container.firstChild);
    } else {
        container.appendChild(notification);
    }

    requestAnimationFrame(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    });

    // Atur notifikasi untuk hilang secara otomatis.
    const dismissTimeout = setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full', 'opacity-0');
            notification.addEventListener('transitionend', () => notification.remove(), { once: true });
        }
    }, 1000);

    // Tambahkan fungsionalitas pada tombol close.
    notification.querySelector('button').addEventListener('click', () => {
        clearTimeout(dismissTimeout);
        notification.classList.add('translate-x-full', 'opacity-0');
        notification.addEventListener('transitionend', () => notification.remove(), { once: true });
    });

    // Batasi jumlah notifikasi yang ditampilkan menjadi maksimal 3.
    const allNotifications = container.querySelectorAll('div');
    if (allNotifications.length > 3) {
        const oldestNotification = allNotifications[allNotifications.length - 1];
        oldestNotification.classList.add('translate-x-full', 'opacity-0');
        oldestNotification.addEventListener('transitionend', () => oldestNotification.remove(), { once: true });
    }
}

// Listener untuk event notifikasi dari Livewire.
Livewire.on('show-notification', ({ message, type }) => {
    if (message) {
        showNotification(message, type);
    }
});

document.addEventListener('livewire:initialized', () => {

    /**
     * ----------------------------------------------------------------
     * MANAJEMEN PROGRES GAME (LOCAL STORAGE)
     * ----------------------------------------------------------------
     */
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

    /**
     * ----------------------------------------------------------------
     * LOGIKA GAME MENJODOHKAN (LEVEL 2) - PENGGAMBARAN PANAH
     * ----------------------------------------------------------------
     */

    let drawnArrows = new Map();

    Livewire.on('clear-arrows', () => {
        drawnArrows.clear();
        if (window.redrawAllCorrectArrows) {
            window.redrawAllCorrectArrows();
        }
    });

    // Observer untuk mendeteksi kapan canvas panah muncul atau hilang dari DOM.
    const observer = new MutationObserver((mutations) => {
        const canvas = document.getElementById('arrow-canvas');
        if (canvas && !canvas.getAttribute('data-initialized')) {
            initializeArrowCanvas(canvas, drawnArrows);
            canvas.setAttribute('data-initialized', 'true');

            // Gambar ulang panah yang sudah ada setelah inisialisasi.
            setTimeout(() => {
                if (window.redrawAllCorrectArrows) {
                    window.redrawAllCorrectArrows();
                }
            }, 300);
        } else if (!canvas) {
            const oldCanvas = document.querySelector('[data-initialized="true"]');
            if (oldCanvas) {
                oldCanvas.removeAttribute('data-initialized');
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

    Livewire.on('redraw-all-arrows', () => {
        const canvas = document.getElementById('arrow-canvas');
        if (canvas && window.redrawAllCorrectArrows) {
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
                return;
            }

            try {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                if (canvas.width !== canvas.offsetWidth || canvas.height !== canvas.offsetHeight) {
                    canvas.width = canvas.offsetWidth;
                    canvas.height = canvas.offsetHeight;
                }

                // Gambar ulang semua panah yang sudah benar dan tersimpan.
                drawnArrows.forEach((arrowData, key) => {
                    const startEl = arrowData.startEl;
                    const endEl = arrowData.endEl;

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
                startEl.classList.add('correct-paired');
                endEl.classList.add('correct-paired');
                endEl.classList.remove('bg-yellow-100', 'border-yellow-700', 'hover:border-blue-400');
                endEl.classList.add('bg-green-200', 'border-green-500');

                drawnArrows.set(`${startKey}-${endKey}`, {
                    startEl: startEl,
                    endEl: endEl,
                    color: '#10B981'
                });

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

        const handleDragStart = (e) => {
            if (e.type === 'touchstart') {
                e.preventDefault();
            }

            let startElement = e.target;

            if (e.type === 'touchstart') {
                const touch = e.touches[0];
                startElement = document.elementFromPoint(touch.clientX, touch.clientY);
            }

            if (startElement && startElement.classList.contains('matching-image') && !startElement.classList.contains('correct-paired')) {
                isDrawing = true;
                startImageElement = startElement;

                startImageElement.classList.add('border-blue-400', 'border-opacity-100', 'active-drag-source');
                startImageElement.classList.remove('hover:border-blue-400');
                canvas.style.pointerEvents = 'auto';
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

            targetTextElements.forEach(el => {
                const rect = el.getBoundingClientRect();
                const tolerance = 10;
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

                endX = currentX - canvasRect.left;
                endY = currentY - canvasRect.top;
            }

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

            let endElement = currentTargetTextElement;

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

        // Hapus listener lama untuk mencegah duplikasi.
        imageOptions.removeEventListener('mousedown', handleDragStart);
        document.removeEventListener('mousemove', handleDragMove);
        document.removeEventListener('mouseup', handleDragEnd);
        imageOptions.removeEventListener('touchstart', handleDragStart);
        document.removeEventListener('touchmove', handleDragMove);
        document.removeEventListener('touchend', handleDragEnd);

        // Daftarkan event 'start' pada container gambar.
        imageOptions.addEventListener('mousedown', handleDragStart);
        imageOptions.addEventListener('touchstart', handleDragStart, { passive: false });

        // Daftarkan event 'move' dan 'end' pada document untuk melacak pergerakan di mana saja.
        document.addEventListener('mousemove', handleDragMove);
        document.addEventListener('mouseup', handleDragEnd);
        document.addEventListener('touchmove', handleDragMove, { passive: false });
        document.addEventListener('touchend', handleDragEnd);
    }
});
