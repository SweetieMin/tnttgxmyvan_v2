# Role Hierarchy Integration trong Modal

## Tổng quan
Chức năng quản lý phân cấp vai trò đã được tích hợp trực tiếp vào modal thêm/sửa role, thay vì tạo trang riêng. Điều này giúp người dùng có thể thiết lập role hierarchy ngay khi tạo hoặc chỉnh sửa role.

## Cách sử dụng

### 1. Thêm vai trò mới
1. Click **"Thêm vai trò"** trong trang quản lý vai trò
2. Điền thông tin cơ bản (tên, mô tả, thứ tự)
3. Click **"Hiện danh sách"** trong phần "Vai trò được quản lý"
4. Tìm kiếm và chọn các vai trò mà vai trò này có thể quản lý
5. Click **"Tạo mới"** để lưu

### 2. Chỉnh sửa vai trò
1. Click **"⋮"** → **"Chỉnh sửa"** trong bảng vai trò
2. Modal sẽ hiển thị với thông tin hiện tại và các vai trò đã được chọn
3. Có thể thay đổi thông tin cơ bản và vai trò được quản lý
4. Click **"Cập nhật"** để lưu thay đổi

## Tính năng trong Modal

### Phần Role Hierarchy
- **Nút "Hiện/Ẩn danh sách"**: Toggle hiển thị phần chọn vai trò
- **Tìm kiếm**: Lọc vai trò theo tên hoặc mô tả
- **Chọn tất cả/Bỏ chọn tất cả**: Nút tiện ích để chọn nhanh
- **Danh sách vai trò**: Hiển thị với checkbox, tên, mô tả và thứ tự
- **Tóm tắt**: Hiển thị số lượng vai trò đã chọn

### Quy tắc và Validation
- Vai trò không thể quản lý chính nó
- Validation phía client và server
- Tự động lọc ra vai trò hiện tại khỏi danh sách chọn

## Cấu trúc Code

### Frontend (React/TypeScript)
```typescript
// State management
const [selectedManagedRoles, setSelectedManagedRoles] = useState<Set<number>>(new Set());
const [hierarchySearchTerm, setHierarchySearchTerm] = useState('');
const [showHierarchySection, setShowHierarchySection] = useState(false);

// Form data includes managed_role_ids
const { data, setData } = useForm({
    id: 0,
    name: '',
    description: '',
    ordering: 1,
    managed_role_ids: [] as number[],
});
```

### Backend (Laravel/PHP)
```php
// RoleController methods
public function store(RoleRequest $request) {
    $role = $this->roleRepository->create($request->all());
    $this->updateRoleHierarchy($role->id, $request->input('managed_role_ids', []));
}

public function update(RoleRequest $request, string $id) {
    $this->roleRepository->update($id, $request->all());
    $this->updateRoleHierarchy($id, $request->input('managed_role_ids', []));
}

private function updateRoleHierarchy($roleId, $managedRoleIds) {
    RoleHierarchy::where('role_id', $roleId)->delete();
    foreach ($managedRoleIds as $managedRoleId) {
        if ($managedRoleId != $roleId) {
            RoleHierarchy::create([
                'role_id' => $roleId,
                'manages_role_id' => $managedRoleId,
            ]);
        }
    }
}
```

## Database Schema
```sql
-- role_hierarchies table
CREATE TABLE role_hierarchies (
    id BIGINT PRIMARY KEY,
    role_id BIGINT NOT NULL,
    manages_role_id BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE(role_id, manages_role_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (manages_role_id) REFERENCES roles(id) ON DELETE CASCADE
);
```

## API Endpoints
- **POST** `/access/roles` - Tạo vai trò mới với hierarchy
- **PUT** `/access/roles/{id}` - Cập nhật vai trò với hierarchy
- **GET** `/access/roles` - Lấy danh sách vai trò với sub_roles

## Validation Rules
```php
// RoleRequest validation
'managed_role_ids' => 'array',
'managed_role_ids.*' => 'integer|exists:roles,id',
```

## Lợi ích của Integration
1. **UX tốt hơn**: Không cần chuyển trang, tất cả trong một modal
2. **Workflow liền mạch**: Tạo/sửa role và thiết lập hierarchy cùng lúc
3. **Code gọn gàng**: Không cần controller và routes riêng
4. **Maintenance dễ dàng**: Logic tập trung trong RoleController

## Lưu ý kỹ thuật
- Sử dụng `with('subRoles')` để eager load relationships
- TypeScript interface Role đã được cập nhật với `sub_roles?: Role[]`
- State management với React hooks và Inertia.js forms
- Validation cả phía client và server
