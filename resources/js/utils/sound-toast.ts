import { toast } from 'sonner';

/**
 * Hiển thị toast + âm thanh đồng bộ toàn hệ thống
 * @param type  'success' | 'error' | 'info'
 * @param message  nội dung thông báo
 */
export function soundToast(type: 'success' | 'error' | 'info', message: string) {
  // 🔊 Đường dẫn âm thanh trong thư mục public/sounds
  const audio = new Audio(`/storage/sounds/${type}.mp3`);
  audio.volume = 0.6;

  // Phát âm thanh (bỏ qua lỗi nếu user chưa tương tác)
  audio.play().catch(() => {
    console.warn('Không thể phát âm thanh — cần tương tác người dùng trước.');
  });

  // Hiển thị toast bằng sonner
  if (type === 'success') {
    toast.success(message, {
      duration: 3000,
      className: 'font-medium',
    });
  } else if (type === 'error') {
    toast.error(message, {
      duration: 4000,
      className: 'font-medium',
    });
  } else {
    toast.info(message, {
      duration: 3000,
      className: 'font-medium',
    });
  }
}
