/**
 * Toast Handler - Xử lý toast notifications và phát âm thanh
 */

/**
 * Function để phát âm thanh dựa trên variant
 * @param {string} variant - Variant của toast (success, error, warning, info)
 * @param {object} userSettings - User settings object
 */
export function playSound(variant, userSettings) {
    // Chỉ phát âm nếu notification_sound không phải false
    const notificationSound = userSettings?.notification_sound;
    
    if (notificationSound === false) {
        return;
    }
    
    // Map variant với file âm thanh
    const soundMap = {
        'success': 'success.mp3',
        'error': 'error.mp3',
        'danger': 'error.mp3', // danger cũng dùng error sound
        'warning': 'warning.mp3',
        'info': 'info.mp3',
    };
    
    // Lấy tên file âm thanh, mặc định là info nếu không tìm thấy
    const soundFile = soundMap[variant] || soundMap['info'];
    const soundPath = `/storage/sounds/${soundFile}`;
    
    try {
        const audio = new Audio(soundPath);
        audio.volume = 0.5; // Điều chỉnh âm lượng (0.0 - 1.0)
        audio.play().catch(error => {
            console.warn('Không thể phát âm thanh:', error);
        });
    } catch (error) {
        console.warn('Lỗi khi tạo Audio object:', error);
    }
}

/**
 * Function để xử lý toast event
 * @param {Event} event - Toast show event
 * @param {object} userSettings - User settings object
 */
export function handleToast(event, userSettings) {
    // Lấy dữ liệu từ event.detail
    const detail = event.detail;
    const variant = detail.dataset?.variant || detail.variant || '';

    // Chỉ hiện console.log nếu notification_sound = true hoặc không có (null/undefined)
    // Nếu notification_sound = false thì không hiện
    const notificationSound = userSettings?.notification_sound;


    if (notificationSound === false) {
        return;
    }

    
    // Phát âm thanh dựa trên variant
    playSound(variant, userSettings);
}

/**
 * Factory function để tạo Alpine.js data object cho toast handler
 * @param {object} userSettings - User settings object
 * @returns {object} Alpine.js data object
 */
export function createToastHandler(userSettings) {
    // Import lại để tránh naming conflict
    const { playSound: playSoundFn, handleToast: handleToastFn } = {
        playSound,
        handleToast
    };
    
    return {
        userSettings: userSettings,
        
        playSound(variant) {
            // Gọi function playSound từ module với userSettings
            playSoundFn(variant, this.userSettings);
        },
        
        handleToast(event) {
            // Gọi function handleToast từ module với userSettings
            handleToastFn(event, this.userSettings);
        }
    };
}

// Export default để có thể import dễ dàng
export default {
    playSound,
    handleToast,
    createToastHandler
};

