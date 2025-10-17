import { toast } from 'sonner';

/**
 * Hiển thị toast + âm thanh đồng bộ toàn hệ thống
 * @param type 'success' | 'error' | 'info'
 * @param message nội dung chính của thông báo
 * @param description mô tả bổ sung (tùy chọn)
 */
export function soundToast(
  type: 'success' | 'error' | 'info',
  message: string,
  description?: string
) {
  // 🔊 Đường dẫn âm thanh trong thư mục public/storage/sounds
  const audio = new Audio(`/storage/sounds/${type}.mp3`);
  audio.volume = 0.6;

  // Phát âm thanh (bỏ qua lỗi nếu user chưa tương tác)
  audio.play().catch(() => {
    console.warn('Không thể phát âm thanh — cần tương tác người dùng trước.');
  });

  // ⚡ Hiển thị toast (kèm mô tả nếu có)
  const toastOptions = {
    duration: type === 'error' ? 4000 : 3000,
    className: 'font-medium',
    description: description || undefined,
  };

  switch (type) {
    case 'success':
      toast.success(message, toastOptions);
      break;
    case 'error':
      toast.error(message, toastOptions);
      break;
    default:
      toast.info(message, toastOptions);
      break;
  }
}
