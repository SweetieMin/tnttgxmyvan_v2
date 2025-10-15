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
import { SquarePen, Trash2, MoreHorizontal, Calendar, FileText } from "lucide-react";
import type { Transaction } from "@/types/academic";

interface TransactionColumnsProps {
    onEdit: (transaction: Transaction) => void;
    onDelete: (transaction: Transaction) => void;
}

export const createTransactionColumns = ({ onEdit, onDelete }: TransactionColumnsProps): ColumnDef<Transaction>[] => [
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
        accessorKey: "transaction_date",
        header: "Ngày giao dịch",
        enableSorting: true,
        cell: ({ row }) => (
            <div className="flex items-center gap-2">
                <Calendar className="h-4 w-4 text-muted-foreground" />
                <span className="font-medium">
                    {new Date(row.getValue("transaction_date")).toLocaleDateString('vi-VN')}
                </span>
            </div>
        ),
    },
    {
        accessorKey: "title",
        header: "Tiêu đề",
        enableSorting: true,
        cell: ({ row }) => (
            <div className="font-semibold">
                {row.getValue("title")}
            </div>
        ),
    },
    {
        accessorKey: "type",
        header: "Loại",
        enableSorting: true,
        cell: ({ row }) => {
            const type = row.getValue("type") as 'income' | 'expense';
            return (
                <div className={`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                    type === 'income' 
                        ? 'bg-green-100 text-green-800' 
                        : 'bg-red-100 text-red-800'
                }`}>
                    {type === 'income' ? 'Thu' : 'Chi'}
                </div>
            );
        },
    },
    {
        accessorKey: "amount",
        header: "Số tiền",
        enableSorting: true,
        cell: ({ row }) => {
            const amount = row.getValue("amount") as number;
            const type = row.original.type;
            return (
                <div className="flex items-center gap-2">
                    <span className={`font-semibold ${
                        type === 'income' ? 'text-green-600' : 'text-red-600'
                    }`}>
                        {new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(amount)}
                    </span>
                </div>
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
        accessorKey: "file_name",
        header: "Tài liệu",
        enableSorting: false,
        cell: ({ row }) => {
            const fileName = row.original.file_name;
            return fileName ? (
                <div className="flex items-center gap-1">
                    <FileText className="h-3 w-3 text-muted-foreground" />
                    <a 
                        href={`/storage/transactions/${fileName}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-xs text-blue-600 hover:text-blue-800 hover:underline"
                    >
                        Xem file
                    </a>
                </div>
            ) : (
                <span className="text-xs text-muted-foreground">Không có</span>
            );
        },
    },
    {
        id: "actions",
        enableHiding: false,
        cell: ({ row }) => {
            const transaction = row.original;

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
                        <DropdownMenuItem onClick={() => onEdit(transaction)}>
                            <SquarePen className="mr-2 h-4 w-4" />
                            Chỉnh sửa
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            onClick={() => onDelete(transaction)}
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
