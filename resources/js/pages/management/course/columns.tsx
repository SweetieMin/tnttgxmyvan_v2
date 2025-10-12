"use client";

import { ColumnDef } from "@tanstack/react-table";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { SquarePen, Trash2, MoreHorizontal } from "lucide-react";
import type { Course } from "@/types/academic";

interface CourseColumnsProps {
    onEdit: (course: Course) => void;
    onDelete: (course: Course) => void;
}

export const createCourseColumns = ({ onEdit, onDelete }: CourseColumnsProps): ColumnDef<Course>[] => [
    {
        id: "select",
        header: ({ table }) => (
            <Checkbox
                checked={
                    table.getIsAllPageRowsSelected() ||
                    (table.getIsSomePageRowsSelected() && "indeterminate")
                }
                onCheckedChange={(value) => table.toggleAllPageRowsSelected(!!value)}
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
        accessorKey: "ordering",
        header: "Thứ tự",
        enableSorting: true,
        cell: ({ row }) => (
            <div className="text-center w-12">
                <span className="font-semibold text-primary">
                    {row.getValue("ordering")}
                </span>
            </div>
        ),
    },
    {
        accessorKey: "name",
        header: "Tên lớp giáo lý",
        enableSorting: true,
        cell: ({ row }) => (
            <div className="font-semibold">
                {row.getValue("name")}
            </div>
        ),
    },
    {
        accessorKey: "academic_year",
        header: "Niên khóa",
        enableSorting: true,
        sortingFn: (rowA, rowB) => {
            const yearA = rowA.original.academic_year?.name || '';
            const yearB = rowB.original.academic_year?.name || '';
            return yearA.localeCompare(yearB);
        },
        cell: ({ row }) => {
            const academicYear = row.original.academic_year;
            return (
                <span className="text-sm">
                    {academicYear?.name || 'Chưa chọn'}
                </span>
            );
        },
    },
    {
        accessorKey: "description",
        header: "Mô tả",
        enableSorting: false,
        cell: ({ row }) => (
            <div className="text-muted-foreground">
                <span className="line-clamp-2">
                    {row.getValue("description") || 'Không có mô tả'}
                </span>
            </div>
        ),
    },
    {
        id: "actions",
        enableHiding: false,
        cell: ({ row }) => {
            const course = row.original;

            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" className="h-8 w-8 p-0">
                            <span className="sr-only">Open menu</span>
                            <MoreHorizontal />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Thao tác</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem onClick={() => onEdit(course)}>
                            <SquarePen className="h-4 w-4 mr-2" />
                            Chỉnh sửa
                        </DropdownMenuItem>
                        <DropdownMenuItem 
                            onClick={() => onDelete(course)}
                            className="text-destructive"
                        >
                            <Trash2 className="h-4 w-4 mr-2" />
                            Xóa
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];
