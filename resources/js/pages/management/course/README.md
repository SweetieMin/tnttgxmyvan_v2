# Course Data Table

Cấu trúc DataTable cho trang quản lý lớp giáo lý được xây dựng theo hướng dẫn của shadcn/ui.

## Cấu trúc Files

```
course/
├── index.tsx          # Server component - trang chính
├── columns.tsx        # Client component - định nghĩa các cột
├── data-table.tsx     # Client component - DataTable component
└── README.md          # Tài liệu hướng dẫn
```

## Backend Requirements

### Controller (CourseController.php)

```php
public function index(Request $request)
{
    // Get academic years for filter
    $academicYears = $this->academicYearRepository->all();
  
    // Get courses with pagination and academic year relationship
    $courses = $this->courseRepository->paginateWith(['academic_year'], 10);
  
    // Get current academic year filter
    $currentAcademicYearId = $request->get('academic_year_id');
  
    // Filter by academic year if specified
    if ($currentAcademicYearId) {
        $courses = $this->courseRepository->getModel()
            ->with(['academic_year'])
            ->where('academic_year_id', $currentAcademicYearId)
            ->paginate(10);
    }
  
    return Inertia::render('management/course/index', [
        'courses' => $courses,
        'academicYears' => $academicYears,
        'currentAcademicYearId' => $currentAcademicYearId,
    ]);
}
```

### Model Relationships

```php
// Course.php
public function academicYear()
{
    return $this->belongsTo(AcademicYear::class);
}
```

### Required Data Structure

- `courses`: Paginated data với relationship `academic_year`
- `academicYears`: Array of academic years cho filter
- `currentAcademicYearId`: ID của academic year hiện tại được filter

## Các tính năng

### 1. Sorting (Sắp xếp)

- Click vào header của cột để sắp xếp
- Hỗ trợ sắp xếp tăng dần/giảm dần
- Icon hiển thị trạng thái sắp xếp

### 2. Filtering (Lọc)Tìm kiếm theo tên lớp giáo lý

- Lọc theo niên khóa (dropdown)

### 3. Pagination (Phân trang)

- Điều hướng trang với các nút Previous/Next
- Hiển thị thông tin số lượng items

### 4. Row Selection (Chọn hàng)

- Hỗ trợ chọn nhiều hàng (có thể bật/tắt)
- Hiển thị số lượng hàng được chọn
- Callback khi selection thay đổi

## Cách sử dụng

### Trong index.tsx:

```tsx
<DataTable
    columns={createCourseColumns({
        onEdit: handleEdit,
        onDelete: handleDelete,
    })}
    data={courses.data}
    searchKey="name"
    searchPlaceholder="Tìm kiếm lớp giáo lý..."
    enableRowSelection={true}
    onRowSelectionChange={(selectedRows) => {
        console.log('Selected rows:', selectedRows);
    }}
/>
```

### Định nghĩa columns trong columns.tsx:

```tsx
export const createCourseColumns = ({ onEdit, onDelete }: CourseColumnsProps): ColumnDef<Course>[] => [
    {
        accessorKey: "name",
        header: "Tên lớp giáo lý",
        enableSorting: true,
        cell: ({ row }) => (
            <div className="font-semibold">
                {row.getValue("name")}
            </div>
        ),
    },
    // ... các cột khác
];
```

## Dependencies

- `@tanstack/react-table` - Core table functionality
- `@radix-ui/react-*` - UI components
- `lucide-react` - Icons
- `tailwindcss` - Styling

## Customization

### Thêm cột mới:

1. Thêm định nghĩa cột trong `columns.tsx`
2. Cấu hình `accessorKey`, `header`, `cell` render
3. Bật/tắt sorting với `enableSorting`

### Thêm filter mới:

1. Thêm input filter trong `data-table.tsx`
2. Cấu hình `searchKey` tương ứng
3. Xử lý logic filter trong `onColumnFiltersChange`

### Custom styling:

- Sử dụng Tailwind CSS classes
- Override styles trong `cell` render functions
- Customize table appearance trong `data-table.tsx`

## 🚨 Troubleshooting

### Lỗi "Cannot read properties of undefined (reading 'length')"
**Nguyên nhân**: DataTable nhận được data không đúng cấu trúc từ backend.

**Giải pháp**:
1. Kiểm tra Controller có trả về đúng cấu trúc data:
   ```php
   return Inertia::render('management/course/index', [
       'courses' => $courses,           // Phải có pagination
       'academicYears' => $academicYears, // Phải có array
   ]);
   ```

2. Kiểm tra Model có relationship:
   ```php
   // Course.php
   public function academicYear()
   {
       return $this->belongsTo(AcademicYear::class);
   }
   ```

3. Kiểm tra Repository có method `paginateWith`:
   ```php
   $courses = $this->courseRepository->paginateWith(['academic_year'], 10);
   ```

### Lỗi "Cannot read properties of undefined (reading 'map')"
**Nguyên nhân**: Props không có default values.

**Giải pháp**: Đã được sửa trong component với default values:
```typescript
export default function CourseIndex({ 
    courses = { data: [], links: [], total: 0, from: 0, to: 0, current_page: 1, last_page: 1, per_page: 10 }, 
    academicYears = [], 
    currentAcademicYearId 
}: Props) {
```

## 🎉 Kết luận

DataTable đã được triển khai thành công theo chuẩn [shadcn/ui Data Table](https://ui.shadcn.com/docs/components/data-table#basic-table) với đầy đủ tính năng cơ bản và có thể mở rộng dễ dàng. Code sạch, type-safe và tuân thủ best practices của React và TypeScript.
