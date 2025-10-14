import AppHeaderAddButton from '@/components/app-header-add-button';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
    InputGroupText,
} from '@/components/ui/input-group';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { index as roles } from '@/routes/access/roles';
import { type BreadcrumbItem } from '@/types';
import type { Role } from '@/types/academic';
import { soundToast } from '@/utils/sound-toast';
import { Head, useForm, usePage, router } from '@inertiajs/react';
import { Filter, ChevronDown } from 'lucide-react';
import { useEffect, useState } from 'react';
import { DataTable } from './data-table';
import { createRoleColumns } from './columns';
import ErrorBoundary from '@/components/error-boundary';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Quản lý', href: '' },
    { title: 'Vai trò', href: roles().url },
];

export interface Props {
    roles?: {
        data: Role[];
        links: { url: string | null; label: string; active: boolean }[];
        total: number;
        from: number;
        to: number;
        current_page: number;
        last_page: number;
        per_page: number;
    };
}

export default function RoleIndex({ roles = { data: [], links: [], total: 0, from: 0, to: 0, current_page: 1, last_page: 1, per_page: 10 } }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const [editingRole, setEditingRole] = useState<Role | null>(null);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [itemToDelete, setItemToDelete] = useState<Role | null>(null);
    const [searchTerm, setSearchTerm] = useState('');
    const [showColumnSelect, setShowColumnSelect] = useState(false);
    const [columnVisibility, setColumnVisibility] = useState<Record<string, boolean>>({
        select: true,
        ordering: true,
        name: true,
        description: true,
        actions: true,
    });

    // Column definitions for visibility control
    const columnDefinitions = [
        { id: 'select', label: 'Chọn' },
        { id: 'ordering', label: 'Thứ tự' },
        { id: 'name', label: 'Tên vai trò' },
        { id: 'description', label: 'Mô tả' },
        { id: 'actions', label: 'Thao tác' },
    ];

    // Helper functions for select all/none
    const handleSelectAll = () => {
        const allVisible = columnDefinitions.reduce((acc, col) => {
            acc[col.id] = true;
            return acc;
        }, {} as Record<string, boolean>);
        setColumnVisibility(allVisible);
    };

    const handleSelectNone = () => {
        const allHidden = columnDefinitions.reduce((acc, col) => {
            acc[col.id] = false;
            return acc;
        }, {} as Record<string, boolean>);
        setColumnVisibility(allHidden);
    };

    // Close column select when clicking outside
    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            const target = event.target as Element;
            if (showColumnSelect && !target.closest('.column-select-container')) {
                setShowColumnSelect(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [showColumnSelect]);

    const { flash } = usePage<{
        flash?: { success?: string; error?: string; message?: string };
    }>().props;

    useEffect(() => {
        if (flash?.success) soundToast('success', flash.success);
        else if (flash?.error) soundToast('error', flash.error);
        else if (flash?.message) soundToast('success', flash.message);
    }, [flash?.success, flash?.error, flash?.message]);

    const { data, setData, post, put, delete: destroy, processing, errors, clearErrors } =
        useForm({
            id: 0,
            name: '',
            description: '',
            ordering: 1,
        });

    const resetForm = () => {
        setData({ id: 0, name: '', description: '', ordering: 1 });
        setEditingRole(null);
        clearErrors();
    };

    const handleAddClick = () => {
        resetForm();
        setIsOpen(true);
    };

    const handleEdit = (item: Role) => {
        setEditingRole(item);
        setData({
            id: item.id,
            name: item.name,
            description: item.description || '',
            ordering: item.ordering,
        });
        setIsOpen(true);
    };

    const handleDelete = (item: Role) => {
        setItemToDelete(item);
        setDeleteDialogOpen(true);
    };

    const confirmDelete = () => {
        if (itemToDelete) {
            destroy(`/management/roles/${itemToDelete.id}`, {
                onSuccess: () => {
                    setDeleteDialogOpen(false);
                    setItemToDelete(null);
                },
            });
        }
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (editingRole) {
            put(`/management/roles/${editingRole.id}`, {
                onSuccess: () => {
                    setIsOpen(false);
                    resetForm();
                },
            });
        } else {
            post('/management/roles', {
                onSuccess: () => {
                    setIsOpen(false);
                    resetForm();
                },
            });
        }
    };

    const handleClose = () => { setIsOpen(false); resetForm(); };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Vai trò" />

            <div className="px-4 py-6">
                {/* Header + Button */}
                <AppHeaderAddButton
                    title="Vai trò"
                    description="Quản lý các vai trò trong hệ thống, bao gồm thông tin tên vai trò và mô tả chi tiết."
                    buttonLabel="Thêm vai trò"
                    onButtonClick={handleAddClick}
                />

                {/* Unified Filter Section */}
                <div className="mb-6 rounded-lg border bg-card p-4">
                    <div className="flex flex-col gap-4">
                        <div className="flex items-center gap-2">
                            <Filter className="h-4 w-4 text-muted-foreground" />
                            <span className="text-sm font-medium">Bộ lọc và tìm kiếm</span>
                        </div>
                        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                                <div className="flex items-center gap-2">
                                    <span className="text-sm text-muted-foreground">Tìm kiếm:</span>
                                    <Input
                                        placeholder="Tìm kiếm vai trò..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                    />
                                </div>
                            </div>
                            <div className="flex items-center gap-2">
                                <span className="text-sm text-muted-foreground">Cột hiển thị:</span>
                                <div className="relative column-select-container">
                                    <Button
                                        variant="outline"
                                        onClick={() => setShowColumnSelect(!showColumnSelect)}
                                        className="min-w-[120px] justify-between"
                                    >
                                        {Object.values(columnVisibility).filter(Boolean).length} cột
                                        <ChevronDown className="ml-2 h-4 w-4" />
                                    </Button>
                                    {showColumnSelect && (
                                        <div className="absolute top-full right-0 mt-1 w-64 bg-white border rounded-md shadow-lg z-50 p-2">
                                            <div className="flex justify-between items-center mb-2">
                                                <span className="text-sm font-medium">Chọn cột hiển thị</span>
                                                <div className="flex gap-1">
                                                    <Button
                                                        size="sm"
                                                        variant="ghost"
                                                        onClick={handleSelectAll}
                                                        className="text-xs h-6 px-2"
                                                    >
                                                        Tất cả
                                                    </Button>
                                                    <Button
                                                        size="sm"
                                                        variant="ghost"
                                                        onClick={handleSelectNone}
                                                        className="text-xs h-6 px-2"
                                                    >
                                                        Không
                                                    </Button>
                                                </div>
                                            </div>
                                            <div className="space-y-1 max-h-48 overflow-y-auto">
                                                {columnDefinitions.map((column) => (
                                                    <label
                                                        key={column.id}
                                                        className="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded cursor-pointer"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            checked={columnVisibility[column.id]}
                                                            onChange={(e) =>
                                                                setColumnVisibility(prev => ({
                                                                    ...prev,
                                                                    [column.id]: e.target.checked
                                                                }))
                                                            }
                                                            className="rounded"
                                                        />
                                                        <span className="text-sm">{column.label}</span>
                                                    </label>
                                                ))}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Data Table */}
                <ErrorBoundary>
                    <DataTable
                        columns={createRoleColumns({
                            onEdit: handleEdit,
                            onDelete: handleDelete,
                        })}
                        data={roles.data.filter(role => 
                            role.name.toLowerCase().includes(searchTerm.toLowerCase())
                        )}
                        columnVisibility={columnVisibility}
                        onColumnVisibilityChange={(updaterOrValue) => {
                            if (typeof updaterOrValue === 'function') {
                                setColumnVisibility(updaterOrValue);
                            } else {
                                setColumnVisibility(updaterOrValue);
                            }
                        }}
                        pagination={{
                            current_page: roles.current_page,
                            last_page: roles.last_page,
                            per_page: roles.per_page,
                            total: roles.total,
                            from: roles.from,
                            to: roles.to,
                            links: roles.links,
                        }}
                    />
                </ErrorBoundary>

                {/* Role Dialog */}
                <Dialog open={isOpen} onOpenChange={setIsOpen}>
                    <DialogContent className="max-h-[90vh] overflow-y-auto">
                        <DialogHeader>
                            <DialogTitle>
                                {editingRole
                                    ? 'Chỉnh sửa vai trò'
                                    : 'Thêm vai trò mới'}
                            </DialogTitle>
                            <DialogDescription>
                                {editingRole
                                    ? 'Cập nhật thông tin vai trò hiện tại'
                                    : 'Nhập thông tin để tạo vai trò mới'}
                            </DialogDescription>
                        </DialogHeader>
                        <Separator />
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="name">
                                        Tên vai trò *
                                    </Label>
                                    <InputGroup>
                                        <InputGroupInput
                                            id="name"
                                            value={data.name}
                                            onChange={(e) =>
                                                setData('name', e.target.value)
                                            }
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

                            <div className="space-y-2">
                                <Label htmlFor="description">Mô tả</Label>
                                <textarea
                                    id="description"
                                    className="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    value={data.description}
                                    onChange={(e) =>
                                        setData('description', e.target.value)
                                    }
                                    placeholder="Nhập mô tả chi tiết về vai trò..."
                                />
                                {errors.description && (
                                    <p className="text-sm text-red-500">
                                        {errors.description}
                                    </p>
                                )}
                            </div>

                            <Separator />
                            <DialogFooter>
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={handleClose}
                                >
                                    Hủy
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing
                                        ? 'Đang xử lý...'
                                        : editingRole
                                          ? 'Cập nhật'
                                          : 'Tạo mới'}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>

                {/* Delete Confirmation Dialog */}
                <AlertDialog
                    open={deleteDialogOpen}
                    onOpenChange={setDeleteDialogOpen}
                >
                    <AlertDialogContent>
                        <AlertDialogHeader>
                            <AlertDialogTitle>
                                Xác nhận xóa vai trò
                            </AlertDialogTitle>
                            <AlertDialogDescription>
                                Bạn có chắc chắn muốn xóa vai trò "
                                {itemToDelete?.name}"? Hành động này không thể
                                hoàn tác và sẽ xóa vĩnh viễn tất cả dữ liệu liên
                                quan đến vai trò này.
                            </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                            <AlertDialogCancel>Hủy</AlertDialogCancel>
                            <AlertDialogAction
                                onClick={confirmDelete}
                                className="bg-red-600 hover:bg-red-700"
                            >
                                Xóa
                            </AlertDialogAction>
                        </AlertDialogFooter>
                    </AlertDialogContent>
                </AlertDialog>
            </div>
        </AppLayout>
    );
}