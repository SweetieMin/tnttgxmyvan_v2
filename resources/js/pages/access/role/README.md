# Role Hierarchy Management

## Tổng quan

Chức năng quản lý phân cấp vai trò cho phép thiết lập mối quan hệ quản lý giữa các vai trò trong hệ thống. Tương tự như hệ thống role-permission, nhưng ở đây là role-role mapping thông qua bảng `role_hierarchies`.

## Cách sử dụng

### 1. Truy cập chức năng

- Vào trang **Quản lý > Vai trò**
- Trong bảng danh sách vai trò, click vào menu **⋮** ở cột "Thao tác"
- Chọn **"Quản lý vai trò"**

### 2. Giao diện quản lý

- **Tìm kiếm**: Sử dụng ô tìm kiếm để lọc các vai trò
- **Chọn vai trò**: Đánh dấu checkbox để chọn vai trò được quản lý
- **Chọn tất cả/Bỏ chọn tất cả**: Nút tiện ích để chọn nhanh
- **Lưu thay đổi**: Click "Lưu thay đổi" để cập nhật

### 3. Quy tắc

- Một vai trò không thể quản lý chính nó
- Vai trò hiện tại sẽ được hiển thị nhưng không thể chọn
- Có thể chọn nhiều vai trò để quản lý

## Cấu trúc dữ liệu

### Bảng `role_hierarchies`

```sql
- id: Primary key
- role_id: ID của vai trò quản lý (foreign key)
- manages_role_id: ID của vai trò được quản lý (foreign key)
- created_at, updated_at: Timestamps
- unique(role_id, manages_role_id): Đảm bảo không trùng lặp
```

### Relationships trong Model

- `Role::subRoles()`: Các vai trò cấp dưới được quản lý
- `Role::superRoles()`: Các vai trò cấp trên quản lý vai trò này
- `Role::roleHierarchies()`: Các bản ghi hierarchy mà vai trò này là parent
- `Role::managedByHierarchies()`: Các bản ghi hierarchy mà vai trò này là child

## API Endpoints

### GET `/management/roles/{role}/hierarchy`

Hiển thị trang quản lý phân cấp vai trò

**Response:**

```json
{
  "role": { "id": 1, "name": "Admin", ... },
  "availableRoles": [{ "id": 2, "name": "Manager", ... }],
  "managedRoles": [{ "id": 3, "name": "User", ... }]
}
```

### POST `/management/roles/{role}/hierarchy`

Cập nhật phân cấp vai trò

**Request:**

```json
{
  "managed_role_ids": [2, 3, 4]
}
```

**Response:**

- Redirect back với flash message thành công

## Ví dụ sử dụng

### Thiết lập phân cấp

1. **Admin** quản lý tất cả vai trò khác
2. **Manager** quản lý **User** và **Guest**
3. **User** không quản lý vai trò nào

### Kết quả trong database

```sql
-- Admin quản lý Manager và User
INSERT INTO role_hierarchies (role_id, manages_role_id) VALUES (1, 2);
INSERT INTO role_hierarchies (role_id, manages_role_id) VALUES (1, 3);

-- Manager quản lý User
INSERT INTO role_hierarchies (role_id, manages_role_id) VALUES (2, 3);
```

## Lưu ý kỹ thuật

### Frontend

- Sử dụng React với Inertia.js
- Component `hierarchy.tsx` xử lý giao diện
- State management với `useState` và `useForm`
- Validation phía client và server

### Backend

- Controller `RoleHierarchyController` xử lý logic
- Model `Role` và `RoleHierarchy` quản lý dữ liệu
- Middleware authentication bảo vệ routes
- Validation request đảm bảo tính toàn vẹn dữ liệu

### Bảo mật

- Chỉ user đã xác thực mới có thể truy cập
- Validation đảm bảo role_id tồn tại
- Ngăn chặn self-management (vai trò không thể quản lý chính nó)
