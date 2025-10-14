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
import { index as academicYears } from '@/routes/management/academic-years';
import { type BreadcrumbItem } from '@/types';
import type { AcademicYear } from '@/types/academic';
import { soundToast } from '@/utils/sound-toast';
import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { createAcademicYearColumns } from './columns';
import ErrorBoundary from '@/components/error-boundary';
import { AppDataTablePage, ColumnDefinition } from '@/components/app-data-table-page';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Quản lý', href: '' },
    { title: 'Niên khóa', href: academicYears().url },
];

export interface Props {
    years?: {
        data: AcademicYear[];
        links: { url: string | null; label: string; active: boolean }[];
        total: number;
        from: number;
        to: number;
        current_page: number;
        last_page: number;
        per_page: number;
    };
}

export default function AcademicYearIndex({ years = { data: [], links: [], total: 0, from: 0, to: 0, current_page: 1, last_page: 1, per_page: 10 } }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const [editingAcademicYear, setEditingAcademicYear] = useState<AcademicYear | null>(null);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [itemToDelete, setItemToDelete] = useState<AcademicYear | null>(null);
    const [searchTerm, setSearchTerm] = useState('');
    const [columnVisibility, setColumnVisibility] = useState<Record<string, boolean>>({
        select: true,
        name: true,
        catechism_period: true,
        catechism_avg_score: true,
        catechism_training_score: true,
        activity_period: true,
        activity_score: true,
        actions: true,
    });

    // Column definitions for visibility control
    const columnDefinitions: ColumnDefinition[] = [
        { id: 'select', label: 'Chọn' },
        { id: 'name', label: 'Niên khóa' },
        { id: 'catechism_period', label: 'Giáo lý' },
        { id: 'catechism_avg_score', label: 'Điểm giáo lý' },
        { id: 'catechism_training_score', label: 'Điểm chuyên cần giáo lý' },
        { id: 'activity_period', label: 'Sinh hoạt' },
        { id: 'activity_score', label: 'Điểm sinh hoạt' },
        { id: 'actions', label: 'Thao tác' },
    ];

    const { flash } = usePage<{
        flash?: { success?: string; error?: string; message?: string };
    }>().props;

    useEffect(() => {
        if (flash?.success) soundToast('success', flash.success);
        else if (flash?.error) soundToast('error', flash.error);
        else if (flash?.message) soundToast('success', flash.message);
    }, [flash?.success, flash?.error, flash?.message]);

    const { data, setData, post, put, delete: destroy, processing, errors, clearErrors } =
        useForm({
            id: 0,
            name: '',
            catechism_start_date: '',
            catechism_end_date: '',
            catechism_avg_score: 0,
            catechism_training_score: 0,
            activity_start_date: '',
            activity_end_date: '',
            activity_score: 0,
            status_academic: 'upcoming',
        });

    const resetForm = () => {
        setData({
            id: 0,
            name: '',
            catechism_start_date: '',
            catechism_end_date: '',
            catechism_avg_score: 0,
            catechism_training_score: 0,
            activity_start_date: '',
            activity_end_date: '',
            activity_score: 0,
            status_academic: 'upcoming',
        });
        setEditingAcademicYear(null);
        clearErrors();
    };

    const handleAddClick = () => {
        resetForm();
        setIsOpen(true);
    };

    const handleEdit = (item: AcademicYear) => {
        setEditingAcademicYear(item);
        setData({
            id: item.id,
            name: item.name,
            catechism_start_date: item.catechism_start_date,
            catechism_end_date: item.catechism_end_date,
            catechism_avg_score: item.catechism_avg_score,
            catechism_training_score: item.catechism_training_score,
            activity_start_date: item.activity_start_date,
            activity_end_date: item.activity_end_date,
            activity_score: item.activity_score,
            status_academic: item.status_academic,
        });
        setIsOpen(true);
    };

    const handleDelete = (item: AcademicYear) => {
        setItemToDelete(item);
        setDeleteDialogOpen(true);
    };

    const confirmDelete = () => {
        if (itemToDelete) {
            destroy(`/management/academic-years/${itemToDelete.id}`, {
                onSuccess: () => {
                    setDeleteDialogOpen(false);
                    setItemToDelete(null);
                },
            });
        }
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (editingAcademicYear) {
            put(`/management/academic-years/${editingAcademicYear.id}`, {
                onSuccess: () => {
                    setIsOpen(false);
                    resetForm();
                },
            });
        } else {
            post('/management/academic-years', {
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
            <Head title="Niên khóa" />

            <div className="px-4 py-6">
                {/* Header + Button */}
                <AppHeaderAddButton
                    title="Niên khóa"
                    description="Quản lý các niên khóa trong hệ thống, bao gồm thông tin thời gian và trạng thái."
                    buttonLabel="Thêm niên khóa"
                    onButtonClick={handleAddClick}
                />

                {/* Data Table Page */}
                <ErrorBoundary>
                    <AppDataTablePage
                        columns={createAcademicYearColumns({
                            onEdit: handleEdit,
                            onDelete: handleDelete,
                        })}
                        data={years.data.filter(year => 
                            year.name.toLowerCase().includes(searchTerm.toLowerCase())
                        )}
                        searchTerm={searchTerm}
                        onSearchChange={setSearchTerm}
                        searchPlaceholder="Tìm kiếm niên khóa..."
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
                            current_page: years.current_page,
                            last_page: years.last_page,
                            per_page: years.per_page,
                            total: years.total,
                            from: years.from,
                            to: years.to,
                            links: years.links,
                        }}
                    />
                </ErrorBoundary>

                {/* Academic Year Dialog */}
                <Dialog open={isOpen} onOpenChange={setIsOpen}>
                    <DialogContent className="max-h-[90vh] overflow-y-auto">
                        <DialogHeader>
                            <DialogTitle>
                                {editingAcademicYear
                                    ? 'Chỉnh sửa niên khóa'
                                    : 'Thêm niên khóa mới'}
                            </DialogTitle>
                            <DialogDescription>
                                {editingAcademicYear
                                    ? 'Cập nhật thông tin niên khóa hiện tại'
                                    : 'Nhập thông tin để tạo niên khóa mới'}
                            </DialogDescription>
                        </DialogHeader>
                        <Separator />
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="name">
                                        Tên niên khóa *
                                    </Label>
                                    <Input
                                        id="name"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        placeholder="VD: 2024-2025"
                                    />
                                    {errors.name && (
                                        <p className="text-sm text-red-500">
                                            {errors.name}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="status_academic">
                                        Trạng thái *
                                    </Label>
                                    <Select
                                        value={data.status_academic}
                                        onValueChange={(value) => setData('status_academic', value)}
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Chọn trạng thái" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="upcoming">Sắp tới</SelectItem>
                                            <SelectItem value="ongoing">Đang diễn ra</SelectItem>
                                            <SelectItem value="completed">Đã kết thúc</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    {errors.status_academic && (
                                        <p className="text-sm text-red-500">
                                            {errors.status_academic}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="catechism_start_date">
                                        Bắt đầu giáo lý
                                    </Label>
                                    <Input
                                        id="catechism_start_date"
                                        type="date"
                                        value={data.catechism_start_date}
                                        onChange={(e) => setData('catechism_start_date', e.target.value)}
                                    />
                                    {errors.catechism_start_date && (
                                        <p className="text-sm text-red-500">
                                            {errors.catechism_start_date}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="catechism_end_date">
                                        Kết thúc giáo lý
                                    </Label>
                                    <Input
                                        id="catechism_end_date"
                                        type="date"
                                        value={data.catechism_end_date}
                                        onChange={(e) => setData('catechism_end_date', e.target.value)}
                                    />
                                    {errors.catechism_end_date && (
                                        <p className="text-sm text-red-500">
                                            {errors.catechism_end_date}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="activity_start_date">
                                        Bắt đầu hoạt động
                                    </Label>
                                    <Input
                                        id="activity_start_date"
                                        type="date"
                                        value={data.activity_start_date}
                                        onChange={(e) => setData('activity_start_date', e.target.value)}
                                    />
                                    {errors.activity_start_date && (
                                        <p className="text-sm text-red-500">
                                            {errors.activity_start_date}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="activity_end_date">
                                        Kết thúc hoạt động
                                    </Label>
                                    <Input
                                        id="activity_end_date"
                                        type="date"
                                        value={data.activity_end_date}
                                        onChange={(e) => setData('activity_end_date', e.target.value)}
                                    />
                                    {errors.activity_end_date && (
                                        <p className="text-sm text-red-500">
                                            {errors.activity_end_date}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div className="space-y-2">
                                    <Label htmlFor="catechism_avg_score">
                                        Điểm TB giáo lý
                                    </Label>
                                    <Input
                                        id="catechism_avg_score"
                                        type="number"
                                        min="1"
                                        max="10"
                                        step="0.5"
                                        value={data.catechism_avg_score}
                                        onChange={(e) => setData('catechism_avg_score', parseFloat(e.target.value) || 0)}
                                    />
                                    {errors.catechism_avg_score && (
                                        <p className="text-sm text-red-500">
                                            {errors.catechism_avg_score}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="catechism_training_score">
                                        Điểm huấn luyện
                                    </Label>
                                    <Input
                                        id="catechism_training_score"
                                        type="number"
                                        min="1"
                                        max="10"
                                        step="0.5"
                                        value={data.catechism_training_score}
                                        onChange={(e) => setData('catechism_training_score', parseFloat(e.target.value) || 0)}
                                    />
                                    {errors.catechism_training_score && (
                                        <p className="text-sm text-red-500">
                                            {errors.catechism_training_score}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="activity_score">
                                        Điểm hoạt động
                                    </Label>
                                    <Input
                                        id="activity_score"
                                        type="number"
                                        min="1"
                                        max="1000"
                                        step="1"
                                        value={data.activity_score}
                                        onChange={(e) => setData('activity_score', parseFloat(e.target.value) || 0)}
                                    />
                                    {errors.activity_score && (
                                        <p className="text-sm text-red-500">
                                            {errors.activity_score}
                                        </p>
                                    )}
                                </div>
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
                                        : editingAcademicYear
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
                                Xác nhận xóa niên khóa
                            </AlertDialogTitle>
                            <AlertDialogDescription>
                                Bạn có chắc chắn muốn xóa niên khóa "
                                {itemToDelete?.name}"? Hành động này không thể
                                hoàn tác và sẽ xóa vĩnh viễn tất cả dữ liệu liên
                                quan đến niên khóa này.
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