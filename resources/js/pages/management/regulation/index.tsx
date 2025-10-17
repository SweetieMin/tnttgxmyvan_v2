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
import AppLayout from '@/layouts/app-layout';
import { index as regulations } from '@/routes/management/regulations';
import { type BreadcrumbItem } from '@/types';
import type { AcademicYear, Regulation } from '@/types/academic';
import { Head, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { createRegulationColumns } from './columns';
import ErrorBoundary from '@/components/error-boundary';
import { AppDataTableFilterYear, ColumnDefinition } from '@/components/app-data-table-filter-year';

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
        regulation_roles: true,
        actions: true,
    });

    // Column definitions for visibility control
    const columnDefinitions: ColumnDefinition[] = [
        { id: 'select', label: 'Chọn' },
        { id: 'ordering', label: 'STT' },
        { id: 'description', label: 'Nội dung' },
        { id: 'type', label: 'Loại' },
        { id: 'points', label: 'Điểm' },
        { id: 'regulation_roles', label: 'Đối tượng áp dụng' },
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


    const handleAddClick = () => {
        router.visit('/management/regulations/create');
    };

    const handleEdit = (item: Regulation) => {
        router.visit(`/management/regulations/${item.id}/edit`);
    };

    const handleDelete = (item: Regulation) => {
        setItemToDelete(item);
        setDeleteDialogOpen(true);
    };

    const confirmDelete = () => {
        if (itemToDelete) {
            router.delete(`/management/regulations/${itemToDelete.id}`, {
                onSuccess: () => {
                    setDeleteDialogOpen(false);
                    setItemToDelete(null);
                },
            });
        }
    };

    const handleAcademicYearFilter = (academicYearId: number) => {
        setSelectedAcademicYearId(academicYearId);
        
        router.get('/management/regulations', 
            { academic_year_id: academicYearId },
            { preserveState: true, replace: true }
        );
    };


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

                {/* Data Table Page with Academic Year Filter */}
                <ErrorBoundary>
                    <AppDataTableFilterYear
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
                        academicYears={academicYears}
                        selectedAcademicYearId={selectedAcademicYearId}
                        onAcademicYearChange={handleAcademicYearFilter}
                        academicYearLabel="Lọc theo niên khóa:"
                        academicYearPlaceholder="Chọn niên khóa để lọc"
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
