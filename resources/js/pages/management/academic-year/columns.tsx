'use client';

import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { AcademicYear } from '@/types/academic';
import { ColumnDef } from '@tanstack/react-table';
import { MoreHorizontal, SquarePen, Trash2 } from 'lucide-react';

interface AcademicYearColumnsProps {
    onEdit: (academicYear: AcademicYear) => void;
    onDelete: (academicYear: AcademicYear) => void;
}

export const createAcademicYearColumns = ({
    onEdit,
    onDelete,
}: AcademicYearColumnsProps): ColumnDef<AcademicYear>[] => [
    {
        id: 'select',
        header: ({ table }) => (
            
                <Checkbox
                    checked={
                        table.getIsAllPageRowsSelected() ||
                        (table.getIsSomePageRowsSelected() && 'indeterminate')
                    }
                    onCheckedChange={(value) =>
                        table.toggleAllPageRowsSelected(!!value)
                    }
                    aria-label="Select all"
                />

        ),
        cell: ({ row }) => (
            <Checkbox
                checked={row.getIsSelected()}
                onCheckedChange={(value) => row.toggleSelected(!!value)}
                aria-label="Select row"
            />
        ),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'name',
        header: () => <div className="text-center">Niên khóa</div>,
        enableSorting: true,
        cell: ({ row }) => (
            <div className="text-center">
                <div className="font-semibold">{row.getValue('name')}</div>
            </div>
        ),
    },
    {
        id: 'catechism_period',
        header: () => <div className="text-center">Giáo lý</div>,
        enableSorting: false,
        cell: ({ row }) => {
            const startDate = row.original.catechism_start_date;
            const endDate = row.original.catechism_end_date;
            const start = startDate
                ? new Date(startDate).toLocaleDateString('vi-VN')
                : 'Chưa có';
            const end = endDate
                ? new Date(endDate).toLocaleDateString('vi-VN')
                : 'Chưa có';

            return (
                <div className="text-center">
                    <div className="text-sm">
                        <div>
                            {start} - {end}
                        </div>
                    </div>
                </div>
            );
        },
    },
    {
        accessorKey: 'catechism_avg_score',
        header: () => <div className="text-center">Điểm giáo lý</div>,
        enableSorting: true,
        cell: ({ row }) => {
            const score = row.getValue('catechism_avg_score') as number;
            return (
                <div className="text-center">
                    <span className="font-bold text-blue-600">
                        {score ? `${score}` : '...'}
                    </span>/10
                </div>
            );
        },
    },
    {
        accessorKey: 'catechism_training_score',
        header: () => <div className="text-center">Điểm chuyên cần giáo lý</div>,
        enableSorting: true,
        cell: ({ row }) => {
            const score = row.getValue('catechism_training_score') as number;
            return (
                <div className="text-center">
                    <span className="font-bold text-blue-600">
                        {score ? `${score}` : '...'}
                    </span>/10
                </div>
            );
        },
    },
    {
        id: 'activity_period',
        header: () => <div className="text-center">Sinh hoạt</div>,
        enableSorting: false,
        cell: ({ row }) => {
            const startDate = row.original.activity_start_date;
            const endDate = row.original.activity_end_date;
            const start = startDate
                ? new Date(startDate).toLocaleDateString('vi-VN')
                : 'Chưa có';
            const end = endDate
                ? new Date(endDate).toLocaleDateString('vi-VN')
                : 'Chưa có';

            return (
                <div className="text-center">
                    <div className="text-sm">
                        <div>
                            {start} - {end}
                        </div>
                    </div>
                </div>
            );
        },
    },
    {
        accessorKey: 'activity_score',
        header: () => <div className="text-center">Điểm sinh hoạt</div>,
        enableSorting: true,
        cell: ({ row }) => {
            const score = row.getValue('activity_score') as number;
            return (
                <div className="text-center">
                    <span className="font-bold text-blue-600">
                        {score ? `${score}` : 'Chưa có'}
                    </span>
                </div>
            );
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const academicYear = row.original;

            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" className="h-8 w-8 p-0">
                            <span className="sr-only">Open menu</span>
                            <MoreHorizontal className="h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Thao tác</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem onClick={() => onEdit(academicYear)}>
                            <SquarePen className="mr-2 h-4 w-4" />
                            Chỉnh sửa
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            onClick={() => onDelete(academicYear)}
                            className="text-red-600"
                        >
                            <Trash2 className="mr-2 h-4 w-4" />
                            Xóa
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];
