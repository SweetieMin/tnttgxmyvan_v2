import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { index as roles } from '@/routes/management/roles';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Quản lý', href: '' },
    { title: 'Chức vụ', href: roles().url },
];

export default function Role() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Chức vụ" />
            
        </AppLayout>
    );
}
