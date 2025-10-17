import { Toaster } from '@/components/ui/sonner';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { type BreadcrumbItem } from '@/types';
import { soundToast } from '@/utils/sound-toast';
import { usePage } from '@inertiajs/react';
import { type ReactNode, useEffect } from 'react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default function AppLayout({
    children,
    breadcrumbs,
    ...props
}: AppLayoutProps) {
    // 🔹 Lấy flash từ Inertia
    const { flash } = usePage<{
        flash?: {
            success?: string;
            error?: string;
            message?: string;
            description?: string;
        };
    }>().props;

    // 🔹 Hiển thị toast tự động mỗi khi có flash
    useEffect(() => {
        if (flash?.success)
            soundToast('success', flash.success, flash.description);
        else if (flash?.error)
            soundToast('error', flash.error, flash.description);
        else if (flash?.message)
            soundToast('info', flash.message, flash.description);
    }, [flash]);

    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
            {children}

            {/* Toaster toàn hệ thống */}
            <Toaster
                position="top-right"
                richColors
                closeButton
                expand
                theme='light'
                duration={4000}
            />
        </AppLayoutTemplate>
    );
}
