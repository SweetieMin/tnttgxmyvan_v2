"use client";

import * as React from "react";
import {
    ColumnDef,
    ColumnFiltersState,
    flexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    SortingState,
    useReactTable,
    VisibilityState,
    OnChangeFn,
} from "@tanstack/react-table";
import { router } from "@inertiajs/react";

import { Button } from "@/components/ui/button";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";

interface DataTableProps<TData, TValue> {
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    searchKey?: string;
    searchPlaceholder?: string;
    columnVisibility?: Record<string, boolean>;
    onColumnVisibilityChange?: OnChangeFn<VisibilityState>;
    pagination?: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number;
        to: number;
        links: { url: string | null; label: string; active: boolean }[];
    };
}

export function DataTable<TData, TValue>({
    columns,
    data,
    searchKey,
    searchPlaceholder = "Tìm kiếm...",
    columnVisibility: externalColumnVisibility,
    onColumnVisibilityChange,
    pagination,
}: DataTableProps<TData, TValue>) {
    const [sorting, setSorting] = React.useState<SortingState>([]);
    const [columnFilters, setColumnFilters] = React.useState<ColumnFiltersState>([]);
    const [internalColumnVisibility, setInternalColumnVisibility] = React.useState<VisibilityState>({});
    const [rowSelection, setRowSelection] = React.useState({});

    // Use external column visibility if provided, otherwise use internal state
    const columnVisibility = externalColumnVisibility || internalColumnVisibility;
    const setColumnVisibility = onColumnVisibilityChange || setInternalColumnVisibility;

    const table = useReactTable({
        data,
        columns,
        onSortingChange: setSorting,
        onColumnFiltersChange: setColumnFilters,
        getCoreRowModel: getCoreRowModel(),
        getSortedRowModel: getSortedRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
        onColumnVisibilityChange: setColumnVisibility,
        onRowSelectionChange: setRowSelection,
        state: {
            sorting,
            columnFilters,
            columnVisibility,
            rowSelection,
        },
        // Disable client-side pagination since we're using server-side
        manualPagination: true,
    });

    const handlePageChange = (page: number) => {
        if (pagination && page >= 1 && page <= pagination.last_page) {
            // Get current URL parameters to preserve filters
            const urlParams = new URLSearchParams(window.location.search);
            const params: Record<string, string> = { page: page.toString() };
            
            // Preserve academic_year_id if it exists
            if (urlParams.has('academic_year_id')) {
                params.academic_year_id = urlParams.get('academic_year_id')!;
            }
            
            router.get(window.location.pathname, params, { preserveState: true });
        }
    };

    return (
        <div className="w-full">
            <div className="overflow-hidden rounded-md border">
                <Table>
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id}>
                                {headerGroup.headers.map((header) => {
                                    return (
                                        <TableHead key={header.id}>
                                            {header.isPlaceholder
                                                ? null
                                                : flexRender(
                                                      header.column.columnDef.header,
                                                      header.getContext()
                                                  )}
                                        </TableHead>
                                    );
                                })}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows?.length ? (
                            table.getRowModel().rows.map((row) => (
                                <TableRow
                                    key={row.id}
                                    data-state={row.getIsSelected() && "selected"}
                                >
                                    {row.getVisibleCells().map((cell) => (
                                        <TableCell key={cell.id}>
                                            {flexRender(
                                                cell.column.columnDef.cell,
                                                cell.getContext()
                                            )}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                        ) : (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="h-24 text-center"
                                >
                                    Không có dữ liệu
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
            {pagination && (
                <div className="flex items-center justify-between space-x-2 py-4">
                    <div className="text-muted-foreground text-sm">
                        Hiển thị {pagination.from} đến {pagination.to} trong tổng số {pagination.total} kết quả
                    </div>
                    <div className="flex items-center space-x-2">
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={() => handlePageChange(pagination.current_page - 1)}
                            disabled={pagination.current_page <= 1}
                        >
                            Trước
                        </Button>
                        <div className="flex items-center space-x-1">
                            {pagination.links.map((link, index) => {
                                if (link.label === '...') {
                                    return (
                                        <span key={index} className="px-2 py-1 text-sm text-muted-foreground">
                                            ...
                                        </span>
                                    );
                                }
                                
                                const pageNumber = parseInt(link.label);
                                if (isNaN(pageNumber)) return null;
                                const isActive = link.active || pageNumber === pagination.current_page;
                                return (
                                    <Button
                                        key={index}
                                        variant={link.active ? "default" : "outline"}
                                        size="sm"
                                        onClick={() => handlePageChange(pageNumber)}
                                        className="min-w-[32px]"
                                        disabled={isActive} 
                                    >
                                        {link.label}
                                    </Button>
                                );
                            })}
                        </div>
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={() => handlePageChange(pagination.current_page + 1)}
                            disabled={pagination.current_page >= pagination.last_page}
                        >
                            Sau
                        </Button>
                    </div>
                </div>
            )}
        </div>
    );
}
