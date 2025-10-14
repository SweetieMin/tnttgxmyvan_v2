import AppHeaderAddButton from '@/components/app-header-add-button';
import AppLayout from '@/layouts/app-layout';
import { index as roles } from '@/routes/access/roles';
import { type BreadcrumbItem } from '@/types';
import type { Role } from '@/types/academic';
import { soundToast } from '@/utils/sound-toast';
import { Head, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { createRoleColumns } from './columns';
import ErrorBoundary from '@/components/error-boundary';
import { router } from '@inertiajs/react';
import { AppDataTablePage, ColumnDefinition } from '@/components/app-data-table-page';

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

export default function RoleIndex({ roles = { data: [], links: [], total: 0, from: 0, to: 0, current_page: 1, last_page: 1, per_page: 10 }, allRoles = [] }: Props) {
    const [searchTerm, setSearchTerm] = useState('');
    const [columnVisibility, setColumnVisibility] = useState<Record<string, boolean>>({
        select: true,
        ordering: true,
        name: true,
        description: true,
        actions: true,
    });

    // Column definitions for visibility control
    const columnDefinitions: ColumnDefinition[] = [
        { id: 'select', label: 'Chọn' },
        { id: 'ordering', label: 'Thứ tự' },
        { id: 'name', label: 'Tên vai trò' },
        { id: 'description', label: 'Mô tả' },
        { id: 'actions', label: 'Thao tác' },
    ];

    const { flash } = usePage<{
        flash?: { success?: string; error?: string; message?: string };
    }>().props;

    useEffect(() => {
        if (flash?.success) soundToast('success', flash.success);
        else if (flash?.error) soundToast('error', flash.error);
        else if (flash?.message) soundToast('success', flash.message);
    }, [flash?.success, flash?.error, flash?.message]);


    const handleAddClick = () => {
        router.visit('/access/roles/create');
    };

    const handleEdit = (item: Role) => {
        router.visit(`/access/roles/${item.id}/edit`);
    };

    const handleDelete = (item: Role) => {
        if (confirm(`Bạn có chắc chắn muốn xóa vai trò "${item.name}"?`)) {
            router.delete(`/access/roles/${item.id}`, {
                onSuccess: () => {
                    // Success message will be handled by the controller
                },
                onError: (errors) => {
                    console.error('Error deleting role:', errors);
                }
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
                        data={roles.data.filter(role => 
                            role.name.toLowerCase().includes(searchTerm.toLowerCase())
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
        </AppLayout>
    );
}