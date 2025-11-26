/**
 * Phát âm thanh theo variant
 */
export function playSound(variant, userSettings) {
    const notificationSound = userSettings?.notification_sound;

    if (notificationSound === false) return;

    const volume = Math.min(Math.max((userSettings?.notification_volume ?? 50) / 100, 0), 1);

    const soundMap = {
        success: 'success.mp3',
        error: 'error.mp3',
        danger: 'error.mp3',
        warning: 'warning.mp3',
        info: 'info.mp3',
    };

    const soundFile = soundMap[variant] || soundMap.info;
    const soundPath = `/storage/sounds/${soundFile}`;

    try {
        const audio = new Audio(soundPath);
        audio.preload = 'auto';
        audio.volume = volume;
        audio.currentTime = 0;

        audio.play().catch((error) => {
            console.warn('Không thể phát âm thanh:', error);
        });

    } catch (error) {
        console.warn('Lỗi Audio:', error);
    }
}

/**
 * Handler cho toast event
 */
export function handleToast(event, userSettings) {
    if (userSettings?.notification_sound === false) return;

    const variant = event.detail.dataset?.variant || event.detail.variant || '';
    playSound(variant, userSettings);
}

/**
 * Tạo Alpine data object
 */
export function createToastHandler(userSettings) {
    return {
        userSettings,

        playSound(variant) {
            playSound(variant, this.userSettings);
        },

        handleToast(event) {
            handleToast(event, this.userSettings);
        },
    };
}

/**
 * Phát thử âm thanh từ Blade
 */
export function playTestSound(url, userSettings) {
    const notificationSound = userSettings?.notification_sound;

    if (notificationSound === false) return;

    const volume = Math.min(Math.max((userSettings?.notification_volume ?? 50) / 100, 0), 1);
    console.log(volume);
    const audio = new Audio(url);
    audio.preload = 'auto';
    audio.volume = volume;
    audio.currentTime = 0;
    audio.play();
}

export default {
    playSound,
    handleToast,
    createToastHandler,
    playTestSound,
};
