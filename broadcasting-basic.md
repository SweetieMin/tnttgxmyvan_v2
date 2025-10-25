# 📡 Hướng Dẫn Broadcasting Cơ Bản - Laravel

## 🎯 Tổng Quan

Hướng dẫn này sẽ giúp bạn thiết lập và sử dụng Laravel Broadcasting từ A-Z, bao gồm:
- Tạo Event để broadcast
- Cấu hình JavaScript để lắng nghe
- Thiết lập channels trong `routes/channels.php`

---

## 📋 Bước 1: Cấu Hình Broadcasting

### 1.1 Cài đặt dependencies

```bash
# Cài đặt Laravel Echo và Pusher
npm install laravel-echo pusher-js
```

### 1.2 Cấu hình .env

```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
```

### 1.3 Cấu hình JavaScript (resources/js/echo.js)

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    enabledTransports: ["ws", "wss"],
});
```

### 1.4 Import Echo trong app.js

```javascript
// resources/js/app.js
import './echo';

// Ví dụ sử dụng
window.Echo.channel('notifications')
  .listen('.new-notification', (e) => {
    alert('📢 Thông báo mới: ' + e.message)
    console.log('📢 Event nhận được:', e)
  })
```

---

## 🎭 Bước 2: Tạo Event Broadcasting

### 2.1 Tạo Event

```bash
php artisan make:event NotificationSent
```

### 2.2 Cấu trúc Event cơ bản

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $userId = null)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // Broadcast đến channel riêng của user
        return [
            new PrivateChannel('user.' . $this->userId)
        ];
    }

    /**
     * Tên event khi broadcast
     */
    public function broadcastAs(): string
    {
        return 'new-notification';
    }

    /**
     * Dữ liệu sẽ được broadcast
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'timestamp' => now()->toISOString(),
            'user_id' => $this->userId
        ];
    }
}
```

### 2.3 Sử dụng Event

```php
// Trong Controller hoặc bất kỳ đâu
use App\Events\NotificationSent;

// Broadcast event
broadcast(new NotificationSent('Xin chào!', $userId));
```

---

## 🔐 Bước 3: Cấu Hình Channels (routes/channels.php)

### 3.1 Channel cho User riêng lẻ

```php
/**
 * 🔹 Channel riêng cho từng user
 * Dành cho thông báo cá nhân, tin nhắn riêng, v.v.
 * Ví dụ: private-user.5
 */
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
```

### 3.2 Channel cho Role

```php
/**
 * 🔹 Channel cho role (nhiều-nhiều)
 * Dành cho broadcast theo quyền hạn, ví dụ "BOD", "Teacher", "Thieu Nhi"
 * Ví dụ: private-role.BOD
 */
Broadcast::channel('role.{roleName}', function ($user, $roleName) {
    return $user->roles()->where('name', $roleName)->exists();
});
```

### 3.3 Channel cho Course

```php
/**
 * 🔹 Channel cho course (nhiều-nhiều)
 * Dành cho broadcast đến toàn bộ học viên và giáo viên trong 1 lớp học
 * Ví dụ: private-course.12
 */
Broadcast::channel('course.{courseId}', function ($user, $courseId) {
    return $user->courses()->where('id', $courseId)->exists();
});
```

### 3.4 Channel cho Sector

```php
/**
 * 🔹 Channel cho sector (nhiều-nhiều)
 * Dành cho broadcast đến toàn bộ thành viên thuộc một ngành / khu vực
 * Ví dụ: private-sector.Nghia hoặc private-sector.3
 */
Broadcast::channel('sector.{sectorId}', function ($user, $sectorId) {
    return $user->sectors()->where('id', $sectorId)->exists();
});
```

---

## 🎧 Bước 4: Lắng Nghe Events trong JavaScript

### 4.1 Lắng nghe Channel riêng

```javascript
// Lắng nghe thông báo riêng cho user
window.Echo.private('user.' + userId)
  .listen('.new-notification', (e) => {
    console.log('Thông báo mới:', e.message);
    // Hiển thị notification
    showNotification(e.message);
  });
```

### 4.2 Lắng nghe Channel theo Role

```javascript
// Lắng nghe thông báo cho tất cả BOD
window.Echo.private('role.BOD')
  .listen('.announcement', (e) => {
    console.log('Thông báo cho BOD:', e.content);
  });
```

### 4.3 Lắng nghe Channel theo Course

```javascript
// Lắng nghe thông báo cho lớp học
window.Echo.private('course.' + courseId)
  .listen('.class-update', (e) => {
    console.log('Cập nhật lớp học:', e.message);
  });
```

### 4.4 Lắng nghe Channel theo Sector

```javascript
// Lắng nghe thông báo cho ngành
window.Echo.private('sector.' + sectorId)
  .listen('.sector-news', (e) => {
    console.log('Tin tức ngành:', e.news);
  });
```

---

## 🚀 Bước 5: Ví Dụ Hoàn Chỉnh

### 5.1 Tạo Event cho thông báo hệ thống

```php
// app/Events/SystemNotification.php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SystemNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
    public $message;
    public $type;
    public $targetRole;

    public function __construct($title, $message, $type = 'info', $targetRole = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->targetRole = $targetRole;
    }

    public function broadcastOn(): array
    {
        if ($this->targetRole) {
            return [new PrivateChannel('role.' . $this->targetRole)];
        }
        
        return [new Channel('public-notifications')];
    }

    public function broadcastAs(): string
    {
        return 'system-notification';
    }

    public function broadcastWith(): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'timestamp' => now()->format('H:i:s d/m/Y')
        ];
    }
}
```

### 5.2 Sử dụng trong Controller

```php
// app/Http/Controllers/NotificationController.php
<?php

namespace App\Http\Controllers;

use App\Events\SystemNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendToRole(Request $request)
    {
        $role = $request->input('role');
        $title = $request->input('title');
        $message = $request->input('message');
        
        // Broadcast đến tất cả user có role này
        broadcast(new SystemNotification($title, $message, 'info', $role));
        
        return response()->json(['success' => true]);
    }
}
```

### 5.3 JavaScript để lắng nghe

```javascript
// Lắng nghe thông báo hệ thống cho role BOD
window.Echo.private('role.BOD')
  .listen('.system-notification', (e) => {
    // Hiển thị notification
    showSystemNotification({
      title: e.title,
      message: e.message,
      type: e.type,
      timestamp: e.timestamp
    });
  });

// Hàm hiển thị notification
function showSystemNotification(data) {
  // Tạo element notification
  const notification = document.createElement('div');
  notification.className = `notification notification-${data.type}`;
  notification.innerHTML = `
    <h4>${data.title}</h4>
    <p>${data.message}</p>
    <small>${data.timestamp}</small>
  `;
  
  // Thêm vào DOM
  document.body.appendChild(notification);
  
  // Tự động ẩn sau 5 giây
  setTimeout(() => {
    notification.remove();
  }, 5000);
}
```

---

## 🔧 Bước 6: Chạy Broadcasting

### 6.1 Chạy Queue Worker

```bash
# Chạy queue worker để xử lý broadcast events
php artisan queue:work
```

### 6.2 Chạy WebSocket Server (nếu dùng Laravel Reverb)

```bash
# Chạy Laravel Reverb server
php artisan reverb:start
```

### 6.3 Build assets

```bash
# Build JavaScript assets
npm run build
# hoặc
npm run dev
```

---

## 📝 Ghi Chú Quan Trọng

1. **Private Channels**: Bắt đầu với `private-` prefix
2. **Public Channels**: Không cần prefix
3. **Event Names**: Sử dụng dot notation (`.`) trong JavaScript
4. **Authentication**: Private channels yêu cầu user đã đăng nhập
5. **Queue**: Broadcasting events nên được queue để tránh block request

---

## 🎯 Kết Luận

Với hướng dẫn này, bạn đã có thể:
- ✅ Tạo và cấu hình Event broadcasting
- ✅ Thiết lập channels với authorization
- ✅ Lắng nghe events trong JavaScript
- ✅ Broadcast real-time notifications

Hãy bắt đầu với ví dụ đơn giản và dần dần mở rộng theo nhu cầu của ứng dụng!


