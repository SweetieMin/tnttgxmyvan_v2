# 📚 Hướng Dẫn Sử Dụng CourseRepository

## 🎯 Tổng Quan

CourseRepository đã được thiết kế riêng cho Course model với đầy đủ tính năng ordering theo `academic_year_id`. Repository này hỗ trợ:

- ✅ **Auto Ordering**: Tự động gán ordering khi create/delete
- ✅ **Group Ordering**: Ordering riêng theo từng năm học
- ✅ **Sortable**: Hỗ trợ drag-drop sortable
- ✅ **Validation**: Kiểm tra và sửa lỗi ordering
- ✅ **Search & Filter**: Tìm kiếm và lọc theo năm học

---

## 🏗️ Cấu Trúc Repository

### 1. CourseRepository (Eloquent)
```php
// app/Repositories/Eloquent/CourseRepository.php
class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    use HasSortableOrdering;
    
    protected ?string $groupColumn = 'academic_year_id';
}
```

### 2. CourseRepositoryInterface
```php
// app/Repositories/Interfaces/CourseRepositoryInterface.php
interface CourseRepositoryInterface
{
    // Basic CRUD
    public function create(array $data): Course;
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
    
    // Ordering & Sortable
    public function updateOrdering(array $orderedIds, int $academicYearId): bool;
    public function moveCourseUp(int $courseId, int $academicYearId): bool;
    public function moveCourseDown(int $courseId, int $academicYearId): bool;
    
    // Search & Filter
    public function courseWithSearchAndYear(?string $search = null, ?int $year = null): LengthAwarePaginator;
    public function getByAcademicYear(int $academicYearId): Collection;
}
```

---

## 🚀 Cách Sử Dụng

### 1. Dependency Injection trong Livewire

```php
// app/Livewire/Management/Course/ActionsCourse.php
class ActionsCourse extends Component
{
    protected CourseRepositoryInterface $courseRepository;

    public function boot(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }
}
```

### 2. Tạo Course Mới

```php
public function createCourse()
{
    $data = [
        'academic_year_id' => 1,
        'program_id' => 2,
        'course' => 'Lớp Giáo Lý A'
    ];

    // Tự động gán ordering trong năm học
    $course = $this->courseRepository->create($data);
}
```

### 3. Cập Nhật Course

```php
public function updateCourse($id)
{
    $data = [
        'academic_year_id' => 1,
        'program_id' => 2,
        'course' => 'Lớp Giáo Lý B'
    ];

    // Tự động reorder nếu thay đổi năm học
    $this->courseRepository->update($id, $data);
}
```

### 4. Xóa Course

```php
public function deleteCourse($id)
{
    // Tự động reorder sau khi xóa
    $this->courseRepository->delete($id);
}
```

---

## 🎯 Tính Năng Ordering

### 1. Auto Ordering khi Create

```php
// Repository tự động gán ordering = max(ordering) + 1 trong năm học
$course = $courseRepository->create([
    'academic_year_id' => 1,
    'program_id' => 2,
    'course' => 'Lớp mới'
]);
// ordering sẽ được gán tự động
```

### 2. Reorder khi Delete

```php
// Sau khi xóa, repository tự động reorder các course còn lại
$courseRepository->delete($courseId);
// Các course khác trong cùng năm học sẽ được reorder
```

### 3. Drag-Drop Sortable

```php
// Cập nhật ordering sau drag-drop
$orderedIds = [3, 1, 2]; // ID theo thứ tự mới
$academicYearId = 1;

$success = $courseRepository->updateOrdering($orderedIds, $academicYearId);
```

### 4. Di Chuyển Course

```php
// Di chuyển lên trên
$courseRepository->moveCourseUp($courseId, $academicYearId);

// Di chuyển xuống dưới
$courseRepository->moveCourseDown($courseId, $academicYearId);

// Di chuyển đến vị trí cụ thể
$courseRepository->moveCourseToPosition($courseId, 2, $academicYearId);
```

---

## 🔍 Search & Filter

### 1. Tìm Kiếm với Pagination

```php
// Tìm kiếm course theo tên và năm học
$courses = $courseRepository->courseWithSearchAndYear(
    search: 'Giáo Lý',  // Tìm kiếm theo tên
    year: 1            // Filter theo năm học
);
```

### 2. Lấy Course theo Năm Học

```php
// Lấy tất cả course trong năm học với ordering
$courses = $courseRepository->getByAcademicYear($academicYearId);
```

### 3. Lấy Course với Relations

```php
// Lấy course với academicYear và program
$course = $courseRepository->findWithRelations($courseId);
```

---

## 🛠️ Validation & Maintenance

### 1. Kiểm Tra Course Tồn Tại

```php
// Kiểm tra course đã tồn tại trong năm học chưa
$exists = $courseRepository->existsInAcademicYear(
    course: 'Lớp Giáo Lý A',
    academicYearId: 1,
    excludeId: 5 // Loại trừ course hiện tại khi update
);
```

### 2. Validate Ordering

```php
// Kiểm tra ordering có vấn đề không
$issues = $courseRepository->validateAndFixOrdering($academicYearId);

if (!empty($issues)) {
    // Có vấn đề về ordering, đã được tự động sửa
    foreach ($issues as $issue) {
        echo $issue; // "Course ID 3 có ordering 5, mong đợi 3"
    }
}
```

### 3. Lấy Ordering Tiếp Theo

```php
// Lấy ordering tiếp theo trong năm học
$nextOrdering = $courseRepository->getNextOrdering($academicYearId);
// Trả về: max(ordering) + 1
```

---

## 🎨 Frontend Integration

### 1. Sortable JavaScript

```javascript
// Sử dụng SortableJS cho drag-drop
import Sortable from 'sortablejs';

// Khởi tạo sortable
const sortable = Sortable.create('#course-list', {
    animation: 150,
    onEnd: function(evt) {
        // Lấy thứ tự mới
        const orderedIds = Array.from(evt.to.children).map(el => el.dataset.id);
        const academicYearId = evt.to.dataset.academicYearId;
        
        // Gửi request cập nhật ordering
        Livewire.emit('updateCourseOrdering', orderedIds, academicYearId);
    }
});
```

### 2. Livewire Methods

```php
// Trong Livewire component
public function updateCourseOrdering(array $orderedIds, int $academicYearId)
{
    $success = $this->courseRepository->updateOrdering($orderedIds, $academicYearId);
    
    if ($success) {
        session()->flash('success', 'Thứ tự lớp học đã được cập nhật.');
    }
}
```

---

## 📊 Ví Dụ Hoàn Chỉnh

### 1. Controller Method

```php
public function index(Request $request)
{
    $search = $request->get('search');
    $year = $request->get('year');
    
    $courses = $this->courseRepository->courseWithSearchAndYear($search, $year);
    
    return view('courses.index', compact('courses'));
}
```

### 2. Livewire Component

```php
class CoursesIndex extends Component
{
    public $search = '';
    public $selectedYear = null;
    
    public function render()
    {
        $courses = $this->courseRepository->courseWithSearchAndYear(
            $this->search, 
            $this->selectedYear
        );
        
        return view('livewire.courses-index', compact('courses'));
    }
    
    public function updateOrdering($orderedIds, $academicYearId)
    {
        $this->courseRepository->updateOrdering($orderedIds, $academicYearId);
        $this->dispatch('courses-updated');
    }
}
```

### 3. Blade Template

```blade
<!-- resources/views/livewire/courses-index.blade.php -->
<div>
    <div id="course-list" data-academic-year-id="{{ $selectedYear }}">
        @foreach($courses as $course)
            <div class="course-item" data-id="{{ $course->id }}">
                {{ $course->course }}
                <span class="ordering">{{ $course->ordering }}</span>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    // Khởi tạo sortable
    const sortable = Sortable.create(document.getElementById('course-list'), {
        animation: 150,
        onEnd: function(evt) {
            const orderedIds = Array.from(evt.to.children).map(el => el.dataset.id);
            const academicYearId = evt.to.dataset.academicYearId;
            
            @this.call('updateOrdering', orderedIds, academicYearId);
        }
    });
</script>
@endpush
```

---

## 🎯 Lợi Ích

### 1. **Tự Động Hóa**
- Tự động gán ordering khi tạo mới
- Tự động reorder khi xóa
- Tự động sửa lỗi ordering

### 2. **Linh Hoạt**
- Hỗ trợ nhiều năm học độc lập
- Drag-drop sortable
- Search và filter mạnh mẽ

### 3. **An Toàn**
- Validation đầy đủ
- Transaction rollback
- Error handling

### 4. **Dễ Sử Dụng**
- Interface rõ ràng
- Method names trực quan
- Documentation đầy đủ

---

## 🚨 Lưu Ý Quan Trọng

1. **Group Column**: Repository sử dụng `academic_year_id` làm group column
2. **Ordering**: Mỗi năm học có ordering riêng (1, 2, 3...)
3. **Validation**: Luôn kiểm tra course tồn tại trước khi tạo/update
4. **Performance**: Sử dụng transaction cho các thao tác ordering
5. **Error Handling**: Tất cả method đều có try-catch và logging

---

## 🎉 Kết Luận

CourseRepository đã được thiết kế đặc biệt cho Course model với đầy đủ tính năng ordering theo `academic_year_id`. Repository này giúp:

- ✅ Quản lý thứ tự lớp học dễ dàng
- ✅ Hỗ trợ drag-drop sortable
- ✅ Tự động hóa các thao tác ordering
- ✅ Validation và maintenance

Hãy sử dụng repository này để có trải nghiệm tốt nhất khi quản lý Course! 🚀
