"use client";

import * as React from "react";
import { ColumnDef, VisibilityState, OnChangeFn } from "@tanstack/react-table";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Filter, ChevronDown } from "lucide-react";
import { AppDataTable, PaginationInfo } from "./app-data-table";

export interface ColumnDefinition {
    id: string;
    label: string;
}

export interface AppDataTablePageProps<TData, TValue> {
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
    
    // Optional props
    className?: string;
    showColumnSelector?: boolean;
    showSearch?: boolean;
}

export function AppDataTablePage<TData, TValue>({
    columns,
    data,
    pagination,
    emptyMessage,
    searchTerm,
    onSearchChange,
    searchPlaceholder = "Tìm kiếm...",
    columnVisibility,
    onColumnVisibilityChange,
    columnDefinitions,
    className = "",
    showColumnSelector = true,
    showSearch = true,
}: AppDataTablePageProps<TData, TValue>) {
    const [showColumnSelect, setShowColumnSelect] = React.useState(false);

    // Helper functions for select all/none
    const handleSelectAll = () => {
        const allVisible = columnDefinitions.reduce((acc, col) => {
            acc[col.id] = true;
            return acc;
        }, {} as Record<string, boolean>);
        onColumnVisibilityChange(allVisible);
    };

    const handleSelectNone = () => {
        const allHidden = columnDefinitions.reduce((acc, col) => {
            acc[col.id] = false;
            return acc;
        }, {} as Record<string, boolean>);
        onColumnVisibilityChange(allHidden);
    };

    // Close column select when clicking outside
    React.useEffect(() => {
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

    return (
        <div className={`space-y-6 ${className}`}>
            {/* Filter Section */}
            <div className="rounded-lg border bg-card p-4">
                <div className="flex flex-col gap-4">
                    <div className="flex items-center gap-2">
                        <Filter className="h-4 w-4 text-muted-foreground" />
                        <span className="text-sm font-medium">Bộ lọc và tìm kiếm</span>
                    </div>
                    <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        {showSearch && (
                            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                                <div className="flex items-center gap-2">
                                    <span className="text-sm text-muted-foreground">Tìm kiếm:</span>
                                    <Input
                                        placeholder={searchPlaceholder}
                                        value={searchTerm}
                                        onChange={(e) => onSearchChange(e.target.value)}
                                        className="max-w-sm"
                                    />
                                </div>
                            </div>
                        )}
                        {showColumnSelector && (
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
                                                                onColumnVisibilityChange(prev => ({
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
                        )}
                    </div>
                </div>
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
