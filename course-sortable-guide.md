# 🎯 Hướng Dẫn Drag-Drop Sortable cho Course

## ✅ Đã Hoàn Thành

### 1. **Cập Nhật View (courses.blade.php)**
- ✅ Thêm Alpine.js x-data với initSortable()
- ✅ Hỗ trợ cả Desktop và Mobile sortable
- ✅ Drag handle với icon bars-3
- ✅ Data attributes cho academic_year_id
- ✅ Wire:key cho Livewire reactivity

### 2. **Cập Nhật Livewire Component (Courses.php)**
- ✅ Thêm method `updateCourseOrdering()`
- ✅ Truyền `selectedYear` vào view
- ✅ Error handling đầy đủ

### 3. **Repository Integration**
- ✅ Sử dụng `CourseRepository` với `updateCourseOrdering()`
- ✅ Group ordering theo `academic_year_id`
- ✅ Transaction safety

---

## 🎨 Tính Năng Drag-Drop

### **Desktop View**
```html
<tbody id="sortable-courses" data-academic-year-id="{{ $selectedYear }}">
    @forelse ($courses as $course)
    <tr wire:key="course-desktop-{{ $course->id }}" data-id="{{ $course->id }}">
        <td class="drag-handle cursor-move">{{ $course->ordering }}</td>
        <td>{{ $course->course }}</td>
        <!-- ... other columns ... -->
    </tr>
    @endforelse
</tbody>
```

### **Mobile View**
```html
<div id="sortable-courses-mobile" data-academic-year-id="{{ $selectedYear }}">
    @forelse ($courses as $course)
    <div class="sortable-row" wire:key="course-mobile-{{ $course->id }}" data-id="{{ $course->id }}">
        <div class="drag-handle cursor-move">
            <flux:icon.bars-3 class="w-5 h-5 text-gray-400" />
        </div>
        <!-- ... course content ... -->
    </div>
    @endforelse
</div>
```

---

## 🚀 JavaScript Sortable

### **Alpine.js Integration**
```javascript
x-data="{
    initSortable() {
        // Desktop sortable
        const desktopEl = document.getElementById('sortable-courses');
        if (desktopEl && !desktopEl.sortableInstance) {
            desktopEl.sortableInstance = new Sortable(desktopEl, {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function() {
                    let orderedIds = [];
                    desktopEl.querySelectorAll('[data-id]').forEach(item => { 
                        orderedIds.push(item.getAttribute('data-id')); 
                    });
                    const academicYearId = desktopEl.getAttribute('data-academic-year-id');
                    if (academicYearId && orderedIds.length > 0) {
                        $wire.updateCourseOrdering(orderedIds, academicYearId);
                    }
                }
            });
        }
        
        // Mobile sortable
        const mobileEl = document.getElementById('sortable-courses-mobile');
        if (mobileEl && !mobileEl.sortableInstance) {
            mobileEl.sortableInstance = new Sortable(mobileEl, {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function() {
                    let orderedIds = [];
                    mobileEl.querySelectorAll('[data-id]').forEach(item => { 
                        orderedIds.push(item.getAttribute('data-id')); 
                    });
                    const academicYearId = mobileEl.getAttribute('data-academic-year-id');
                    if (academicYearId && orderedIds.length > 0) {
                        $wire.updateCourseOrdering(orderedIds, academicYearId);
                    }
                }
            });
        }
    }
}" x-init="initSortable()"
```

---

## 🔧 Livewire Method

### **updateCourseOrdering Method**
```php
public function updateCourseOrdering(array $orderedIds, int $academicYearId)
{
    try {
        $success = $this->courseRepository->updateCourseOrdering($orderedIds, $academicYearId);
        
        if ($success) {
            session()->flash('success', 'Thứ tự lớp học đã được cập nhật.');
        } else {
            session()->flash('error', 'Không thể cập nhật thứ tự lớp học.');
        }
    } catch (\Exception $e) {
        session()->flash('error', 'Lỗi khi cập nhật thứ tự: ' . $e->getMessage());
    }
}
```

---

## 🎯 Cách Sử Dụng

### **1. Kéo Thả Course**
- Kéo icon `bars-3` để di chuyển course
- Thứ tự sẽ được cập nhật tự động
- Chỉ course trong cùng năm học mới có thể kéo thả

### **2. Responsive Design**
- **Desktop**: Table view với drag handle
- **Mobile**: Card view với drag handle
- Cả hai đều hỗ trợ sortable

### **3. Group Ordering**
- Mỗi năm học có ordering riêng
- Kéo thả chỉ ảnh hưởng đến course trong cùng năm học
- Academic year được truyền qua data attribute

---

## 🎨 UI/UX Features

### **Visual Indicators**
- ✅ Drag handle với icon `bars-3`
- ✅ Cursor pointer khi hover
- ✅ Animation 150ms khi kéo thả
- ✅ Responsive design

### **Data Attributes**
- ✅ `data-id`: Course ID
- ✅ `data-academic-year-id`: Năm học hiện tại
- ✅ `wire:key`: Livewire reactivity

### **Error Handling**
- ✅ Try-catch trong Livewire method
- ✅ Flash messages cho user
- ✅ Validation academic year

---

## 🔍 Technical Details

### **Sortable Configuration**
```javascript
{
    animation: 150,           // Animation duration
    handle: '.drag-handle',   // Only drag by handle
    onEnd: function() {       // Callback after drop
        // Get ordered IDs
        // Get academic year
        // Call Livewire method
    }
}
```

### **Livewire Integration**
```php
// Repository method
public function updateCourseOrdering(array $orderedIds, int $academicYearId): bool

// Livewire method
public function updateCourseOrdering(array $orderedIds, int $academicYearId)

// JavaScript call
$wire.updateCourseOrdering(orderedIds, academicYearId);
```

---

## 🚨 Lưu Ý Quan Trọng

### **1. Academic Year Filter**
- Sortable chỉ hoạt động khi có `selectedYear`
- Course phải thuộc cùng năm học
- Data attribute `data-academic-year-id` phải được set

### **2. Performance**
- Sortable instance được cache để tránh tạo lại
- Chỉ khởi tạo khi element tồn tại
- Animation mượt mà với 150ms

### **3. Responsive**
- Desktop và Mobile có sortable riêng
- Cùng logic nhưng khác UI
- Wire:key khác nhau cho từng view

---

## 🎉 Kết Quả

### **✅ Tính Năng Hoàn Chỉnh:**
- 🎯 Drag-drop sortable cho Course
- 📱 Responsive design (Desktop + Mobile)
- 🔄 Auto update ordering
- 🎨 Beautiful UI với Flux components
- ⚡ Fast performance với Alpine.js
- 🛡️ Error handling đầy đủ

### **🚀 Sẵn Sàng Sử Dụng:**
- Repository đã được tích hợp
- Livewire component đã có method
- View đã được cập nhật với sortable
- JavaScript đã được cấu hình

**Drag-drop sortable cho Course đã hoàn thành!** 🎉
