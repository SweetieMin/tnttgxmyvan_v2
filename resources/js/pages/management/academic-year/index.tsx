import AppLayout from '@/layouts/app-layout';
import { index as academic_years } from '@/routes/management/academic-years';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { Button } from '@/components/ui/button';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Trash2, Edit, Plus } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Niên khóa',
        href: academic_years().url,
    },
];

export interface AcademicYear {
    id: number;
    name: string;
    catechism_start_date: string;
    catechism_end_date: string;
    catechism_avg_score: number;
    catechism_training_score: number;
    activity_start_date: string;
    activity_end_date: string;
    activity_score: number;
    status_academic: string;
  }
  
  // Props truyền vào component (dữ liệu từ Inertia)
  export interface Props {
    years: AcademicYear[];
    flash?: {
      success?: string;
      error?: string;
    };
  }

export default function Index({ years, flash }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const [editingAcademicYear, setEditingAcademicYear] = useState<AcademicYear | null>(null);
    const [showToast, setShowToast] = useState(false);
    const [toastMessage, setToastMessage] = useState('');
    const [toastType, setToastType] = useState<'success' | 'error'>('success');

    useEffect(() => {
        if(flash?.success){
            setToastMessage(flash.success);
            setToastType('success');
            setShowToast(true);
        } else if(flash?.error){
            setToastMessage(flash.error);
            setToastType('error');
            setShowToast(true);
        }

    }, [flash]);

    useEffect(() => {
        if(showToast){
            const timeout = setTimeout(() => {
                setShowToast(false);
            }, 3000);
            return () => clearTimeout(timeout);
        }
    }, [showToast]);

    const {data, setData, post, put, processing, errors, reset, delete: destroy} = useForm({
        id: 0,
        name: '',
        catechism_start_date: '',
        catechism_end_date: '',
        catechism_avg_score: 0,
        catechism_training_score: 0,
        activity_start_date: '',
        activity_end_date: '',
        activity_score: 0,
        status_academic: '',
    });

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        
        if (editingAcademicYear){
            put(`/management/academic-years/${editingAcademicYear.id}`, {
                onSuccess: () => {
                    setIsOpen(false);
                    reset();
                    setEditingAcademicYear(null);
                }
            });
        } else {
            post('/management/academic-years', {
                onSuccess: () => {
                    setIsOpen(false);
                    reset();
                }
            });
        }
    };

    const handleEdit = (year: AcademicYear) => {
        setEditingAcademicYear(year);
        setData({
            id: year.id,
            name: year.name,
            catechism_start_date: year.catechism_start_date ? new Date(year.catechism_start_date).toISOString().split('T')[0] : '',
            catechism_end_date: year.catechism_end_date ? new Date(year.catechism_end_date).toISOString().split('T')[0] : '',
            catechism_avg_score: year.catechism_avg_score,
            catechism_training_score: year.catechism_training_score,
            activity_start_date: year.activity_start_date ? new Date(year.activity_start_date).toISOString().split('T')[0] : '',
            activity_end_date: year.activity_end_date ? new Date(year.activity_end_date).toISOString().split('T')[0] : '',
            activity_score: year.activity_score,
            status_academic: year.status_academic,
        });
        setIsOpen(true);
    };

    const handleDelete = (yearId: number) => {
        destroy(`/management/academic-years/${yearId}`);
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Niên khoá" />
            
            <div className="space-y-6 mt-4 mx-4">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold">Quản lý Niên khóa</h1>
                        <p className="text-muted-foreground">Quản lý các niên khóa giáo lý và sinh hoạt</p>
                    </div>
                    <Dialog open={isOpen} onOpenChange={setIsOpen}>
                        <DialogTrigger asChild>
                            <Button>
                                <Plus className="h-4 w-4 mr-2" />
                                Thêm niên khóa
                            </Button>
                        </DialogTrigger>
                        <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
                            <DialogHeader>
                                <DialogTitle>
                                    {editingAcademicYear ? 'Chỉnh sửa niên khóa' : 'Thêm niên khóa mới'}
                                </DialogTitle>
                                <DialogDescription>
                                    {editingAcademicYear 
                                        ? 'Cập nhật thông tin niên khóa hiện tại' 
                                        : 'Nhập thông tin để tạo niên khóa mới'
                                    }
                                </DialogDescription>
                            </DialogHeader>
                            
                            <form onSubmit={handleSubmit} className="space-y-4">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div className="space-y-2">
                                        <Label htmlFor="name">Tên niên khóa *</Label>
                                        <Input
                                            id="name"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            placeholder="VD: Niên khóa 2024-2025"
                                            required
                                        />
                                        {errors.name && <p className="text-sm text-red-500">{errors.name}</p>}
                                    </div>
                                    
                                    <div className="space-y-2">
                                        <Label htmlFor="status_academic">Trạng thái</Label>
                                        <Select value={data.status_academic} onValueChange={(value) => setData('status_academic', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Chọn trạng thái" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="upcoming">Sắp diễn ra</SelectItem>
                                                <SelectItem value="ongoing">Đang diễn ra</SelectItem>
                                                <SelectItem value="finished">Đã kết thúc</SelectItem>
                                            </SelectContent>
                                        </Select>
                                        {errors.status_academic && <p className="text-sm text-red-500">{errors.status_academic}</p>}
                                    </div>
                                </div>

                                <div className="space-y-4">
                                    <h3 className="text-lg font-semibold">Thời gian Giáo lý</h3>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div className="space-y-2">
                                            <Label htmlFor="catechism_start_date">Ngày bắt đầu *</Label>
                                            <Input
                                                id="catechism_start_date"
                                                type="date"
                                                value={data.catechism_start_date}
                                                onChange={(e) => setData('catechism_start_date', e.target.value)}
                                                required
                                            />
                                            {errors.catechism_start_date && <p className="text-sm text-red-500">{errors.catechism_start_date}</p>}
                                        </div>
                                        
                                        <div className="space-y-2">
                                            <Label htmlFor="catechism_end_date">Ngày kết thúc *</Label>
                                            <Input
                                                id="catechism_end_date"
                                                type="date"
                                                value={data.catechism_end_date}
                                                onChange={(e) => setData('catechism_end_date', e.target.value)}
                                                required
                                            />
                                            {errors.catechism_end_date && <p className="text-sm text-red-500">{errors.catechism_end_date}</p>}
                                        </div>
                                    </div>
                                </div>

                                <div className="space-y-4">
                                    <h3 className="text-lg font-semibold">Thời gian Sinh hoạt</h3>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div className="space-y-2">
                                            <Label htmlFor="activity_start_date">Ngày bắt đầu *</Label>
                                            <Input
                                                id="activity_start_date"
                                                type="date"
                                                value={data.activity_start_date}
                                                onChange={(e) => setData('activity_start_date', e.target.value)}
                                                required
                                            />
                                            {errors.activity_start_date && <p className="text-sm text-red-500">{errors.activity_start_date}</p>}
                                        </div>
                                        
                                        <div className="space-y-2">
                                            <Label htmlFor="activity_end_date">Ngày kết thúc *</Label>
                                            <Input
                                                id="activity_end_date"
                                                type="date"
                                                value={data.activity_end_date}
                                                onChange={(e) => setData('activity_end_date', e.target.value)}
                                                required
                                            />
                                            {errors.activity_end_date && <p className="text-sm text-red-500">{errors.activity_end_date}</p>}
                                        </div>
                                    </div>
                                </div>

                                <div className="space-y-4">
                                    <h3 className="text-lg font-semibold">Điểm số</h3>
                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div className="space-y-2">
                                            <Label htmlFor="catechism_avg_score">Điểm TB Giáo lý *</Label>
                                            <Input
                                                id="catechism_avg_score"
                                                type="number"
                                                min="0"
                                                max="10"
                                                step="0.1"
                                                value={data.catechism_avg_score}
                                                onChange={(e) => setData('catechism_avg_score', parseFloat(e.target.value) || 0)}
                                                required
                                            />
                                            {errors.catechism_avg_score && <p className="text-sm text-red-500">{errors.catechism_avg_score}</p>}
                                        </div>
                                        
                                        <div className="space-y-2">
                                            <Label htmlFor="catechism_training_score">Điểm ĐT Giáo lý *</Label>
                                            <Input
                                                id="catechism_training_score"
                                                type="number"
                                                min="0"
                                                max="10"
                                                step="0.1"
                                                value={data.catechism_training_score}
                                                onChange={(e) => setData('catechism_training_score', parseFloat(e.target.value) || 0)}
                                                required
                                            />
                                            {errors.catechism_training_score && <p className="text-sm text-red-500">{errors.catechism_training_score}</p>}
                                        </div>
                                        
                                        <div className="space-y-2">
                                            <Label htmlFor="activity_score">Điểm Sinh hoạt *</Label>
                                            <Input
                                                id="activity_score"
                                                type="number"
                                                min="0"
                                                max="1000"
                                                value={data.activity_score}
                                                onChange={(e) => setData('activity_score', parseInt(e.target.value) || 0)}
                                                required
                                            />
                                            {errors.activity_score && <p className="text-sm text-red-500">{errors.activity_score}</p>}
                                        </div>
                                    </div>
                                </div>

                                <div className="flex justify-end space-x-2 pt-4">
                                    <Button type="button" variant="outline" onClick={() => setIsOpen(false)}>
                                        Hủy
                                    </Button>
                                    <Button type="submit" disabled={processing}>
                                        {processing ? 'Đang xử lý...' : (editingAcademicYear ? 'Cập nhật' : 'Tạo mới')}
                                    </Button>
                                </div>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>

                {/* Toast Notification */}
                {showToast && (
                    <div className={`fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg ${
                        toastType === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                    }`}>
                        {toastMessage}
                    </div>
                )}

                {/* Academic Years Table */}
                {years.length === 0 ? (
                    <Card>
                        <CardContent className="flex flex-col items-center justify-center py-12">
                            <div className="text-center">
                                <h3 className="text-lg font-semibold text-muted-foreground">Chưa có niên khóa nào</h3>
                                <p className="text-sm text-muted-foreground mt-2">Hãy tạo niên khóa đầu tiên để bắt đầu</p>
                            </div>
                        </CardContent>
                    </Card>
                ) : (
                    <Card>
                        <CardContent className="p-0">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead className="w-[200px]">Tên niên khóa</TableHead>
                                        <TableHead className="w-[120px]">Trạng thái</TableHead>
                                        <TableHead className="w-[150px]">Giáo lý</TableHead>
                                        <TableHead className="w-[150px]">Sinh hoạt</TableHead>
                                        <TableHead className="w-[100px] text-center">Điểm TB</TableHead>
                                        <TableHead className="w-[100px] text-center">Điểm ĐT</TableHead>
                                        <TableHead className="w-[100px] text-center">Điểm SH</TableHead>
                                        <TableHead className="w-[120px] text-center">Thao tác</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {years.map((year) => (
                                        <TableRow key={year.id}>
                                            <TableCell className="font-medium">
                                                {year.name}
                                            </TableCell>
                                            <TableCell>
                                                <Badge variant={
                                                    year.status_academic === 'upcoming' ? 'secondary' :
                                                    year.status_academic === 'ongoing' ? 'default' : 'outline'
                                                }>
                                                    {year.status_academic === 'upcoming' ? 'Sắp diễn ra' :
                                                     year.status_academic === 'ongoing' ? 'Đang diễn ra' : 'Đã kết thúc'}
                                                </Badge>
                                            </TableCell>
                                            <TableCell>
                                                <div className="text-sm">
                                                    <div className="font-medium">
                                                        {new Date(year.catechism_start_date).toLocaleDateString('vi-VN')}
                                                    </div>
                                                    <div className="text-muted-foreground">
                                                        đến {new Date(year.catechism_end_date).toLocaleDateString('vi-VN')}
                                                    </div>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="text-sm">
                                                    <div className="font-medium">
                                                        {new Date(year.activity_start_date).toLocaleDateString('vi-VN')}
                                                    </div>
                                                    <div className="text-muted-foreground">
                                                        đến {new Date(year.activity_end_date).toLocaleDateString('vi-VN')}
                                                    </div>
                                                </div>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className="font-medium">{year.catechism_avg_score}</span>
                                                <span className="text-muted-foreground text-xs">/10</span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className="font-medium">{year.catechism_training_score}</span>
                                                <span className="text-muted-foreground text-xs">/10</span>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <span className="font-medium">{year.activity_score}</span>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex justify-center space-x-2">
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                        onClick={() => handleEdit(year)}
                                                    >
                                                        <Edit className="h-4 w-4" />
                                                    </Button>
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                        onClick={() => handleDelete(year.id)}
                                                    >
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppLayout>
    );
}
