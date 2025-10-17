import {
    AppDataTableTransaction,
    ColumnDefinition,
} from '@/components/app-data-table-transaction';
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
import { type BreadcrumbItem } from '@/types';
import type { Transaction } from '@/types/academic';
import { Head, router, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { createTransactionColumns } from './columns';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Tiền quỹ', href: '' },
    { title: 'Giao dịch', href: '/finance/transactions' },
];

export default function TransactionIndex() {
    const {
        transactions,
        summary,
        filters,
    } = usePage<{
        transactions: {
            data: Transaction[];
            links: { url: string | null; label: string; active: boolean }[];
            total: number;
            from: number;
            to: number;
            current_page: number;
            last_page: number;
            per_page: number;
        };
        summary: {
            total_income: number;
            total_expense: number;
            balance: number;
        };
        filters: {
            start_date?: string;
            end_date?: string;
            search?: string;
        };
    }>().props;

    const [searchTerm, setSearchTerm] = useState(filters?.search || '');
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

    const columnDefinitions: ColumnDefinition[] = [
        { id: 'select', label: 'Chọn' },
        { id: 'transaction_date', label: 'Ngày giao dịch' },
        { id: 'title', label: 'Tiêu đề' },
        { id: 'type', label: 'Loại' },
        { id: 'amount', label: 'Số tiền' },
        { id: 'description', label: 'Mô tả' },
        { id: 'files', label: 'File chi tiết' },
        { id: 'actions', label: 'Thao tác' },
    ];

    // ✅ Gọi lại API khi người dùng gõ vào ô tìm kiếm
    useEffect(() => {
        const timeout = setTimeout(() => {
            router.get(
                '/finance/transactions',
                {
                    search: searchTerm,
                    start_date: filters?.start_date || '',
                    end_date: filters?.end_date || '',
                },
                {
                    preserveState: true,
                    only: ['transactions', 'summary', 'filters'],
                },
            );
        }, 400); // debounce 0.4s

        return () => clearTimeout(timeout);
    }, [searchTerm]);


    const handleAddClick = () => router.visit('/finance/transactions/create');

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
                <AppHeaderAddButton
                    title="Giao dịch"
                    description="Quản lý các giao dịch tài chính trong hệ thống, bao gồm thu nhập và chi phí."
                    buttonLabel="Thêm giao dịch"
                    onButtonClick={handleAddClick}
                />

                <ErrorBoundary>
                    <AppDataTableTransaction
                        columns={createTransactionColumns({
                            onEdit: handleEdit,
                            onDelete: handleDelete,
                        })}
                        data={transactions.data}
                        searchTerm={searchTerm}
                        onSearchChange={setSearchTerm}
                        columnVisibility={columnVisibility}
                        onColumnVisibilityChange={setColumnVisibility}
                        columnDefinitions={columnDefinitions}
                        summary={summary}
                        filters={filters}
                        onDateFilterChange={(field, value) => {
                            router.get(
                                '/finance/transactions',
                                {
                                    ...filters,
                                    [field]: value,
                                    search: searchTerm,
                                },
                                {
                                    preserveState: true,
                                    only: ['transactions', 'summary', 'filters'],
                                },
                            );
                        }}
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

            {/* Xác nhận xoá */}
            <AlertDialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Xác nhận xóa bản ghi</AlertDialogTitle>
                        <AlertDialogDescription>
                            Bạn có chắc chắn muốn xóa bản ghi "{itemToDelete?.title}"?
                            Hành động này không thể hoàn tác.
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
