'use client';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { motion } from 'framer-motion';
import { Check, Eye, EyeOff, X } from 'lucide-react';
import * as React from 'react';

// 💡 Interface chung
interface AppPasswordStrongInputProps
    extends React.InputHTMLAttributes<HTMLInputElement> {
    label?: string;
    error?: string;
    required?: boolean;
    confirmValue?: string; // 👉 Giá trị mật khẩu xác nhận
    showConfirmCheck?: boolean; // 👉 Bật/tắt kiểm tra khớp
    onValidityChange?: (valid: boolean, matched: boolean) => void; // 👉 Callback
}

// 🧠 Các tiêu chí kiểm tra mật khẩu mạnh
const validationRules = [
    { id: 'length', text: 'Ít nhất 8 ký tự', regex: /.{8,}/ },
    { id: 'number', text: 'Ít nhất 1 chữ số', regex: /\d/ },
    { id: 'lowercase', text: 'Ít nhất 1 chữ thường', regex: /[a-z]/ },
    { id: 'uppercase', text: 'Ít nhất 1 chữ hoa', regex: /[A-Z]/ },
    { id: 'special', text: 'Ít nhất 1 ký tự đặc biệt', regex: /[^A-Za-z0-9]/ },
];

// ✅ Item hiển thị điều kiện
const ValidationItem = ({
    isValid,
    text,
}: {
    isValid: boolean;
    text: string;
}) => (
    <li
        className={cn(
            'flex items-center text-sm transition-colors duration-300',
            isValid
                ? 'text-green-600 dark:text-green-400'
                : 'text-muted-foreground',
        )}
    >
        {isValid ? (
            <Check className="mr-2 h-4 w-4" strokeWidth={3} />
        ) : (
            <X className="mr-2 h-4 w-4" strokeWidth={3} />
        )}
        <span>{text}</span>
    </li>
);

export function AppPasswordStrongInput({
    className,
    label,
    error,
    required,
    id = 'password',
    name = 'password',
    placeholder = 'Nhập mật khẩu mạnh',
    onChange,
    confirmValue,
    showConfirmCheck = false,
    onValidityChange,
    ...props
}: AppPasswordStrongInputProps) {
    const [showPassword, setShowPassword] = React.useState(false);
    const [password, setPassword] = React.useState('');
    const [isPristine, setIsPristine] = React.useState(true);
    const [validationState, setValidationState] = React.useState<
        Record<string, boolean>
    >({});

    // ✅ Đánh giá độ mạnh & cập nhật callback
    React.useEffect(() => {
        if (password === '') {
            setIsPristine(true);
            setValidationState({});
            onValidityChange?.(false, false);
            return;
        }

        setIsPristine(false);
        const newValidation = Object.fromEntries(
            validationRules.map((rule) => [rule.id, rule.regex.test(password)]),
        );
        setValidationState(newValidation);

        const allValid = Object.values(newValidation).every(Boolean);
        const matched =
            showConfirmCheck &&
            confirmValue !== undefined &&
            confirmValue !== '' &&
            password === confirmValue;

        onValidityChange?.(allValid, matched);
    }, [password, confirmValue]);

    const togglePasswordVisibility = (e: React.MouseEvent) => {
        e.preventDefault();
        e.stopPropagation();
        setShowPassword((prev) => !prev);
    };

    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setPassword(e.target.value);
        onChange?.(e);
    };

    const isMatch =
        showConfirmCheck &&
        confirmValue !== undefined &&
        confirmValue !== '' &&
        password === confirmValue;

    return (
        <div className="w-full space-y-2">
            {/* Label */}
            {label && (
                <Label htmlFor={id}>
                    {label}
                    {required && <span className="text-red-500">*</span>}
                </Label>
            )}

            {/* Input + Eye */}
            <div className="relative">
                <Input
                    {...props}
                    id={id}
                    name={name}
                    type={showPassword ? 'text' : 'password'}
                    value={password}
                    onChange={handleInputChange}
                    placeholder={placeholder}
                    className={cn('pr-10', className)}
                />
                <Button
                    type="button"
                    variant="ghost"
                    size="icon"
                    className="absolute top-1/2 right-1 h-7 w-7 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                    onClick={togglePasswordVisibility}
                    tabIndex={-1}
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

            {/* Validation rules */}
            <div className="mt-4 space-y-3">
                <div className="flex items-center justify-between">
                    <h3 className="text-sm font-medium">Yêu cầu mật khẩu:</h3>
                    {isPristine && (
                        <p className="text-xs text-muted-foreground">
                            Nhập mật khẩu để kiểm tra
                        </p>
                    )}
                </div>
                <ul className="space-y-2">
                    {validationRules.map((rule) => (
                        <ValidationItem
                            key={rule.id}
                            isValid={!!validationState[rule.id]}
                            text={rule.text}
                        />
                    ))}

                    {/* ✅ Kiểm tra khớp mật khẩu */}
                    {showConfirmCheck && confirmValue !== '' && (
                        <li
                            className={cn(
                                'flex items-center text-sm transition-colors duration-300',
                                isMatch
                                    ? 'text-green-600 dark:text-green-400'
                                    : 'text-red-500 dark:text-red-400',
                            )}
                        >
                            {isMatch ? (
                                <Check
                                    className="mr-2 h-4 w-4"
                                    strokeWidth={3}
                                />
                            ) : (
                                <X className="mr-2 h-4 w-4" strokeWidth={3} />
                            )}
                            <span>
                                {isMatch
                                    ? 'Mật khẩu khớp nhau'
                                    : 'Mật khẩu xác nhận chưa khớp'}
                            </span>
                        </li>
                    )}
                </ul>
            </div>
        </div>
    );
}
