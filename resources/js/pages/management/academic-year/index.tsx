import AppHeaderAddButton from '@/components/app-header-add-button';
import { AppPopoverCalendar } from '@/components/app-popover-calendar';
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
import { Input } from '@/components/ui/input';
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
    SelectGroup,
} from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { index as academic_years } from '@/routes/management/academic-years';
import { type BreadcrumbItem } from '@/types';
import type { AcademicYear } from '@/types/academic';
import { soundToast } from '@/utils/sound-toast';
import { Head, useForm, usePage } from '@inertiajs/react';
import { SquarePen, Trash2 } from 'lucide-react';
import { useEffect, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Quản lý', href: '' },
    { title: 'Niên khóa', href: academic_years().url },
];

export interface Props {
    years: AcademicYear[];
    flash?: {
        success?: string;
        error?: string;
    };
}

export default function AcademicYearIndex({ years = [] }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const [editingAcademicYear, setEditingAcademicYear] =
        useState<AcademicYear | null>(null);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [itemToDelete, setItemToDelete] = useState<AcademicYear | null>(null);

    // Calendar states
    const [catechismStartOpen, setCatechismStartOpen] = useState(false);
    const [catechismStartDate, setCatechismStartDate] = useState<
        Date | undefined
    >(undefined);
    const [catechismEndOpen, setCatechismEndOpen] = useState(false);
    const [catechismEndDate, setCatechismEndDate] = useState<Date | undefined>(
        undefined,
    );
    const [activityStartOpen, setActivityStartOpen] = useState(false);
    const [activityStartDate, setActivityStartDate] = useState<
        Date | undefined
    >(undefined);
    const [activityEndOpen, setActivityEndOpen] = useState(false);
    const [activityEndDate, setActivityEndDate] = useState<Date | undefined>(
        undefined,
    );
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
        name: '',
        catechism_start_date: '',
        catechism_end_date: '',
        catechism_avg_score: 1,
        catechism_training_score: 1,
        activity_start_date: '',
        activity_end_date: '',
        activity_score: 1,
        status_academic: '',
    });

    const resetCalendarStates = () => {
        setCatechismStartDate(undefined);
        setCatechismEndDate(undefined);
        setActivityStartDate(undefined);
        setActivityEndDate(undefined);
        setCatechismStartOpen(false);
        setCatechismEndOpen(false);
        setActivityStartOpen(false);
        setActivityEndOpen(false);
    };

    const resetForm = () => {
        setData({
            id: 0,
            name: '',
            catechism_start_date: '',
            catechism_end_date: '',
            catechism_avg_score: 5,
            catechism_training_score: 5,
            activity_start_date: '',
            activity_end_date: '',
            activity_score: 200,
            status_academic: 'upcoming',
        });
        setEditingAcademicYear(null);
        clearErrors();
        resetCalendarStates();
    };

    const handleAddClick = () => {
        resetForm();
        setIsOpen(true);
    };

    const handleEdit = (item: AcademicYear) => {
        setEditingAcademicYear(item);

        // Set calendar dates
        setCatechismStartDate(
            item.catechism_start_date
                ? new Date(item.catechism_start_date)
                : undefined,
        );
        setCatechismEndDate(
            item.catechism_end_date
                ? new Date(item.catechism_end_date)
                : undefined,
        );
        setActivityStartDate(
            item.activity_start_date
                ? new Date(item.activity_start_date)
                : undefined,
        );
        setActivityEndDate(
            item.activity_end_date
                ? new Date(item.activity_end_date)
                : undefined,
        );

        setData({
            id: item.id,
            name: item.name,
            catechism_start_date: item.catechism_start_date
                ? new Date(item.catechism_start_date)
                      .toISOString()
                      .split('T')[0]
                : '',
            catechism_end_date: item.catechism_end_date
                ? new Date(item.catechism_end_date).toISOString().split('T')[0]
                : '',
            catechism_avg_score: item.catechism_avg_score || 1,
            catechism_training_score: item.catechism_training_score || 1,
            activity_start_date: item.activity_start_date
                ? new Date(item.activity_start_date).toISOString().split('T')[0]
                : '',
            activity_end_date: item.activity_end_date
                ? new Date(item.activity_end_date).toISOString().split('T')[0]
                : '',
            activity_score: item.activity_score || 1,
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

    const handleClose = () => {
        setIsOpen(false);
        resetForm();
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Niên khoá" />

            <div className="px-4 py-6">
                {/* Header + Button */}
                <AppHeaderAddButton
                    title="Niên khoá"
                    description="Thiết lập thời gian học giáo lý và sinh hoạt cho từng năm học, bao gồm mốc thời gian bắt đầu – kết thúc, thời gian điểm danh sinh hoạt, cùng với quy định điểm tối thiểu cần đạt cho từng hạng mục."
                    buttonLabel="Thêm niên khoá"
                    onButtonClick={handleAddClick}
                />

                {/* Table/Card responsive */}
                <AppTable<AcademicYear>
                    columns={[
                        {
                            title: 'Tên niên khoá',
                            className: 'font-semibold w-[180px]',
                            render: (item) => item.name,
                        },
                        {
                            title: 'Giáo lý',
                            className: 'text-muted-foreground w-[200px]',
                            render: (item) =>
                                `${new Date(item.catechism_start_date).toLocaleDateString('vi-VN')} - ${new Date(item.catechism_end_date).toLocaleDateString('vi-VN')}`,
                        },
                        {
                            title: 'Điểm Giáo Lý',
                            className: 'text-center w-[120px]',
                            render: (item) => (
                                <span className="font-bold">
                                    {item.catechism_avg_score}/10
                                </span>
                            ),
                        },
                        {
                            title: 'Điểm chuyên cần Giáo Lý',
                            className: 'text-center w-[120px]',
                            render: (item) => (
                                <span className="font-bold">
                                    {item.catechism_training_score}/10
                                </span>
                            ),
                        },
                        {
                            title: 'Sinh Hoạt',
                            className: 'text-muted-foreground w-[200px]',
                            render: (item) =>
                                `${new Date(item.activity_start_date).toLocaleDateString('vi-VN')} - ${new Date(item.activity_end_date).toLocaleDateString('vi-VN')}`,
                        },
                        {
                            title: 'Điểm Sinh Hoạt',
                            className: 'text-center font-bold w-[100px]',
                            render: (item) => item.activity_score,
                        },
                    ]}
                    data={years}
                    emptyMessage="Chưa có niên khoá nào"
                    emptyHint="Hãy nhấn nút 'Thêm niên khoá' để tạo niên khoá đầu tiên."
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
                    renderCard={(item: AcademicYear) => (
                        <div className="flex flex-col gap-3">
                            {/* 🏷️ Tên niên khóa */}
                            <div>
                                <span className="text-base font-semibold text-foreground">
                                    {item.name}
                                </span>
                            </div>
                            <Separator />
                            {/* 📅 Hai cột thông tin */}
                            <div className="grid grid-cols-2 gap-3 text-sm text-muted-foreground">
                                {/* Cột trái: Giáo lý */}
                                <div className="space-y-1">
                                    <p className="font-semibold text-foreground">
                                        Giáo lý
                                    </p>
                                    <p>
                                        {new Date(
                                            item.catechism_start_date,
                                        ).toLocaleDateString('vi-VN')}{' '}
                                        –{' '}
                                        {new Date(
                                            item.catechism_end_date,
                                        ).toLocaleDateString('vi-VN')}
                                    </p>
                                    <p>
                                        Điểm TB:{' '}
                                        <span className="font-bold">
                                            {item.catechism_avg_score}
                                        </span>
                                        /10
                                    </p>
                                    <p>
                                        Điểm ĐT:{' '}
                                        <span className="font-bold">
                                            {item.catechism_training_score}
                                        </span>
                                        /10
                                    </p>
                                </div>

                                {/* Cột phải: Sinh hoạt */}
                                <div className="space-y-1">
                                    <p className="font-semibold text-foreground">
                                        Sinh hoạt
                                    </p>
                                    <p>
                                        {new Date(
                                            item.activity_start_date,
                                        ).toLocaleDateString('vi-VN')}{' '}
                                        –{' '}
                                        {new Date(
                                            item.activity_end_date,
                                        ).toLocaleDateString('vi-VN')}
                                    </p>
                                    <p>
                                        Điểm:{' '}
                                        <span className="font-bold">
                                            {item.activity_score}
                                        </span>
                                    </p>
                                </div>
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
                            <div className="grid  gap-4 grid-cols-2">
                                <div className="space-y-2">
                                    <Label htmlFor="status_academic">
                                        Niên khoá
                                    </Label>
                                    <InputGroup>
                                        <InputGroupInput
                                            id="name"
                                            value={data.name}
                                            onChange={(e) =>
                                                setData('name', e.target.value)
                                            }
                                            placeholder="VD: 2024-2025"
                                        />
                                        <InputGroupAddon>
                                            <InputGroupText>
                                                Niên khoá
                                            </InputGroupText>
                                        </InputGroupAddon>
                                    </InputGroup>

                                    {errors.name && (
                                        <p className="text-sm text-red-500">
                                            {errors.name}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="status_academic">
                                        Trạng thái
                                    </Label>
                                    <Select
                                        value={data.status_academic}
                                        onValueChange={(value) =>
                                            setData('status_academic', value)
                                        }
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Chọn trạng thái" />
                                        </SelectTrigger>
                                        <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="upcoming">
                                                Sắp diễn ra
                                            </SelectItem>
                                            <SelectItem value="ongoing">
                                                Đang diễn ra
                                            </SelectItem>
                                            <SelectItem value="finished">
                                                Đã kết thúc
                                            </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    {errors.status_academic && (
                                        <p className="text-sm text-red-500">
                                            {errors.status_academic}
                                        </p>
                                    )}
                                </div>
                            </div>
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">
                                    Thời gian Giáo lý
                                </h3>
                                <div className="grid gap-4 grid-cols-2">
                                    <AppPopoverCalendar
                                        id="catechism_start_date"
                                        label="Ngày bắt đầu"
                                        date={catechismStartDate}
                                        onChange={(date) => {
                                            setCatechismStartDate(date);
                                            setData(
                                                'catechism_start_date',
                                                date
                                                    ? date
                                                          .toISOString()
                                                          .split('T')[0]
                                                    : '',
                                            );
                                        }}
                                        required
                                        error={errors.catechism_start_date}
                                    />

                                    <AppPopoverCalendar
                                        id="catechism_end_date"
                                        label="Ngày kết thúc"
                                        date={catechismEndDate}
                                        onChange={(date) => {
                                            setCatechismEndDate(date);
                                            setData(
                                                'catechism_end_date',
                                                date
                                                    ? date
                                                          .toISOString()
                                                          .split('T')[0]
                                                    : '',
                                            );
                                        }}
                                        required
                                        error={errors.catechism_end_date}
                                    />
                                </div>
                            </div>

                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">
                                    Thời gian Sinh hoạt
                                </h3>
                                <div className="grid gap-4 grid-cols-2">
                                    <AppPopoverCalendar
                                        id="activity_start_date"
                                        label="Ngày bắt đầu"
                                        date={activityStartDate}
                                        onChange={(date) => {
                                            setActivityStartDate(date);
                                            setData(
                                                'activity_start_date',
                                                date
                                                    ? date
                                                          .toISOString()
                                                          .split('T')[0]
                                                    : '',
                                            );
                                        }}
                                        required
                                        error={errors.activity_start_date}
                                    />

                                    <AppPopoverCalendar
                                        id="activity_end_date"
                                        label="Ngày kết thúc"
                                        date={activityEndDate}
                                        onChange={(date) => {
                                            setActivityEndDate(date);
                                            setData(
                                                'activity_end_date',
                                                date
                                                    ? date
                                                          .toISOString()
                                                          .split('T')[0]
                                                    : '',
                                            );
                                        }}
                                        required
                                        error={errors.activity_end_date}
                                    />
                                </div>
                            </div>

                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold">
                                    Điểm số
                                </h3>
                                <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div className="space-y-2">
                                        <Label htmlFor="catechism_avg_score">
                                            Điểm TB Giáo lý *
                                        </Label>
                                        <Input
                                            id="catechism_avg_score"
                                            type="number"
                                            min="1"
                                            max="10"
                                            step="0.1"
                                            value={data.catechism_avg_score}
                                            onChange={(e) =>
                                                setData(
                                                    'catechism_avg_score',
                                                    parseFloat(
                                                        e.target.value,
                                                    ) || 1,
                                                )
                                            }
                                            required
                                        />
                                        {errors.catechism_avg_score && (
                                            <p className="text-sm text-red-500">
                                                {errors.catechism_avg_score}
                                            </p>
                                        )}
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="catechism_training_score">
                                            Điểm ĐT Giáo lý *
                                        </Label>
                                        <Input
                                            id="catechism_training_score"
                                            type="number"
                                            min="1"
                                            max="10"
                                            step="0.1"
                                            value={
                                                data.catechism_training_score
                                            }
                                            onChange={(e) =>
                                                setData(
                                                    'catechism_training_score',
                                                    parseFloat(
                                                        e.target.value,
                                                    ) || 1,
                                                )
                                            }
                                            required
                                        />
                                        {errors.catechism_training_score && (
                                            <p className="text-sm text-red-500">
                                                {
                                                    errors.catechism_training_score
                                                }
                                            </p>
                                        )}
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="activity_score">
                                            Điểm Sinh hoạt *
                                        </Label>
                                        <Input
                                            id="activity_score"
                                            type="number"
                                            min="1"
                                            max="1000"
                                            value={data.activity_score}
                                            onChange={(e) =>
                                                setData(
                                                    'activity_score',
                                                    parseInt(e.target.value) ||
                                                        1,
                                                )
                                            }
                                            required
                                        />
                                        {errors.activity_score && (
                                            <p className="text-sm text-red-500">
                                                {errors.activity_score}
                                            </p>
                                        )}
                                    </div>
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
