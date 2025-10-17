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
import type { Role } from "@/types/academic";

interface RoleColumnsProps {
    onEdit: (role: Role) => void;
    onDelete: (role: Role) => void;
}

export const createRoleColumns = ({ onEdit, onDelete }: RoleColumnsProps): ColumnDef<Role>[] => [
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
        header: "Tên vai trò",
        enableSorting: true,
        cell: ({ row }) => (
            <div className="font-semibold">
                {row.getValue("name")}
            </div>
        ),
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
        accessorKey: "sub_roles",
        header: "Vai trò quản lý",
        enableSorting: false,
        cell: ({ row }) => {
            const subRoles = row.original.sub_roles || [];
            
            if (subRoles.length === 0) {
                return (
                    <div className="text-muted-foreground text-sm">
                        Không có
                    </div>
                );
            }

            return (
                <div className="flex flex-wrap gap-1">
                    {subRoles.map((subRole, index) => (
                        <span
                            key={subRole.id}
                            className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                        >
                            {subRole.name}
                        </span>
                    ))}
                </div>
            );
        },
    },
    {
        id: "actions",
        enableHiding: false,
        cell: ({ row }) => {
            const role = row.original;

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
                        <DropdownMenuItem onClick={() => onEdit(role)}>
                            <SquarePen className="mr-2 h-4 w-4" />
                            Chỉnh sửa
                        </DropdownMenuItem>
                        <DropdownMenuItem
                            onClick={() => onDelete(role)}
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
