import AppHeaderAddButton from '@/components/app-header-add-button';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import type { Transaction } from '@/types/academic';
import { soundToast } from '@/utils/sound-toast';
import { Head, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { createTransactionColumns } from './columns';
import ErrorBoundary from '@/components/error-boundary';
import { router } from '@inertiajs/react';
import { AppDataTablePage, ColumnDefinition } from '@/components/app-data-table-page';
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

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Tiền quỹ', href: '' },
    { title: 'Giao dịch', href: '/finance/transactions' },
];

export interface Props {
    transactions?: {
        data: Transaction[];
        links: { url: string | null; label: string; active: boolean }[];
        total: number;
        from: number;
        to: number;
        current_page: number;
        last_page: number;
        per_page: number;
    };
}

export default function TransactionIndex({ transactions = { data: [], links: [], total: 0, from: 0, to: 0, current_page: 1, last_page: 1, per_page: 10 } }: Props) {
    const [searchTerm, setSearchTerm] = useState('');
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [itemToDelete, setItemToDelete] = useState<Transaction | null>(null);
    const [columnVisibility, setColumnVisibility] = useState<Record<string, boolean>>({
        select: true,
        transaction_date: true,
        title: true,
        type: true,
        amount: true,
        description: true,
        files: true,
        actions: true,
    });

    // Column definitions for visibility control
    const columnDefinitions: ColumnDefinition[] = [
        { id: 'select', label: 'Chọn' },
        { id: 'transaction_date', label: 'Ngày giao dịch' },
        { id: 'title', label: 'Tiêu đề' },
        { id: 'type', label: 'Loại' },
        { id: 'amount', label: 'Số tiền' },
        { id: 'description', label: 'Mô tả' },
        { id: 'files', label: 'Tài liệu' },
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
        router.visit('/finance/transactions/create');
    };

    const handleEdit = (item: Transaction) => {
        router.visit(`/finance/transactions/${item.id}/edit`);
    };


    const handleDelete = (item: Transaction) => {
        setItemToDelete(item);
        setDeleteDialogOpen(true);
    };

    const confirmDelete = () => {
        if (itemToDelete) {
            router.delete(`/finance/transactions/${itemToDelete.id}`, {
                onSuccess: () => {
                    setDeleteDialogOpen(false);
                    setItemToDelete(null);
                },
            });
        }
    };


    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Giao dịch" />

            <div className="px-4 py-6">
                {/* Header + Button */}
                <AppHeaderAddButton
                    title="Giao dịch"
                    description="Quản lý các giao dịch tài chính trong hệ thống, bao gồm thu nhập và chi phí."
                    buttonLabel="Thêm giao dịch"
                    onButtonClick={handleAddClick}
                />

                {/* Data Table Page */}
                <ErrorBoundary>
                    <AppDataTablePage
                        columns={createTransactionColumns({
                            onEdit: handleEdit,
                            onDelete: handleDelete,
                        })}
                        data={transactions.data.filter(transaction => 
                            transaction.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                            (transaction.description && transaction.description.toLowerCase().includes(searchTerm.toLowerCase()))
                        )}
                        searchTerm={searchTerm}
                        onSearchChange={setSearchTerm}
                        searchPlaceholder="Tìm kiếm giao dịch..."
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
                            current_page: transactions.current_page,
                            last_page: transactions.last_page,
                            per_page: transactions.per_page,
                            total: transactions.total,
                            from: transactions.from,
                            to: transactions.to,
                            links: transactions.links,
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
                            Xác nhận xóa bản ghi
                        </AlertDialogTitle>
                        <AlertDialogDescription>
                            Bạn có chắc chắn muốn xóa bản ghi "
                            {itemToDelete?.title}"? Hành động này không thể hoàn
                            tác và sẽ xóa vĩnh viễn tất cả dữ liệu liên quan đến
                            bản ghi này.
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