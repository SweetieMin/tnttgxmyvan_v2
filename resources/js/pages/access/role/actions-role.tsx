import { Button } from '@/components/ui/button';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
    InputGroupText,
} from '@/components/ui/input-group';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { index as roles } from '@/routes/access/roles';
import { type BreadcrumbItem } from '@/types';
import type { Role } from '@/types/academic';
import { Head, useForm, usePage, router } from '@inertiajs/react';
import { Search, ArrowLeft } from 'lucide-react';
import { useEffect, useState } from 'react';

const getBreadcrumbs = (mode: 'create' | 'edit', roleName?: string): BreadcrumbItem[] => [
    { title: 'Quản lý', href: '' },
    { title: 'Vai trò', href: roles().url },
    { title: mode === 'create' ? 'Thêm mới' : `Chỉnh sửa: ${roleName || ''}`, href: '' },
];

export interface Props {
    role?: Role;
    allRoles?: Role[];
    managedRoles?: Role[];
    mode: 'create' | 'edit';
}

export default function ActionsRole({ role, allRoles = [], managedRoles = [], mode }: Props) {
    const [selectedManagedRoles, setSelectedManagedRoles] = useState<Set<number>>(new Set());
    const [hierarchySearchTerm, setHierarchySearchTerm] = useState('');

    // Initialize selectedManagedRoles with current managed roles for edit mode
    useEffect(() => {
        if (mode === 'edit' && managedRoles) {
            const managedRoleIds = new Set(managedRoles.map(r => r.id));
            setSelectedManagedRoles(managedRoleIds);
        }
    }, [managedRoles, mode]);

    const { data, setData, post, put, processing, errors, clearErrors } = useForm({
        name: role?.name || '',
        description: role?.description || '',
        ordering: role?.ordering || 1,
        managed_role_ids: [] as number[],
    });



    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
    
        // Update form data with selected managed roles
        setData('managed_role_ids', Array.from(selectedManagedRoles));
    
        if (mode === 'edit' && role) {
            put(`/access/roles/${role.id}`, {
                onSuccess: () => {
                    router.visit('/access/roles');
                },
                onError: (errors: any) => {
                    console.error('Validation errors:', errors);
                }
            });
        } else {
            post('/access/roles', {
                onSuccess: () => {
                    router.visit('/access/roles');
                },
                onError: (errors: any) => {
                    console.error('Validation errors:', errors);
                }
            });
        }
    };
    
    

    const handleCancel = () => {
        router.visit('/access/roles');
    };

    // Role hierarchy helper functions
    const handleRoleToggle = (roleId: number, checked: boolean) => {
        const newSelected = new Set(selectedManagedRoles);
        if (checked) {
            newSelected.add(roleId);
        } else {
            newSelected.delete(roleId);
        }
        setSelectedManagedRoles(newSelected);
        setData('managed_role_ids', Array.from(newSelected));
    };

    const handleSelectAllManagedRoles = () => {
        const availableRoles = allRoles.filter(r => 
            (mode === 'edit' ? r.id !== role?.id : true) && 
            r.name.toLowerCase().includes(hierarchySearchTerm.toLowerCase())
        );
        const allIds = new Set(availableRoles.map(r => r.id));
        setSelectedManagedRoles(allIds);
        setData('managed_role_ids', Array.from(allIds));
    };

    const handleSelectNoneManagedRoles = () => {
        setSelectedManagedRoles(new Set());
        setData('managed_role_ids', []);
    };

    // Filter available roles for hierarchy
    const availableManagedRoles = allRoles.filter(r => 
        (mode === 'edit' ? r.id !== role?.id : true) && 
        (r.name.toLowerCase().includes(hierarchySearchTerm.toLowerCase()) ||
         (r.description && r.description.toLowerCase().includes(hierarchySearchTerm.toLowerCase())))
    );

    // Group roles into rows of 4-5 roles each
    const groupRolesIntoRows = (roles: Role[], rolesPerRow: number = 4) => {
        const rows: Role[][] = [];
        for (let i = 0; i < roles.length; i += rolesPerRow) {
            rows.push(roles.slice(i, i + rolesPerRow));
        }
        return rows;
    };

    const roleRows = groupRolesIntoRows(availableManagedRoles, 4);

    return (
        <AppLayout breadcrumbs={getBreadcrumbs(mode, role?.name)}>
            <Head title={mode === 'create' ? 'Thêm vai trò mới' : `Chỉnh sửa vai trò: ${role?.name}`} />

            <div className="px-4 py-6">

                    {/* Header */}
                    <div className="mb-6">
                        <div className="flex items-center gap-4 mb-4">
                            <Button
                                variant="outline"
                                size="sm"
                                onClick={handleCancel}
                                className="flex items-center gap-2"
                            >
                                <ArrowLeft className="h-4 w-4" />
                                Quay lại
                            </Button>
                        </div>
                        <h1 className="text-2xl font-bold">
                            {mode === 'create' ? 'Thêm vai trò mới' : `Chỉnh sửa vai trò: ${role?.name}`}
                        </h1>
                        <p className="text-muted-foreground">
                            {mode === 'create' 
                                ? 'Nhập thông tin để tạo vai trò mới trong hệ thống'
                                : 'Cập nhật thông tin vai trò hiện tại'
                            }
                        </p>
                    </div>

                    <form onSubmit={handleSubmit} className="space-y-6">
                        {/* Basic Information */}
                        <div className="rounded-lg border bg-card p-6">
                            <h2 className="text-lg font-semibold mb-4">Thông tin cơ bản</h2>
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="name">
                                        Tên vai trò *
                                    </Label>
                                    <InputGroup>
                                        <InputGroupInput
                                            id="name"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            placeholder="VD: Admin"
                                        />
                                        <InputGroupAddon>
                                            <InputGroupText>
                                                Vai trò
                                            </InputGroupText>
                                        </InputGroupAddon>
                                    </InputGroup>
                                    {errors.name && (
                                        <p className="text-sm text-red-500">
                                            {errors.name}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="ordering">
                                        Thứ tự *
                                    </Label>
                                    <Input
                                        id="ordering"
                                        type="number"
                                        min="1"
                                        value={data.ordering}
                                        onChange={(e) =>
                                            setData('ordering', parseInt(e.target.value) || 1)
                                        }
                                        placeholder="1"
                                    />
                                    {errors.ordering && (
                                        <p className="text-sm text-red-500">
                                            {errors.ordering}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="space-y-2 mt-4">
                                <Label htmlFor="description">Mô tả</Label>
                                <textarea
                                    id="description"
                                    className="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="Nhập mô tả chi tiết về vai trò..."
                                />
                                {errors.description && (
                                    <p className="text-sm text-red-500">
                                        {errors.description}
                                    </p>
                                )}
                            </div>
                        </div>

                        {/* Role Hierarchy Section */}
                        <div className="rounded-lg border bg-card p-6">
                            <div className="space-y-4">
                                <div>
                                    <h2 className="text-lg font-semibold">Vai trò được quản lý</h2>
                                    <p className="text-sm text-muted-foreground">
                                        Chọn các vai trò mà vai trò này có thể quản lý
                                    </p>
                                </div>

                                {/* Search and Controls */}
                                <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div className="flex items-center gap-2">
                                        <Search className="h-4 w-4 text-muted-foreground" />
                                        <Input
                                            placeholder="Tìm kiếm vai trò..."
                                            value={hierarchySearchTerm}
                                            onChange={(e) => setHierarchySearchTerm(e.target.value)}
                                            className="max-w-sm"
                                        />
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            onClick={handleSelectAllManagedRoles}
                                        >
                                            Chọn tất cả
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            onClick={handleSelectNoneManagedRoles}
                                        >
                                            Bỏ chọn tất cả
                                        </Button>
                                    </div>
                                </div>

                                {/* Role Selection - 4-5 roles per row */}
                                <div className="space-y-4">
                                    {roleRows.length === 0 ? (
                                        <div className="text-center py-8 text-muted-foreground">
                                            {hierarchySearchTerm ? 'Không tìm thấy vai trò nào phù hợp' : 'Không có vai trò nào để chọn'}
                                        </div>
                                    ) : (
                                        roleRows.map((row, rowIndex) => (
                                            <div key={rowIndex} className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                                {row.map((r) => {
                                                    const isSelected = selectedManagedRoles.has(r.id);
                                                    
                                                    return (
                                                        <div
                                                            key={r.id}
                                                            className={`flex items-center space-x-3 p-3 rounded-lg border transition-colors ${
                                                                isSelected 
                                                                    ? 'bg-primary/5 border-primary/20' 
                                                                    : 'hover:bg-muted/30'
                                                            }`}
                                                        >
                                                            <Checkbox
                                                                id={`managed-role-${r.id}`}
                                                                checked={isSelected}
                                                                onCheckedChange={(checked) => 
                                                                    handleRoleToggle(r.id, !!checked)
                                                                }
                                                            />
                                                            <div className="flex-1 min-w-0">
                                                                <Label
                                                                    htmlFor={`managed-role-${r.id}`}
                                                                    className="font-medium cursor-pointer text-sm"
                                                                >
                                                                    {r.name}
                                                                </Label>
                                                                {r.description && (
                                                                    <p className="text-xs text-muted-foreground mt-1 line-clamp-2">
                                                                        {r.description}
                                                                    </p>
                                                                )}
                                                                <p className="text-xs text-muted-foreground mt-1">
                                                                    Thứ tự: {r.ordering}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    );
                                                })}
                                            </div>
                                        ))
                                    )}
                                </div>

                                {/* Summary */}
                                <div className="text-sm text-muted-foreground bg-muted/50 p-3 rounded-md">
                                    ✓ Đã chọn {selectedManagedRoles.size} vai trò
                                </div>
                                
                                {/* Validation errors for managed_role_ids */}
                                {errors.managed_role_ids && (
                                    <div className="text-sm text-red-500 bg-red-50 p-2 rounded-md">
                                        {errors.managed_role_ids}
                                    </div>
                                )}
                                {(errors as any)['managed_role_ids.*'] && (
                                    <div className="text-sm text-red-500 bg-red-50 p-2 rounded-md">
                                        {(errors as any)['managed_role_ids.*']}
                                    </div>
                                )}
                            </div>
                        </div>

                        <Separator />
                        <div className="flex justify-end gap-3">
                            <Button
                                type="button"
                                variant="outline"
                                onClick={handleCancel}
                            >
                                Hủy
                            </Button>
                            <Button type="submit" disabled={processing}>
                                {processing 
                                    ? (mode === 'create' ? 'Đang tạo...' : 'Đang cập nhật...')
                                    : (mode === 'create' ? 'Tạo vai trò' : 'Cập nhật vai trò')
                                }
                            </Button>
                        </div>
                    </form>

            </div>
        </AppLayout>
    );
}
