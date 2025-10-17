import {
    AppDataTablePage,
    ColumnDefinition,
} from '@/components/app-data-table-page';
import AppHeaderAddButton from '@/components/app-header-add-button';
import ErrorBoundary from '@/components/error-boundary';
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
import AppLayout from '@/layouts/app-layout';
import { index as roles } from '@/routes/access/roles';
import { type BreadcrumbItem } from '@/types';
import type { Role } from '@/types/academic';
import { soundToast } from '@/utils/sound-toast';
import { Head, router, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { createRoleColumns } from './columns';

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
    allRoles?: Role[];
}

export default function RoleIndex({
    roles = {
        data: [],
        links: [],
        total: 0,
        from: 0,
        to: 0,
        current_page: 1,
        last_page: 1,
        per_page: 10,
    },
    allRoles = [],
}: Props) {
    const [searchTerm, setSearchTerm] = useState('');
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [columnVisibility, setColumnVisibility] = useState<
        Record<string, boolean>
    >({
        select: true,
        ordering: true,
        name: true,
        description: true,
        actions: true,
    });
    const [itemToDelete, setItemToDelete] = useState<Role | null>(null);

    // Column definitions for visibility control
    const columnDefinitions: ColumnDefinition[] = [
        { id: 'select', label: 'Chọn' },
        { id: 'ordering', label: 'Thứ tự' },
        { id: 'name', label: 'Tên vai trò' },
        { id: 'description', label: 'Mô tả' },
        { id: 'actions', label: 'Thao tác' },
    ];

    const handleAddClick = () => {
        router.visit('/access/roles/create');
    };

    const handleEdit = (item: Role) => {
        router.visit(`/access/roles/${item.id}/edit`);
    };

    const handleDelete = (item: Role) => {
        setItemToDelete(item);
        setDeleteDialogOpen(true);
    };

    const confirmDelete = () => {
        if (itemToDelete) {
            router.delete(`/access/roles/${itemToDelete.id}`, {
                onSuccess: () => {
                    setDeleteDialogOpen(false);
                    setItemToDelete(null);
                },
            });
        }
    };

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

                {/* Data Table Page */}
                <ErrorBoundary>
                    <AppDataTablePage
                        columns={createRoleColumns({
                            onEdit: handleEdit,
                            onDelete: handleDelete,
                        })}
                        data={roles.data.filter((role) =>
                            role.name
                                .toLowerCase()
                                .includes(searchTerm.toLowerCase()),
                        )}
                        searchTerm={searchTerm}
                        onSearchChange={setSearchTerm}
                        searchPlaceholder="Tìm kiếm vai trò..."
                        columnVisibility={columnVisibility}
                        onColumnVisibilityChange={(updaterOrValue) => {
                            if (typeof updaterOrValue === 'function') {
                                setColumnVisibility(updaterOrValue);
                            } else {
                                setColumnVisibility(updaterOrValue);
                            }
                        }}
                        columnDefinitions={columnDefinitions}
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
            </div>

            {/* Delete Confirmation Dialog */}
            <AlertDialog
                open={deleteDialogOpen}
                onOpenChange={setDeleteDialogOpen}
            >
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>
                            Xác nhận xóa chức vụ
                        </AlertDialogTitle>
                        <AlertDialogDescription>
                            Bạn có chắc chắn muốn xóa chức vụ "
                            {itemToDelete?.name}"? Hành động này không thể hoàn
                            tác và sẽ xóa vĩnh viễn tất cả dữ liệu liên quan đến
                            chức vụ này.
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
        </AppLayout>
    );
}
