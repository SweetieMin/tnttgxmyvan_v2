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
import { index as courses } from '@/routes/management/courses';
import { type BreadcrumbItem } from '@/types';
import type { AcademicYear, Course } from '@/types/academic';
import { soundToast } from '@/utils/sound-toast';
import { Head, useForm, usePage, router } from '@inertiajs/react';
import { Filter, ChevronDown } from 'lucide-react';
import { useEffect, useState } from 'react';
import { DataTable } from './data-table';
import { createCourseColumns } from './columns';
import ErrorBoundary from '@/components/error-boundary';


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Quản lý', href: '' },
    { title: 'Lớp giáo lý', href: courses().url },
];


export interface Props {
    courses?: {
        data: Course[];
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

export default function CourseIndex({ courses = { data: [], links: [], total: 0, from: 0, to: 0, current_page: 1, last_page: 1, per_page: 10 }, academicYears = [], currentAcademicYearId }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const [editingCourse, setEditingCourse] = useState<Course | null>(null);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [itemToDelete, setItemToDelete] = useState<Course | null>(null);
    const [selectedAcademicYearId, setSelectedAcademicYearId] = useState<number | undefined>(
        currentAcademicYearId,
    );
    const [searchTerm, setSearchTerm] = useState('');
    const [showColumnSelect, setShowColumnSelect] = useState(false);
    const [columnVisibility, setColumnVisibility] = useState<Record<string, boolean>>({
        select: true,
        ordering: true,
        name: true,
        academic_year: true,
        description: true,
        actions: true,
    });

    // Column definitions for visibility control
    const columnDefinitions = [
        { id: 'select', label: 'Chọn' },
        { id: 'ordering', label: 'Thứ tự' },
        { id: 'name', label: 'Tên lớp giáo lý' },
        { id: 'academic_year', label: 'Niên khóa' },
        { id: 'description', label: 'Mô tả' },
        { id: 'actions', label: 'Thao tác' },
    ];

    // Helper functions for select all/none
    const handleSelectAll = () => {
        const allVisible = columnDefinitions.reduce((acc, col) => {
            acc[col.id] = true;
            return acc;
        }, {} as Record<string, boolean>);
        setColumnVisibility(allVisible);
    };

    const handleSelectNone = () => {
        const allHidden = columnDefinitions.reduce((acc, col) => {
            acc[col.id] = false;
            return acc;
        }, {} as Record<string, boolean>);
        setColumnVisibility(allHidden);
    };

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

    // Close column select when clicking outside
    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            const target = event.target as Element;
            if (showColumnSelect && !target.closest('.column-select-container')) {
                setShowColumnSelect(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [showColumnSelect]);


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
            academic_year_id: 0,
            name: '',
            description: '',
        });

    const resetForm = () => {
        setData({ id: 0, academic_year_id: 0, name: '', description: '' });
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

    const handleClose = () => { setIsOpen(false); resetForm(); };

    const handleAcademicYearFilter = (academicYearId: string) => {
        const yearId = parseInt(academicYearId);
        setSelectedAcademicYearId(yearId);
        
        router.get('/management/courses', 
            { academic_year_id: yearId },
            { preserveState: true, replace: true }
        );
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

                {/* Unified Filter Section */}
                <div className="mb-6 rounded-lg border bg-card p-4">
                    <div className="flex flex-col gap-4">
                        <div className="flex items-center gap-2">
                            <Filter className="h-4 w-4 text-muted-foreground" />
                            <span className="text-sm font-medium">Bộ lọc và tìm kiếm</span>
                        </div>
                        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                                <div className="flex items-center gap-2">
                                    <span className="text-sm text-muted-foreground">Niên khóa:</span>
                                    <Select
                                        value={selectedAcademicYearId?.toString() || ''}
                                        onValueChange={handleAcademicYearFilter}
                                    >
                                        <SelectTrigger className="w-[200px]">
                                            <SelectValue placeholder={selectedAcademicYearId ? undefined : "Chọn niên khóa"} />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {academicYears.map((year) => (
                                                <SelectItem key={year.id} value={year.id.toString()}>
                                                    {year.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div className="flex items-center gap-2">
                                    <span className="text-sm text-muted-foreground">Tìm kiếm:</span>
                                    <Input
                                        placeholder="Tìm kiếm lớp giáo lý..."
                                        
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                    />
                                </div>
                            </div>
                            <div className="flex items-center gap-2">
                                <span className="text-sm text-muted-foreground">Cột hiển thị:</span>
                                <div className="relative column-select-container">
                                    <Button
                                        variant="outline"
                                        onClick={() => setShowColumnSelect(!showColumnSelect)}
                                        className="min-w-[120px] justify-between"
                                    >
                                        {Object.values(columnVisibility).filter(Boolean).length} cột
                                        <ChevronDown className="ml-2 h-4 w-4" />
                                    </Button>
                                    {showColumnSelect && (
                                        <div className="absolute top-full right-0 mt-1 w-64 bg-white border rounded-md shadow-lg z-50 p-2">
                                            <div className="flex justify-between items-center mb-2">
                                                <span className="text-sm font-medium">Chọn cột hiển thị</span>
                                                <div className="flex gap-1">
                                                    <Button
                                                        size="sm"
                                                        variant="ghost"
                                                        onClick={handleSelectAll}
                                                        className="text-xs h-6 px-2"
                                                    >
                                                        Tất cả
                                                    </Button>
                                                    <Button
                                                        size="sm"
                                                        variant="ghost"
                                                        onClick={handleSelectNone}
                                                        className="text-xs h-6 px-2"
                                                    >
                                                        Không
                                                    </Button>
                                                </div>
                                            </div>
                                            <div className="space-y-1 max-h-48 overflow-y-auto">
                                                {columnDefinitions.map((column) => (
                                                    <label
                                                        key={column.id}
                                                        className="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded cursor-pointer"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            checked={columnVisibility[column.id]}
                                                            onChange={(e) =>
                                                                setColumnVisibility(prev => ({
                                                                    ...prev,
                                                                    [column.id]: e.target.checked
                                                                }))
                                                            }
                                                            className="rounded"
                                                        />
                                                        <span className="text-sm">{column.label}</span>
                                                    </label>
                                                ))}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Data Table */}
                <ErrorBoundary>
                    <DataTable
                        columns={createCourseColumns({
                            onEdit: handleEdit,
                            onDelete: handleDelete,
                        })}
                        data={courses.data.filter(course => 
                            course.name.toLowerCase().includes(searchTerm.toLowerCase())
                        )}
                        columnVisibility={columnVisibility}
                        onColumnVisibilityChange={(updaterOrValue) => {
                            if (typeof updaterOrValue === 'function') {
                                setColumnVisibility(updaterOrValue);
                            } else {
                                setColumnVisibility(updaterOrValue);
                            }
                        }}
                        pagination={{
                            current_page: courses.current_page,
                            last_page: courses.last_page,
                            per_page: courses.per_page,
                            total: courses.total,
                            from: courses.from,
                            to: courses.to,
                            links: courses.links,
                        }}
                    />
                </ErrorBoundary>

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