'use client';

import { AppDatePicker } from '@/components/app-date-picker';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { router } from '@inertiajs/react';
import { ColumnDef, OnChangeFn, VisibilityState } from '@tanstack/react-table';
import { ChevronDown, Filter } from 'lucide-react';
import * as React from 'react';
import { AppDataTable, PaginationInfo } from './app-data-table';

export interface ColumnDefinition {
    id: string;
    label: string;
}

export interface AppDataTableTransactionProps<TData, TValue> {
    // Data table props
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    pagination?: PaginationInfo;
    emptyMessage?: string;

    // Search props
    searchTerm: string;
    onSearchChange: (value: string) => void;
    searchPlaceholder?: string;

    // Column visibility props
    columnVisibility: Record<string, boolean>;
    onColumnVisibilityChange: OnChangeFn<VisibilityState>;
    columnDefinitions: ColumnDefinition[];

    // Summary props for transaction
    summary: {
        total_income: number;
        total_expense: number;
        balance: number;
    };

    filters?: {
        start_date?: string;
        end_date?: string;
    };
    onDateFilterChange?: (
        field: 'start_date' | 'end_date',
        value: string,
    ) => void;

    // Optional props
    className?: string;
    showColumnSelector?: boolean;
    showSearch?: boolean;
}

// Helper function to format currency
const formatCurrency = (amount: number): string => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
    }).format(amount);
};

export function AppDataTableTransaction<TData, TValue>({
    columns,
    data,
    pagination,
    emptyMessage,
    searchTerm,
    onSearchChange,
    searchPlaceholder = 'Tìm kiếm...',
    columnVisibility,
    onColumnVisibilityChange,
    columnDefinitions,
    summary,
    filters, // 👈 thêm
    onDateFilterChange, // 👈 thêm
    className = '',
    showColumnSelector = true,
    showSearch = true,
}: AppDataTableTransactionProps<TData, TValue>) {
    const [showColumnSelect, setShowColumnSelect] = React.useState(false);

    // Helper functions for select all/none
    const handleSelectAll = () => {
        const allVisible = columnDefinitions.reduce(
            (acc, col) => {
                acc[col.id] = true;
                return acc;
            },
            {} as Record<string, boolean>,
        );
        onColumnVisibilityChange(allVisible);
    };

    const handleSelectNone = () => {
        const allHidden = columnDefinitions.reduce(
            (acc, col) => {
                acc[col.id] = false;
                return acc;
            },
            {} as Record<string, boolean>,
        );
        onColumnVisibilityChange(allHidden);
    };

    // Hàm reset toàn bộ bộ lọc
    const handleResetFilters = () => {
        // Reset ô tìm kiếm (xoá nội dung input)
        onSearchChange('');

        // Reset các filter ngày về rỗng
        onDateFilterChange?.('start_date', '');
        onDateFilterChange?.('end_date', '');

        // Gọi lại dữ liệu gốc từ backend
        router.get(
            '/finance/transactions',
            {},
            {
                preserveState: true,
                only: ['transactions', 'summary', 'filters'],
            },
        );
    };

    // Close column select when clicking outside
    React.useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            const target = event.target as Element;
            if (
                showColumnSelect &&
                !target.closest('.column-select-container')
            ) {
                setShowColumnSelect(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [showColumnSelect]);

    return (
        <div className={`space-y-6 ${className}`}>
            {/* Filter Section */}
            <div className="rounded-lg border bg-card p-4">
                <div className="flex flex-col gap-4">
                    <div className="flex items-center gap-2">
                        <Filter className="h-4 w-4 text-muted-foreground" />
                        <span className="text-sm font-medium">
                            Bộ lọc và tìm kiếm
                        </span>
                    </div>
                    <div className="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        {/* Bộ lọc bên trái */}
                        <div className="flex flex-col gap-4 sm:flex-row sm:items-end">
                            {/* Tìm kiếm */}
                            {showSearch && (
                                <div className="flex flex-col gap-2">
                                    <span className="text-sm text-muted-foreground">
                                        Tìm kiếm
                                    </span>
                                    <Input
                                        placeholder={searchPlaceholder}
                                        value={searchTerm}
                                        onChange={(e) =>
                                            onSearchChange(e.target.value)
                                        }
                                        className="w-48"
                                    />
                                </div>
                            )}

                            {/* Bộ lọc theo ngày */}
                            <AppDatePicker
                                label="Từ ngày"
                                date={
                                    filters?.start_date
                                        ? new Date(filters.start_date)
                                        : undefined
                                }
                                onDateChange={(date) =>
                                    onDateFilterChange?.(
                                        'start_date',
                                        date
                                            ? date.toISOString().split('T')[0]
                                            : '',
                                    )
                                }
                            />

                            <AppDatePicker
                                label="Đến ngày"
                                date={
                                    filters?.end_date
                                        ? new Date(filters.end_date)
                                        : undefined
                                }
                                onDateChange={(date) =>
                                    onDateFilterChange?.(
                                        'end_date',
                                        date
                                            ? date.toISOString().split('T')[0]
                                            : '',
                                    )
                                }
                            />

                            {/* Nút đặt lại */}
                            <Button
                                variant="outline"
                                onClick={handleResetFilters}
                            >
                                Đặt lại
                            </Button>
                        </div>

                        {/* Selector bên phải giữ nguyên */}
                        {showColumnSelector && (
                            <div className="flex items-center gap-2 sm:self-end">
                                <span className="text-sm text-muted-foreground">
                                    Cột hiển thị:
                                </span>
                                <div className="column-select-container relative">
                                    <Button
                                        variant="outline"
                                        onClick={() =>
                                            setShowColumnSelect(
                                                !showColumnSelect,
                                            )
                                        }
                                        className="min-w-[120px] justify-between"
                                    >
                                        {
                                            Object.values(
                                                columnVisibility,
                                            ).filter(Boolean).length
                                        }{' '}
                                        cột
                                        <ChevronDown className="ml-2 h-4 w-4" />
                                    </Button>
                                    {showColumnSelect && (
                                        <div className="absolute top-full right-0 z-50 mt-1 w-64 rounded-md border bg-white p-2 shadow-lg">
                                            <div className="mb-2 flex items-center justify-between">
                                                <span className="text-sm font-medium">
                                                    Chọn cột hiển thị
                                                </span>
                                                <div className="flex gap-1">
                                                    <Button
                                                        size="sm"
                                                        variant="ghost"
                                                        onClick={
                                                            handleSelectAll
                                                        }
                                                        className="h-6 px-2 text-xs"
                                                    >
                                                        Tất cả
                                                    </Button>
                                                    <Button
                                                        size="sm"
                                                        variant="ghost"
                                                        onClick={
                                                            handleSelectNone
                                                        }
                                                        className="h-6 px-2 text-xs"
                                                    >
                                                        Không
                                                    </Button>
                                                </div>
                                            </div>
                                            <div className="max-h-48 space-y-1 overflow-y-auto">
                                                {columnDefinitions.map(
                                                    (column) => (
                                                        <label
                                                            key={column.id}
                                                            className="flex cursor-pointer items-center space-x-2 rounded p-2 hover:bg-gray-50"
                                                        >
                                                            <input
                                                                type="checkbox"
                                                                checked={
                                                                    columnVisibility[
                                                                        column
                                                                            .id
                                                                    ]
                                                                }
                                                                onChange={(e) =>
                                                                    onColumnVisibilityChange(
                                                                        (
                                                                            prev,
                                                                        ) => ({
                                                                            ...prev,
                                                                            [column.id]:
                                                                                e
                                                                                    .target
                                                                                    .checked,
                                                                        }),
                                                                    )
                                                                }
                                                                className="rounded"
                                                            />
                                                            <span className="text-sm">
                                                                {column.label}
                                                            </span>
                                                        </label>
                                                    ),
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            <div className="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-green-600">
                            Tổng thu
                        </CardTitle>
                        <CardDescription>Tổng tiền thu</CardDescription>
                    </CardHeader>
                    <CardContent className="text-2xl font-semibold text-green-600">
                        {formatCurrency(summary.total_income)}
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-red-600">Tổng chi</CardTitle>
                        <CardDescription>Tổng tiền chi</CardDescription>
                    </CardHeader>
                    <CardContent className="text-2xl font-semibold text-red-600">
                        {formatCurrency(summary.total_expense)}
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-blue-600">
                            Số dư hiện tại
                        </CardTitle>
                        <CardDescription>Tổng thu - tổng chi</CardDescription>
                    </CardHeader>
                    <CardContent className="text-2xl font-semibold text-blue-600">
                        {formatCurrency(summary.balance)}
                    </CardContent>
                </Card>
            </div>

            {/* Data Table */}
            <AppDataTable
                columns={columns}
                data={data}
                columnVisibility={columnVisibility}
                onColumnVisibilityChange={onColumnVisibilityChange}
                pagination={pagination}
                emptyMessage={emptyMessage}
            />
        </div>
    );
}
