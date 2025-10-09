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
import { index as courses } from '@/routes/management/courses';
import { type BreadcrumbItem } from '@/types';
import type { AcademicYear, Course } from '@/types/academic';
import { soundToast } from '@/utils/sound-toast';
import { Head, useForm, usePage } from '@inertiajs/react';
import { SquarePen, Trash2 } from 'lucide-react';
import { useEffect, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Quản lý', href: '' },
    { title: 'Lớp giáo lý', href: courses().url },
];

export interface Props {
    courses: {
        data: Course[];
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
}

export default function CourseIndex({ courses, academicYears }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const [editingCourse, setEditingCourse] = useState<Course | null>(null);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [itemToDelete, setItemToDelete] = useState<Course | null>(null);

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
        setEditingCourse(null);
        clearErrors();
    };

    const handleAddClick = () => {
        resetForm();
        setIsOpen(true);
    };

    const handleEdit = (item: Course) => {
        setEditingCourse(item);
        setData({
            id: item.id,
            academic_year_id: item.academic_year_id,
            name: item.name,
            description: item.description || '',
        });
        setIsOpen(true);
    };

    const handleDelete = (item: Course) => {
        setItemToDelete(item);
        setDeleteDialogOpen(true);
    };

    const confirmDelete = () => {
        if (itemToDelete) {
            destroy(`/management/courses/${itemToDelete.id}`, {
                onSuccess: () => {
                    setDeleteDialogOpen(false);
                    setItemToDelete(null);
                },
            });
        }
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (editingCourse) {
            put(`/management/courses/${editingCourse.id}`, {
                onSuccess: () => {
                    setIsOpen(false);
                    resetForm();
                },
            });
        } else {
            post('/management/courses', {
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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Lớp giáo lý" />

            <div className="px-4 py-6">
                {/* Header + Button */}
                <AppHeaderAddButton
                    title="Lớp giáo lý"
                    description="Quản lý các lớp giáo lý, bao gồm thông tin tên lớp giáo lý và mô tả chi tiết."
                    buttonLabel="Thêm lớp giáo lý"
                    onButtonClick={handleAddClick}
                />

                {/* Table/Card responsive */}
                <AppTable<Course>
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
                            title: 'Tên lớp giáo lý',
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
                    data={courses.data}
                    emptyMessage="Chưa có lớp giáo lý nào"
                    emptyHint="Hãy nhấn nút 'Thêm lớp giáo lý' để tạo lớp giáo lý đầu tiên."
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
                    renderCard={(item: Course) => (
                        <div className="flex flex-col gap-3">
                            {/* 🏷️ Tên lớp giáo lý */}
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
                    links={courses.links}
                    total={courses.total}
                    from={courses.from}
                    to={courses.to}
                />

                {/* Course Dialog */}
                <Dialog open={isOpen} onOpenChange={setIsOpen}>
                    <DialogContent className="max-h-[90vh] overflow-y-auto">
                        <DialogHeader>
                            <DialogTitle>
                                {editingCourse
                                    ? 'Chỉnh sửa lớp giáo lý'
                                    : 'Thêm lớp giáo lý mới'}
                            </DialogTitle>
                            <DialogDescription>
                                {editingCourse
                                    ? 'Cập nhật thông tin lớp giáo lý hiện tại'
                                    : 'Nhập thông tin để tạo lớp giáo lý mới'}
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

                                <div className="space-y-2">
                                    <Label htmlFor="name">
                                        Tên lớp giáo lý *
                                    </Label>
                                    <InputGroup>
                                        <InputGroupInput
                                            id="name"
                                            value={data.name}
                                            onChange={(e) =>
                                                setData('name', e.target.value)
                                            }
                                            placeholder="VD: Khai Tâm 1"
                                        />
                                        <InputGroupAddon>
                                            <InputGroupText>
                                                Lớp giáo lý
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
                                    placeholder="Nhập mô tả chi tiết về lớp giáo lý..."
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
                                        : editingCourse
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
                                Xác nhận xóa lớp giáo lý
                            </AlertDialogTitle>
                            <AlertDialogDescription>
                                Bạn có chắc chắn muốn xóa lớp giáo lý "
                                {itemToDelete?.name}"? Hành động này không thể
                                hoàn tác và sẽ xóa vĩnh viễn tất cả dữ liệu liên
                                quan đến lớp giáo lý này.
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
