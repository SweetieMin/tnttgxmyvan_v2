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
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
    InputGroupText,
} from '@/components/ui/input-group';
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
import { index as sectors } from '@/routes/management/sectors';
import { type BreadcrumbItem } from '@/types';
import type { AcademicYear, Sector } from '@/types/academic';
import { Head, useForm, usePage, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { createSectorColumns } from './columns';
import ErrorBoundary from '@/components/error-boundary';
import { AppDataTablePage, ColumnDefinition } from '@/components/app-data-table-page';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Quản lý', href: '' },
    { title: 'Ngành sinh hoạt', href: sectors().url },
];

export interface Props {
    sectors: {
        data: Sector[];
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
        total: number;
        from: number;
        to: number;
        current_page: number;
        last_page: number;
        per_page: number;
    };
    academicYears: AcademicYear[];
    currentAcademicYearId?: number;
}

export default function SectorIndex({ sectors, academicYears, currentAcademicYearId }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const [editingSector, setEditingSector] = useState<Sector | null>(null);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [itemToDelete, setItemToDelete] = useState<Sector | null>(null);
    const [selectedAcademicYearId, setSelectedAcademicYearId] = useState<number | undefined>(currentAcademicYearId);
    const [searchTerm, setSearchTerm] = useState('');
    const [columnVisibility, setColumnVisibility] = useState<Record<string, boolean>>({
        select: true,
        ordering: true,
        name: true,
        academic_year: true,
        description: true,
        actions: true,
    });

    // Column definitions for visibility control
    const columnDefinitions: ColumnDefinition[] = [
        { id: 'select', label: 'Chọn' },
        { id: 'ordering', label: 'Thứ tự' },
        { id: 'name', label: 'Tên ngành sinh hoạt' },
        { id: 'academic_year', label: 'Niên khóa' },
        { id: 'description', label: 'Mô tả' },
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


    const {
        data,
        setData,
        post,
        put,
        delete: destroy,
        processing,
        errors,
        reset,
        clearErrors,
    } = useForm({
        id: 0,
        academic_year_id: 0,
        name: '',
        description: '',
    });

    const resetForm = () => {
        setData({
            id: 0,
            academic_year_id: 0,
            name: '',
            description: '',
        });
        setEditingSector(null);
        clearErrors();
    };

    const handleAddClick = () => {
        resetForm();
        setIsOpen(true);
    };

    const handleEdit = (item: Sector) => {
        setEditingSector(item);
        setData({
            id: item.id,
            academic_year_id: item.academic_year_id,
            name: item.name,
            description: item.description || '',
        });
        setIsOpen(true);
    };

    const handleDelete = (item: Sector) => {
        setItemToDelete(item);
        setDeleteDialogOpen(true);
    };

    const confirmDelete = () => {
        if (itemToDelete) {
            destroy(`/management/sectors/${itemToDelete.id}`, {
                onSuccess: () => {
                    setDeleteDialogOpen(false);
                    setItemToDelete(null);
                },
            });
        }
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (editingSector) {
            put(`/management/sectors/${editingSector.id}`, {
                onSuccess: () => {
                    setIsOpen(false);
                    resetForm();
                },
            });
        } else {
            post('/management/sectors', {
                onSuccess: () => {
                    setIsOpen(false);
                    resetForm();
                },
            });
        }
    };

    const handleClose = () => {
        setIsOpen(false);
        resetForm();
    };

    const handleAcademicYearFilter = (academicYearId: string) => {
        const yearId = parseInt(academicYearId);
        setSelectedAcademicYearId(yearId);
        
        router.get('/management/sectors', 
            { academic_year_id: yearId },
            { preserveState: true, replace: true }
        );
    };


    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Ngành sinh hoạt" />

            <div className="px-4 py-6">
                {/* Header + Button */}
                <AppHeaderAddButton
                    title="Ngành sinh hoạt"
                    description="Quản lý các ngành sinh hoạt, bao gồm thông tin tên ngành và mô tả chi tiết."
                    buttonLabel="Thêm ngành sinh hoạt"
                    onButtonClick={handleAddClick}
                />

                {/* Data Table Page */}
                <ErrorBoundary>
                    <AppDataTablePage
                        columns={createSectorColumns({
                            onEdit: handleEdit,
                            onDelete: handleDelete,
                        })}
                        data={sectors.data.filter(sector => 
                            sector.name.toLowerCase().includes(searchTerm.toLowerCase())
                        )}
                        searchTerm={searchTerm}
                        onSearchChange={setSearchTerm}
                        searchPlaceholder="Tìm kiếm ngành sinh hoạt..."
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
                            current_page: sectors.current_page,
                            last_page: sectors.last_page,
                            per_page: sectors.per_page,
                            total: sectors.total,
                            from: sectors.from,
                            to: sectors.to,
                            links: sectors.links,
                        }}
                    />
                </ErrorBoundary>

                {/* Sector Dialog */}
                <Dialog open={isOpen} onOpenChange={setIsOpen}>
                    <DialogContent className="max-h-[90vh] overflow-y-auto">
                        <DialogHeader>
                            <DialogTitle>
                                {editingSector
                                    ? 'Chỉnh sửa ngành sinh hoạt'
                                    : 'Thêm ngành sinh hoạt mới'}
                            </DialogTitle>
                            <DialogDescription>
                                {editingSector
                                    ? 'Cập nhật thông tin ngành sinh hoạt hiện tại'
                                    : 'Nhập thông tin để tạo ngành sinh hoạt mới'}
                            </DialogDescription>
                        </DialogHeader>
                        <Separator />
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                {/* 🏫 Niên khoá */}
                                <div className="space-y-2">
                                    <Label htmlFor="academic_year_id">
                                        Niên khóa *
                                    </Label>
                                    <Select
                                        value={
                                            data.academic_year_id?.toString() ??
                                            ''
                                        }
                                        onValueChange={(value) =>
                                            setData(
                                                'academic_year_id',
                                                parseInt(value),
                                            )
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

                                {/* 🧭 Tên ngành sinh hoạt */}
                                <div className="space-y-2">
                                    <Label htmlFor="name">
                                        Tên ngành sinh hoạt *
                                    </Label>
                                    <InputGroup>
                                        <InputGroupInput
                                            id="name"
                                            value={data.name}
                                            onChange={(e) =>
                                                setData('name', e.target.value)
                                            }
                                            placeholder="VD: Tiền Ấu"
                                        />
                                        <InputGroupAddon>
                                            <InputGroupText>
                                                Ngành sinh hoạt
                                            </InputGroupText>
                                        </InputGroupAddon>
                                    </InputGroup>
                                    {errors.name && (
                                        <p className="text-sm text-red-500">
                                            {errors.name}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="description">Mô tả</Label>
                                <textarea
                                    id="description"
                                    className="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    value={data.description}
                                    onChange={(e) =>
                                        setData('description', e.target.value)
                                    }
                                    placeholder="Nhập mô tả chi tiết về ngành sinh hoạt..."
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
                                        : editingSector
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
                                Xác nhận xóa ngành sinh hoạt
                            </AlertDialogTitle>
                            <AlertDialogDescription>
                                Bạn có chắc chắn muốn xóa ngành sinh hoạt "
                                {itemToDelete?.name}"? Hành động này không thể
                                hoàn tác và sẽ xóa vĩnh viễn tất cả dữ liệu liên
                                quan đến ngành sinh hoạt này.
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
