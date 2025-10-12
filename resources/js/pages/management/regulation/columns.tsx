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
import type { Regulation } from "@/types/academic";

interface RegulationColumnsProps {
    onEdit: (regulation: Regulation) => void;
    onDelete: (regulation: Regulation) => void;
}

export const createRegulationColumns = ({ onEdit, onDelete }: RegulationColumnsProps): ColumnDef<Regulation>[] => [
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
        header: "STT",
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
        accessorKey: "description",
        header: "Nội dung",
        enableSorting: false,
        cell: ({ row }) => (
            <div className="text-sm">
                <span className="line-clamp-3">
                    {row.getValue("description")}
                </span>
            </div>
        ),
    },
    {
        accessorKey: "type",
        header: "Loại",
        enableSorting: true,
        cell: ({ row }) => {
            const type = row.getValue("type") as string;
            return (
                <div className="text-center">
                    <span className={`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                        type === 'plus' 
                            ? 'bg-green-100 text-green-800' 
                            : 'bg-red-100 text-red-800'
                    }`}>
                        {type === 'plus' ? 'Cộng điểm' : 'Trừ điểm'}
                    </span>
                </div>
            );
        },
    },
    {
        accessorKey: "points",
        header: "Điểm",
        enableSorting: true,
        cell: ({ row }) => {
            const points = row.getValue("points") as number;
            const type = row.original.type;
            return (
                <div className="text-center">
                    <span className={`font-semibold ${
                        type === 'plus' ? 'text-green-600' : 'text-red-600'
                    }`}>
                        {type === 'plus' ? '+' : '-'}{points}
                    </span>
                </div>
            );
        },
    },
    {
        id: "actions",
        enableHiding: false,
        cell: ({ row }) => {
            const regulation = row.original;

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
                        <DropdownMenuItem onClick={() => onEdit(regulation)}>
                            <SquarePen className="mr-2 h-4 w-4" />
                            Chỉnh sửa
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            onClick={() => onDelete(regulation)}
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
