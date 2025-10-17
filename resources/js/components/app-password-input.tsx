'use client';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { motion } from 'framer-motion';
import { Eye, EyeOff } from 'lucide-react';
import * as React from 'react';

interface AppPasswordInputProps
    extends React.InputHTMLAttributes<HTMLInputElement> {
    label?: string;
    error?: string;
    required?: boolean;
}

export function AppPasswordInput({
    className,
    label,
    error,
    required,
    ...props
}: AppPasswordInputProps) {
    const [showPassword, setShowPassword] = React.useState(false);

    return (
        <div className="w-full space-y-2">
            {/* Label */}
            {label && (
                <Label htmlFor={props.id}>
                    {label}{' '}
                    {required && <span className="text-red-500">*</span>}
                </Label>
            )}

            {/* Input + Eye icon */}
            <div className="relative">
                <Input
                    {...props}
                    type={showPassword ? 'text' : 'password'}
                    className={cn('pr-10', className)}
                />
                <Button
                    type="button"
                    variant="ghost"
                    size="icon"
                    className="absolute top-1/2 right-1 h-7 w-7 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                    onClick={(e) => {
                        e.preventDefault(); // ✅ Ngăn form submit
                        e.stopPropagation(); // ✅ Ngăn lan sự kiện
                        setShowPassword(!showPassword);
                    }}
                >
                    <motion.div
                        key={showPassword ? 'on' : 'off'}
                        initial={{ opacity: 0, y: 2 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.15 }}
                    >
                        {showPassword ? (
                            <EyeOff className="h-4 w-4" />
                        ) : (
                            <Eye className="h-4 w-4" />
                        )}
                    </motion.div>
                </Button>
            </div>

            {/* Error message */}
            {error && <p className="text-sm text-red-500">{error}</p>}
        </div>
    );
}
