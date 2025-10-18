import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { index as spirituals } from '@/routes/personnel/spirituals';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Quản lý', href: '' },
    { title: 'Linh hướng', href: spirituals.url(), },
];

// Interface cho dữ liệu từ Laravel
interface SpiritualsPageProps {
    users?: Array<{
        id: number;
        code: string;
        name: string;
        avatar?: string;
        position?: string;
        [key: string]: any;
    }>;
}

export default function SpiritualsIndex({ users = [] }: SpiritualsPageProps) {

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Linh hướng - Quản lý nhân sự" />


        </AppLayout>
    );
}
