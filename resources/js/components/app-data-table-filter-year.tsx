'use client';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { ColumnDef, OnChangeFn, VisibilityState } from '@tanstack/react-table';
import { ChevronDown, Filter } from 'lucide-react';
import * as React from 'react';
import { AppDataTable, PaginationInfo } from './app-data-table';

export interface ColumnDefinition {
    id: string;
    label: string;
}

export interface AcademicYear {
    id: number;
    name: string;
    status_academic?: string;
}

export interface AppDataTableFilterYearProps<TData, TValue> {
    // Data table props
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    pagination?: PaginationInfo;
    emptyMessage?: string;

    // Search props
    searchTerm: string;
    onSearchChange: (value: string) => void;
    searchPlaceholder?: string;

    // Academic year filter props
    academicYears: AcademicYear[];
    selectedAcademicYearId?: number;
    onAcademicYearChange: (academicYearId: number) => void;
    academicYearLabel?: string;
    academicYearPlaceholder?: string;

    // Column visibility props
    columnVisibility: Record<string, boolean>;
    onColumnVisibilityChange: OnChangeFn<VisibilityState>;
    columnDefinitions: ColumnDefinition[];

    // Optional props
    className?: string;
    showColumnSelector?: boolean;
    showSearch?: boolean;
    showAcademicYearFilter?: boolean;
}

export function AppDataTableFilterYear<TData, TValue>({
    columns,
    data,
    pagination,
    emptyMessage,
    searchTerm,
    onSearchChange,
    searchPlaceholder = 'Tìm kiếm...',
    academicYears,
    selectedAcademicYearId,
    onAcademicYearChange,
    academicYearLabel = 'Lọc theo niên khóa:',
    academicYearPlaceholder = 'Chọn niên khóa để lọc',
    columnVisibility,
    onColumnVisibilityChange,
    columnDefinitions,
    className = '',
    showColumnSelector = true,
    showSearch = true,
    showAcademicYearFilter = true,
}: AppDataTableFilterYearProps<TData, TValue>) {
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
                    <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                            {/* Search */}
                            {showSearch && (
                                <div className="flex items-center gap-2">
                                    <span className="text-sm whitespace-nowrap text-muted-foreground">
                                        Tìm kiếm:
                                    </span>
                                    <Input
                                        placeholder={searchPlaceholder}
                                        value={searchTerm}
                                        onChange={(e) =>
                                            onSearchChange(e.target.value)
                                        }
                                        className="max-w-sm"
                                    />
                                </div>
                            )}

                            {/* Academic Year Filter */}
                            {showAcademicYearFilter && (
                                <div className="flex items-center gap-2">
                                    <Label
                                        htmlFor="academic-year-filter"
                                        className="text-sm whitespace-nowrap text-muted-foreground"
                                    >
                                        {academicYearLabel}
                                    </Label>
                                    <Select
                                        value={
                                            selectedAcademicYearId?.toString() ??
                                            ''
                                        }
                                        onValueChange={(value) =>
                                            onAcademicYearChange(
                                                parseInt(value),
                                            )
                                        }
                                    >
                                        <SelectTrigger className="w-[250px]">
                                            <SelectValue
                                                placeholder={
                                                    academicYearPlaceholder
                                                }
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {academicYears.map((year) => (
                                                <SelectItem
                                                    key={year.id}
                                                    value={year.id.toString()}
                                                >
                                                    {year.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>
                            )}
                        </div>

                        {/* Column Selector */}
                        {showColumnSelector && (
                            <div className="flex items-center gap-2">
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
