import { Head } from '@inertiajs/react';

import HeadingSmall from '@/components/heading-small';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { edit as editAppearance } from '@/routes/appearance';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Cài đặt giao diện',
        href: editAppearance().url,
    },
];

export default function Appearance() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Cài đặt giao diện" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title="Cài đặt giao diện"
                        description="Tùy chỉnh và thay đổi giao diện hiển thị cho tài khoản của bạn"
                    />
                    
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
