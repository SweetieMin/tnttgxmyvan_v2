// components/app-card-empty.tsx
'use client';

import { Card } from '@/components/ui/card';
import { Inbox } from 'lucide-react';

export default function AppCardEmpty({
    title = 'Không có dữ liệu',
    message = 'Hãy thêm mới dữ liệu để bắt đầu quản lý.',
    icon = (
        <Inbox className="h-8 w-8 text-muted-foreground opacity-60 sm:h-9 sm:w-9 md:h-10 md:w-10" />
    ),
}: {
    title?: string;
    message?: string;
    icon?: React.ReactNode;
}) {
    return (
        <div className="flex w-full items-center justify-center">
            <Card className="flex w-full flex-col items-center justify-center gap-3 border border-border bg-background p-8 text-center shadow-sm sm:gap-4 sm:p-10 md:p-12 lg:p-16">
                {icon}
                <div className="text-base font-semibold sm:text-lg">
                    {title}
                </div>
                <p className="text-sm text-muted-foreground">{message}</p>
            </Card>
        </div>
    );
}
