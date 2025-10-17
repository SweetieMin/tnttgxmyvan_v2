import AppHeaderAddButton from '@/components/app-header-add-button';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { index as regulations } from '@/routes/management/regulations';
import { type BreadcrumbItem } from '@/types';
import type { AcademicYear, Regulation } from '@/types/academic';
import { Head, useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { createRegulationColumns } from './columns';
import ErrorBoundary from '@/components/error-boundary';
import { AppDataTablePage, ColumnDefinition } from '@/components/app-data-table-page';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Quản lý', href: '' },
    { title: 'Nội quy', href: regulations().url },
];

export interface Props {
    regulations?: {
        data: Regulation[];
        links: { url: string | null; label: string; active: boolean }[];
        total: number;
        from: number;
        to: number;
        current_page: number;
        last_page: number;
        per_page: number;
    };
    academicYears?: AcademicYear[];
    currentAcademicYearId?: number;
}

export default function RegulationIndex({ regulations = { data: [], links: [], total: 0, from: 0, to: 0, current_page: 1, last_page: 1, per_page: 10 }, academicYears = [], currentAcademicYearId }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const [editingRegulation, setEditingRegulation] = useState<Regulation | null>(null);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [itemToDelete, setItemToDelete] = useState<Regulation | null>(null);
    const [selectedAcademicYearId, setSelectedAcademicYearId] = useState<number | undefined>(
        currentAcademicYearId,
    );
    const [searchTerm, setSearchTerm] = useState('');
    const [columnVisibility, setColumnVisibility] = useState<Record<string, boolean>>({
        select: true,
        ordering: true,
        description: true,
        type: true,
        points: true,
        actions: true,
    });

    // Column definitions for visibility control
    const columnDefinitions: ColumnDefinition[] = [
        { id: 'select', label: 'Chọn' },
        { id: 'ordering', label: 'STT' },
        { id: 'description', label: 'Nội dung' },
        { id: 'type', label: 'Loại' },
        { id: 'points', label: 'Điểm' },
        { id: 'actions', label: 'Thao tác' },
    ];

    // Set default academic year if not provided
    useEffect(() => {
        if (!selectedAcademicYearId && academicYears.length > 0) {
            // Find current academic year (ongoing status)
            const currentYear = academicYears.find(year => year.status_academic === 'ongoing');
            if (currentYear) {
                setSelectedAcademicYearId(currentYear.id);
            } else if (academicYears.length > 0) {
                // Fallback to first academic year if no ongoing year found
                setSelectedAcademicYearId(academicYears[0].id);
            }
        }
    }, [academicYears, selectedAcademicYearId]);


    const { data, setData, post, put, delete: destroy, processing, errors, clearErrors } =
        useForm({
            id: 0,
            academic_year_id: 0,
            description: '',
            type: 'plus',
            points: 1,
            ordering: 1,
        });

    const resetForm = () => {
        setData({ id: 0, academic_year_id: 0, description: '', type: 'plus', points: 1, ordering: 1 });
        setEditingRegulation(null);
        clearErrors();
    };

    const handleAddClick = () => {
        resetForm();
        setIsOpen(true);
    };

    const handleEdit = (item: Regulation) => {
        setEditingRegulation(item);
        setData({
            id: item.id,
            academic_year_id: item.academic_year_id,
            description: item.description,
            type: item.type,
            points: item.points,
            ordering: item.ordering,
        });
        setIsOpen(true);
    };

    const handleDelete = (item: Regulation) => {
        setItemToDelete(item);
        setDeleteDialogOpen(true);
    };

    const confirmDelete = () => {
        if (itemToDelete) {
            destroy(`/management/regulations/${itemToDelete.id}`, {
                onSuccess: () => {
                    setDeleteDialogOpen(false);
                    setItemToDelete(null);
                },
            });
        }
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (editingRegulation) {
            put(`/management/regulations/${editingRegulation.id}`, {
                onSuccess: () => {
                    setIsOpen(false);
                    resetForm();
                },
            });
        } else {
            post('/management/regulations', {
                onSuccess: () => {
                    setIsOpen(false);
                    resetForm();
                },
            });
        }
    };

    const handleClose = () => { setIsOpen(false); resetForm(); };


    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Nội quy" />

            <div className="px-4 py-6">
                {/* Header + Button */}
                <AppHeaderAddButton
                    title="Nội quy"
                    description="Quản lý các nội quy trong hệ thống, bao gồm quy định cộng điểm và trừ điểm."
                    buttonLabel="Thêm nội quy"
                    onButtonClick={handleAddClick}
                />

                {/* Data Table Page */}
                <ErrorBoundary>
                    <AppDataTablePage
                        columns={createRegulationColumns({
                            onEdit: handleEdit,
                            onDelete: handleDelete,
                        })}
                        data={regulations.data.filter(regulation => 
                            regulation.description.toLowerCase().includes(searchTerm.toLowerCase())
                        )}
                        searchTerm={searchTerm}
                        onSearchChange={setSearchTerm}
                        searchPlaceholder="Tìm kiếm nội quy..."
                        columnVisibility={columnVisibility}
                        onColumnVisibilityChange={(updaterOrValue) => {
                            if (typeof updaterOrValue === 'function') {
                                setColumnVisibility(updaterOrValue);
                            } else {
                                setColumnVisibility(updaterOrValue);
                            }
                        }}
                        columnDefinitions={columnDefinitions}
                        pagination={{
                            current_page: regulations.current_page,
                            last_page: regulations.last_page,
                            per_page: regulations.per_page,
                            total: regulations.total,
                            from: regulations.from,
                            to: regulations.to,
                            links: regulations.links,
                        }}
                    />
                </ErrorBoundary>

                {/* Regulation Dialog */}
                <Dialog open={isOpen} onOpenChange={setIsOpen}>
                    <DialogContent className="max-h-[90vh] overflow-y-auto">
                        <DialogHeader>
                            <DialogTitle>
                                {editingRegulation
                                    ? 'Chỉnh sửa nội quy'
                                    : 'Thêm nội quy mới'}
                            </DialogTitle>
                            <DialogDescription>
                                {editingRegulation
                                    ? 'Cập nhật thông tin nội quy hiện tại'
                                    : 'Nhập thông tin để tạo nội quy mới'}
                            </DialogDescription>
                        </DialogHeader>
                        <Separator />
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="academic_year_id">
                                        Niên khóa *
                                    </Label>
                                    <Select
                                        value={data.academic_year_id?.toString() ?? ''}
                                        onValueChange={(value) =>
                                            setData('academic_year_id', parseInt(value))
                                        }
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Chọn niên khóa" />
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
                                    {errors.academic_year_id && (
                                        <p className="text-sm text-red-500">
                                            {errors.academic_year_id}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="type">
                                        Loại nội quy *
                                    </Label>
                                    <Select
                                        value={data.type}
                                        onValueChange={(value) =>
                                            setData('type', value as 'plus' | 'minus')
                                        }
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Chọn loại" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="plus">Cộng điểm</SelectItem>
                                            <SelectItem value="minus">Trừ điểm</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    {errors.type && (
                                        <p className="text-sm text-red-500">
                                            {errors.type}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="points">
                                        Điểm số *
                                    </Label>
                                    <Input
                                        id="points"
                                        type="number"
                                        min="1"
                                        max="100"
                                        value={data.points}
                                        onChange={(e) =>
                                            setData('points', parseInt(e.target.value) || 1)
                                        }
                                        placeholder="1"
                                    />
                                    {errors.points && (
                                        <p className="text-sm text-red-500">
                                            {errors.points}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="ordering">
                                        Thứ tự *
                                    </Label>
                                    <Input
                                        id="ordering"
                                        type="number"
                                        min="1"
                                        value={data.ordering}
                                        onChange={(e) =>
                                            setData('ordering', parseInt(e.target.value) || 1)
                                        }
                                        placeholder="1"
                                    />
                                    {errors.ordering && (
                                        <p className="text-sm text-red-500">
                                            {errors.ordering}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="description">Nội dung nội quy *</Label>
                                <textarea
                                    id="description"
                                    className="flex min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    value={data.description}
                                    onChange={(e) =>
                                        setData('description', e.target.value)
                                    }
                                    placeholder="Nhập nội dung nội quy..."
                                />
                                {errors.description && (
                                    <p className="text-sm text-red-500">
                                        {errors.description}
                                    </p>
                                )}
                            </div>

                            <Separator />
                            <DialogFooter>
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={handleClose}
                                >
                                    Hủy
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing
                                        ? 'Đang xử lý...'
                                        : editingRegulation
                                          ? 'Cập nhật'
                                          : 'Tạo mới'}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>

                {/* Delete Confirmation Dialog */}
                <AlertDialog
                    open={deleteDialogOpen}
                    onOpenChange={setDeleteDialogOpen}
                >
                    <AlertDialogContent>
                        <AlertDialogHeader>
                            <AlertDialogTitle>
                                Xác nhận xóa nội quy
                            </AlertDialogTitle>
                            <AlertDialogDescription>
                                Bạn có chắc chắn muốn xóa nội quy này? Hành động này không thể
                                hoàn tác và sẽ xóa vĩnh viễn nội quy khỏi hệ thống.
                            </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                            <AlertDialogCancel>Hủy</AlertDialogCancel>
                            <AlertDialogAction
                                onClick={confirmDelete}
                                className="bg-red-600 hover:bg-red-700"
                            >
                                Xóa
                            </AlertDialogAction>
                        </AlertDialogFooter>
                    </AlertDialogContent>
                </AlertDialog>
            </div>
        </AppLayout>
    );
}
