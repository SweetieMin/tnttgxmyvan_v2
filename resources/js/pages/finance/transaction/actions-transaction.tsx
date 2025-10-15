import { Calendar22 } from '@/components/app-date-picker';
import { Button } from '@/components/ui/button';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
    InputGroupText,
} from '@/components/ui/input-group';
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
import { type BreadcrumbItem } from '@/types';
import type { Transaction } from '@/types/academic';
import { soundToast } from '@/utils/sound-toast';
import { Head, router, useForm, usePage } from '@inertiajs/react';
import { ArrowLeft, DollarSign, FileText, Upload } from 'lucide-react';
import { useEffect, useState } from 'react';

const getBreadcrumbs = (
    mode: 'create' | 'edit',
    transactionTitle?: string,
): BreadcrumbItem[] => [
    { title: 'Tài chính', href: '' },
    { title: 'Giao dịch', href: '/finance/transactions' },
    {
        title:
            mode === 'create'
                ? 'Thêm mới'
                : `Chỉnh sửa: ${transactionTitle || ''}`,
        href: '',
    },
];

export interface Props {
    transaction?: Transaction;
    mode: 'create' | 'edit';
}

export default function ActionsTransaction({ transaction, mode }: Props) {
    const [selectedFiles, setSelectedFiles] = useState<File[]>([]);
    const [deletedFiles, setDeletedFiles] = useState<number[]>([]);

    const { flash } = usePage<{
        flash?: { success?: string; error?: string; message?: string };
    }>().props;

    useEffect(() => {
        if (flash?.success) soundToast('success', flash.success);
        else if (flash?.error) soundToast('error', flash.error);
        else if (flash?.message) soundToast('success', flash.message);
    }, [flash?.success, flash?.error, flash?.message]);

    const { data, setData, post, put, processing, errors, clearErrors } =
        useForm({
            transaction_date: transaction?.transaction_date || '',
            title: transaction?.title || '',
            description: transaction?.description || '',
            type: transaction?.type || 'expense',
            amount: transaction?.amount || 0,
            files: [] as File[],
            deleted_files: [] as number[],
        });

    // Initialize files for edit mode
    useEffect(() => {
        if (mode === 'edit' && transaction) {
            setSelectedFiles([]);
        }
    }, [mode, transaction]);

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('transaction_date', data.transaction_date);
        formData.append('title', data.title);
        formData.append('description', data.description || '');
        formData.append('type', data.type);
        formData.append('amount', data.amount.toString());

        selectedFiles.forEach((file) => {
            formData.append('files[]', file);
        });

        deletedFiles.forEach((id) => {
            formData.append('deleted_files[]', id.toString());
        });

        // Thêm _method nếu là chế độ chỉnh sửa
        if (mode === 'edit') {
            formData.append('_method', 'PUT');
        }

        // 🧠 Fix TypeScript warning bằng cách kiểm tra rõ ràng
        const url =
            mode === 'edit' && transaction
                ? `/finance/transactions/${transaction.id}`
                : '/finance/transactions';

        router.post(url, formData, {
            onError: (errors) => {
                console.error('Validation errors:', errors);
            },
            onSuccess: () => {
                // Không cần router.visit()
                // Laravel redirect -> flash tự hiển thị
            },
            forceFormData: true,
        });
    };

    const handleCancel = () => {
        router.visit('/finance/transactions');
    };

    // File handling functions
    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        setSelectedFiles(file ? [file] : []);
    };

    const handleRemoveFile = (index: number) => {
        setSelectedFiles((prev) => prev.filter((_, i) => i !== index));
    };

    const handleDeleteExistingFile = (fileId: number) => {
        setDeletedFiles((prev) => [...prev, fileId]);
    };

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
        }).format(amount);
    };

    return (
        <AppLayout breadcrumbs={getBreadcrumbs(mode, transaction?.title)}>
            <Head
                title={
                    mode === 'create'
                        ? 'Thêm giao dịch mới'
                        : `Chỉnh sửa giao dịch: ${transaction?.title}`
                }
            />

            <div className="px-4 py-6">
                {/* Header */}
                <div className="mb-6">
                    <div className="mb-4 flex items-center gap-4">
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={handleCancel}
                            className="flex items-center gap-2"
                        >
                            <ArrowLeft className="h-4 w-4" />
                            Quay lại
                        </Button>
                    </div>
                    <h1 className="text-2xl font-bold">
                        {mode === 'create'
                            ? 'Thêm giao dịch mới'
                            : `Chỉnh sửa giao dịch: ${transaction?.title}`}
                    </h1>
                    <p className="text-muted-foreground">
                        {mode === 'create'
                            ? 'Nhập thông tin để tạo giao dịch mới trong hệ thống'
                            : 'Cập nhật thông tin giao dịch hiện tại'}
                    </p>
                </div>

                <form
                    onSubmit={handleSubmit}
                    className="space-y-6"
                    encType="multipart/form-data"
                >
                    {/* Basic Information */}
                    <div className="rounded-lg border bg-card p-6">
                        <h2 className="mb-4 text-lg font-semibold">
                            Thông tin giao dịch
                        </h2>
                        <div className="grid grid-cols-1 gap-6 lg:grid-cols-5">
                            {/* Cột trái: Ngày giao dịch, Loại, Số tiền - 1 phần */}
                            <div className="space-y-4 lg:col-span-1">
                                <div className="space-y-2">
                                    <Label htmlFor="transaction_date">
                                        Ngày *
                                    </Label>
                                    <Calendar22
                                        date={
                                            data.transaction_date
                                                ? new Date(
                                                      data.transaction_date,
                                                  )
                                                : undefined
                                        }
                                        onDateChange={(date) =>
                                            setData(
                                                'transaction_date',
                                                date
                                                    ? date
                                                          .toISOString()
                                                          .split('T')[0]
                                                    : '',
                                            )
                                        }
                                        placeholder="Chọn ngày giao dịch"
                                    />
                                    {errors.transaction_date && (
                                        <p className="text-sm text-red-500">
                                            {errors.transaction_date}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="type">Thu/Chi *</Label>
                                    <Select
                                        value={data.type}
                                        onValueChange={(value) =>
                                            setData(
                                                'type',
                                                value as 'income' | 'expense',
                                            )
                                        }
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Chọn loại giao dịch" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="income">
                                                Thu
                                            </SelectItem>
                                            <SelectItem value="expense">
                                                Chi
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    {errors.type && (
                                        <p className="text-sm text-red-500">
                                            {errors.type}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="amount">Số tiền *</Label>
                                    <InputGroup>
                                        <InputGroupInput
                                            id="amount"
                                            type="number"
                                            min="0"
                                            value={data.amount}
                                            onChange={(e) =>
                                                setData(
                                                    'amount',
                                                    parseInt(e.target.value) ||
                                                        0,
                                                )
                                            }
                                            placeholder="0"
                                        />
                                        <InputGroupAddon>
                                            <InputGroupText>
                                                <DollarSign className="h-4 w-4" />
                                            </InputGroupText>
                                        </InputGroupAddon>
                                    </InputGroup>
                                    {data.amount > 0 && (
                                        <p className="text-sm text-muted-foreground">
                                            {formatCurrency(data.amount)}
                                        </p>
                                    )}
                                    {errors.amount && (
                                        <p className="text-sm text-red-500">
                                            {errors.amount}
                                        </p>
                                    )}
                                </div>
                            </div>

                            {/* Cột phải: Tiêu đề và Mô tả - 4 phần */}
                            <div className="space-y-4 lg:col-span-4">
                                <div className="space-y-2">
                                    <Label htmlFor="title">Hạng mục *</Label>
                                    <InputGroup>
                                        <InputGroupInput
                                            id="title"
                                            value={data.title}
                                            onChange={(e) =>
                                                setData('title', e.target.value)
                                            }
                                            placeholder="VD: Mua sắm tháng 10"
                                        />
                                        <InputGroupAddon>
                                            <InputGroupText>
                                                <FileText className="h-4 w-4" />
                                            </InputGroupText>
                                        </InputGroupAddon>
                                    </InputGroup>
                                    {errors.title && (
                                        <p className="text-sm text-red-500">
                                            {errors.title}
                                        </p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="description">Mô tả</Label>
                                    <textarea
                                        id="description"
                                        className="flex min-h-[120px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                        value={data.description}
                                        onChange={(e) =>
                                            setData(
                                                'description',
                                                e.target.value,
                                            )
                                        }
                                        placeholder="Nhập mô tả chi tiết về giao dịch..."
                                    />
                                    {errors.description && (
                                        <p className="text-sm text-red-500">
                                            {errors.description}
                                        </p>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* File Management Section */}
                    <div className="rounded-lg border bg-card p-6">
                        <div className="space-y-4">
                            <div>
                                <h2 className="text-lg font-semibold">
                                    Bảng chi tiết đính kèm
                                </h2>
                                <p className="text-sm text-muted-foreground">
                                    Tải lên các file PDF liên quan đến giao dịch
                                    (tối đa 10MB)
                                </p>
                            </div>

                            {/* File Upload */}
                            <div className="space-y-4">
                                <div className="flex items-center gap-4">
                                    <Label
                                        htmlFor="files"
                                        className="cursor-pointer"
                                    >
                                        <div className="flex items-center gap-2 rounded-lg border border-dashed border-muted-foreground/25 px-4 py-2 transition-colors hover:border-muted-foreground/50">
                                            <Upload className="h-4 w-4" />
                                            <span>Chọn file PDF</span>
                                        </div>
                                    </Label>
                                    <input
                                        id="files"
                                        type="file"
                                        accept=".pdf"
                                        onChange={handleFileChange}
                                        className="hidden"
                                    />
                                </div>

                                {/* Selected Files */}
                                {selectedFiles.length > 0 && (
                                    <div className="space-y-2">
                                        <h4 className="text-sm font-medium">
                                            File đã chọn:
                                        </h4>
                                        {selectedFiles.map((file, index) => (
                                            <div
                                                key={index}
                                                className="flex items-center justify-between rounded-md bg-muted/50 p-2"
                                            >
                                                <span className="text-sm">
                                                    {file.name}
                                                </span>
                                                <Button
                                                    type="button"
                                                    variant="ghost"
                                                    size="sm"
                                                    onClick={() =>
                                                        handleRemoveFile(index)
                                                    }
                                                    className="text-red-500 hover:text-red-700"
                                                >
                                                    Xóa
                                                </Button>
                                            </div>
                                        ))}
                                    </div>
                                )}

                                {/* Existing File (for edit mode) */}
                                {/* Existing File (for edit mode) */}
                                {mode === 'edit' &&
                                    transaction?.file_name &&
                                    !deletedFiles.includes(transaction.id) && (
                                        <div className="space-y-2">
                                            <h4 className="text-sm font-medium">
                                                File hiện có:
                                            </h4>
                                            <div className="flex items-center justify-between rounded-md bg-muted/50 p-2">
                                                <div className="flex items-center gap-2">
                                                    <FileText className="h-4 w-4 text-muted-foreground" />
                                                    <a
                                                        href={`/storage/transactions/${transaction.file_name}`}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="text-sm text-blue-600 hover:text-blue-800 hover:underline"
                                                    >
                                                        Xem file
                                                    </a>
                                                </div>
                                                <Button
                                                    type="button"
                                                    variant="ghost"
                                                    size="sm"
                                                    onClick={() => {
                                                        // chỉ đánh dấu cần xóa, chưa xóa thật
                                                        setDeletedFiles(
                                                            (prev) => [
                                                                ...prev,
                                                                transaction.id,
                                                            ],
                                                        );
                                                    }}
                                                    className="text-red-500 hover:text-red-700"
                                                >
                                                    Xóa
                                                </Button>
                                            </div>
                                        </div>
                                    )}

                                {/* Nếu người dùng đã chọn xóa nhưng chưa cập nhật */}
                                {mode === 'edit' &&
                                    deletedFiles.includes(
                                        transaction?.id ?? 0,
                                    ) && (
                                        <div className="rounded-md bg-yellow-50 p-2 text-sm text-yellow-700">
                                            File này sẽ bị xóa khi bạn nhấn{' '}
                                            <b>Cập nhật</b>.
                                        </div>
                                    )}

                                {/* File validation errors */}
                                {errors.files && (
                                    <div className="rounded-md bg-red-50 p-2 text-sm text-red-500">
                                        {errors.files}
                                    </div>
                                )}
                                {(errors as any)['files.*'] && (
                                    <div className="rounded-md bg-red-50 p-2 text-sm text-red-500">
                                        {(errors as any)['files.*']}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    <Separator />
                    <div className="flex justify-end gap-3">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={handleCancel}
                        >
                            Hủy
                        </Button>
                        <Button type="submit" disabled={processing}>
                            {processing
                                ? mode === 'create'
                                    ? 'Đang tạo...'
                                    : 'Đang cập nhật...'
                                : mode === 'create'
                                  ? 'Tạo giao dịch'
                                  : 'Cập nhật giao dịch'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
