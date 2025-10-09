import AppHeaderAddButton from '@/components/app-header-add-button';
import AppPagination from '@/components/app-pagination';
import { AppTable } from '@/components/app-table';
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
import { soundToast } from '@/utils/sound-toast';
import { Head, useForm, usePage, router } from '@inertiajs/react';
import { SquarePen, Trash2, Filter } from 'lucide-react';
import { useEffect, useState } from 'react';

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

    const { flash } = usePage<{
        flash?: {
            success?: string;
            error?: string;
            message?: string;
        };
    }>().props;

    useEffect(() => {
        if (flash?.success) {
            soundToast('success', flash.success);
        } else if (flash?.error) {
            soundToast('error', flash.error);
        } else if (flash?.message) {
            soundToast('success', flash.message);
        }
    }, [flash?.success, flash?.error, flash?.message]);

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
        const yearId = academicYearId === 'all' ? undefined : parseInt(academicYearId);
        setSelectedAcademicYearId(yearId);
        
        router.get('/management/sectors', 
            yearId ? { academic_year_id: yearId } : {},
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

                {/* Filter Section */}
                <div className="mb-6 rounded-lg border bg-card p-4">
                    <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div className="flex items-center gap-2">
                            <Filter className="h-4 w-4 text-muted-foreground" />
                            <span className="text-sm font-medium">Lọc theo niên khóa:</span>
                        </div>
                        <div className="flex gap-2">
                            <Select
                                value={selectedAcademicYearId?.toString() || 'all'}
                                onValueChange={handleAcademicYearFilter}
                            >
                                <SelectTrigger className="w-[200px]">
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
                        </div>
                    </div>
                </div>

                {/* Table/Card responsive */}
                <AppTable<Sector>
                    columns={[
                        {
                            title: 'Thứ tự',
                            className: 'text-center w-[100px]',
                            render: (item) => (
                                <span className="font-semibold text-primary">
                                    {item.ordering}
                                </span>
                            ),
                        },
                        {
                            title: 'Tên ngành sinh hoạt',
                            className: 'font-semibold w-[200px]',
                            render: (item) => item.name,
                        },
                        {
                            title: 'Niên khóa',
                            className: 'w-[150px]',
                            render: (item) => (
                                <span className="text-sm">
                                    {item.academic_year?.name || 'Chưa chọn'}
                                </span>
                            ),
                        },
                        {
                            title: 'Mô tả',
                            className: 'text-muted-foreground',
                            render: (item) => (
                                <span className="line-clamp-2">
                                    {item.description || 'Không có mô tả'}
                                </span>
                            ),
                        },
                    ]}
                    data={sectors.data}
                    emptyMessage="Chưa có ngành sinh hoạt nào"
                    emptyHint="Hãy nhấn nút 'Thêm ngành sinh hoạt' để tạo ngành sinh hoạt đầu tiên."
                    renderActions={(item) => (
                        <div className="flex justify-center gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                onClick={() => handleEdit(item)}
                            >
                                <SquarePen /> Sửa
                            </Button>
                            <Button
                                variant="destructive"
                                size="sm"
                                onClick={() => handleDelete(item)}
                            >
                                <Trash2 /> Xoá
                            </Button>
                        </div>
                    )}
                    renderCard={(item: Sector) => (
                        <div className="flex flex-col gap-3">
                            {/* 🏷️ Tên ngành sinh hoạt */}
                            <div className="flex items-center justify-between">
                                <span className="text-base font-semibold text-foreground">
                                    {item.name}
                                </span>
                                <span className="text-sm font-semibold text-primary">
                                    #{item.ordering}
                                </span>
                            </div>
                            <Separator />
                            {/* 📅 Niên khóa */}
                            <div className="text-sm">
                                <span className="font-medium text-foreground">
                                    Niên khóa:{' '}
                                </span>
                                <span className="text-muted-foreground">
                                    {item.academic_year?.name || 'Chưa chọn'}
                                </span>
                            </div>
                            <Separator />
                            {/* 📝 Mô tả */}
                            <div className="text-sm text-muted-foreground">
                                <p className="line-clamp-3">
                                    {item.description || 'Không có mô tả'}
                                </p>
                            </div>
                            <Separator />
                            {/* ⚙️ Hành động */}
                            <div className="mt-3 flex gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    onClick={() => handleEdit(item)}
                                >
                                    <SquarePen className="mr-1 h-4 w-4" /> Sửa
                                </Button>
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    onClick={() => handleDelete(item)}
                                >
                                    <Trash2 className="mr-1 h-4 w-4" /> Xoá
                                </Button>
                            </div>
                        </div>
                    )}
                />

                {/* Pagination */}
                <AppPagination
                    links={sectors.links}
                    total={sectors.total}
                    from={sectors.from}
                    to={sectors.to}
                />

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
