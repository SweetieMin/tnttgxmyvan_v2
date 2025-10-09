'use client';

import AppCardEmpty from '@/components/app-card-empty';
import { Card } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Loader2 } from 'lucide-react';
import React from 'react';

interface Column<T> {
    title: string;
    className?: string;
    render?: (item: T, index: number) => React.ReactNode;
}

interface AppTableProps<T> {
    columns: Column<T>[];
    data: T[];
    isLoading?: boolean;
    renderActions?: (item: T, index: number) => React.ReactNode;
    renderCard?: (item: T, index: number) => React.ReactNode;
    emptyMessage?: string;
    emptyHint?: string;
}

/**
 * 🧩 AppTable — bảng responsive với cột cấu hình linh hoạt
 * Hỗ trợ:
 * - Table + Card view responsive
 * - Loading state
 * - Empty state
 * - Custom render cho từng cột
 */
export function AppTable<T>({
    columns,
    data,
    isLoading = false,
    renderActions,
    renderCard,
    emptyMessage = 'Không có dữ liệu',
    emptyHint = 'Hãy thêm mới dữ liệu để bắt đầu quản lý.',
}: AppTableProps<T>) {
    // 🌀 Loading
    if (isLoading) {
        return (
            <div className="flex items-center justify-center py-10 text-muted-foreground">
                <Loader2 className="mr-2 h-5 w-5 animate-spin" />
                Đang tải dữ liệu...
            </div>
        );
    }

    // 📭 Empty state
    if (!data || data.length === 0) {
        return <AppCardEmpty title={emptyMessage} message={emptyHint} />;
    }

    // 📊 Table
    return (
        <>
            {/* 🖥 Desktop Table */}
            <div className="hidden overflow-x-auto rounded-xl border border-border shadow-sm md:block">
                <Table className="min-w-full bg-background text-sm">
                    <TableHeader>
                        <TableRow className="bg-muted/30">
                            {columns.map((col, index) => (
                                <TableHead
                                    key={index}
                                    className={`px-6 py-4 font-semibold text-muted-foreground ${col.className ?? ''}`}
                                >
                                    {col.title}
                                </TableHead>
                            ))}
                            {renderActions && (
                                <TableHead className="px-6 py-4 text-center font-semibold text-muted-foreground">
                                    Hành động
                                </TableHead>
                            )}
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        {data.map((item, rowIndex) => (
                            <TableRow
                                key={rowIndex}
                                className="transition-colors hover:bg-muted/20"
                            >
                                {columns.map((col, colIndex) => (
                                    <TableCell
                                        key={colIndex}
                                        className={`px-6 py-4 align-middle ${col.className ?? ''}`}
                                    >
                                        {col.render
                                            ? col.render(item, rowIndex)
                                            : String(
                                                  (item as any)[
                                                      col.title as keyof T
                                                  ],
                                              )}
                                    </TableCell>
                                ))}
                                {renderActions && (
                                    <TableCell className="px-6 py-4 text-center">
                                        {renderActions(item, rowIndex)}
                                    </TableCell>
                                )}
                            </TableRow>
                        ))}
                    </TableBody>
                </Table>
            </div>

            {/* 📱 Mobile Card */}
            <div className="grid gap-4 md:hidden">
                {data.map((item, index) => (
                    <Card
                        key={index}
                        className="border border-border bg-background p-4 shadow-sm"
                    >
                        {renderCard ? (
                            renderCard(item, index)
                        ) : (
                            <div className="space-y-1 text-sm">
                                {columns.map((col, i) => (
                                    <div key={i}>
                                        <span className="font-medium">
                                            {col.title}:
                                        </span>{' '}
                                        <span className="text-muted-foreground">
                                            {col.render
                                                ? col.render(item, index)
                                                : String(
                                                      (item as any)[
                                                          col.title as keyof T
                                                      ],
                                                  )}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        )}
                    </Card>
                ))}
            </div>
        </>
    );
}
