'use client';

import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Calendar } from '@/components/ui/calendar';
import { ChevronDownIcon } from 'lucide-react';
import { cn } from '@/lib/utils';

interface AppPopoverCalendarProps {
    id?: string;
    label?: string;
    date?: Date | undefined;
    onChange: (date: Date | undefined) => void;
    placeholder?: string;
    required?: boolean;
    error?: string;
    className?: string;
}

export function AppPopoverCalendar({
    id,
    label,
    date,
    onChange,
    placeholder = 'Chọn ngày',
    required = false,
    error,
    className,
}: AppPopoverCalendarProps) {
    const [open, setOpen] = useState(false);

    return (
        <div className={cn('flex flex-col gap-3', className)}>
            {label && (
                <Label htmlFor={id}>
                    {label} {required && <span className="text-red-500">*</span>}
                </Label>
            )}
            <Popover open={open} onOpenChange={setOpen}>
                <PopoverTrigger asChild>
                    <Button
                        id={id}
                        variant="outline"
                        className={cn(
                            'w-full justify-between font-normal text-left',
                            !date && 'text-muted-foreground',
                        )}
                    >
                        {date
                            ? date.toLocaleDateString('vi-VN')
                            : placeholder}
                        <ChevronDownIcon className="h-4 w-4 opacity-50" />
                    </Button>
                </PopoverTrigger>
                <PopoverContent
                    className="w-auto overflow-hidden p-0"
                    align="start"
                >
                    <Calendar
                        mode="single"
                        selected={date}
                        captionLayout="dropdown"
                        onSelect={(d) => {
                            onChange(d);
                            setOpen(false);
                        }}
                    />
                </PopoverContent>
            </Popover>

            {error && <p className="text-sm text-red-500">{error}</p>}
        </div>
    );
}
