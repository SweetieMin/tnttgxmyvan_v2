'use client'

import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Separator } from '@/components/ui/separator'
import AppLayout from '@/layouts/app-layout'
import { type BreadcrumbItem } from '@/types'
import type { AcademicYear, Regulation, Role } from '@/types/academic'
import { Head, useForm, router } from '@inertiajs/react'
import { ArrowLeft, Search } from 'lucide-react'
import { useEffect, useState } from 'react'
import { Checkbox } from '@/components/ui/checkbox'
import { index as regulations } from '@/routes/management/regulations'

// ✅ Dynamic breadcrumb
const getBreadcrumbs = (mode: 'create' | 'edit', regulationName?: string): BreadcrumbItem[] => [
  { title: 'Quản lý', href: '' },
  { title: 'Nội quy', href: regulations().url },
  {
    title: mode === 'create'
      ? 'Thêm mới'
      : `Chỉnh sửa: ${regulationName ? regulationName : ''}`,
    href: '',
  },
]

export interface Props {
  regulation?: Regulation
  academicYears?: AcademicYear[]
  allRoles?: Role[]
  regulationRoles?: { role_id: number; role: Role }[]
  currentAcademicYearId?: number
}

export default function ActionsRegulationIndex({
  regulation,
  academicYears = [],
  allRoles = [],
  regulationRoles = [],
  currentAcademicYearId,
}: Props) {
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false)
  const [selectedRoles, setSelectedRoles] = useState<Set<number>>(new Set())
  const [searchTerm, setSearchTerm] = useState('')

  const mode: 'create' | 'edit' = regulation ? 'edit' : 'create'

  const { data, setData, post, put, delete: destroy, processing, errors } = useForm({
    academic_year_id: currentAcademicYearId || 0,
    description: '',
    type: 'plus',
    points: 1,
    ordering: 1,
    role_ids: [] as number[],
  })

  // Khi edit: load dữ liệu
  useEffect(() => {
    if (mode === 'edit' && regulation) {
      const ids = regulationRoles.map((r) => r.role_id)
      setSelectedRoles(new Set(ids))
      setData({
        academic_year_id: regulation.academic_year_id,
        description: regulation.description,
        type: regulation.type,
        points: regulation.points,
        ordering: regulation.ordering,
        role_ids: ids,
      })
    }
  }, [regulation, regulationRoles])

  // Khi tạo mới: gán niên khóa mặc định
  useEffect(() => {
    if (mode === 'create' && currentAcademicYearId) {
      setData('academic_year_id', currentAcademicYearId)
    }
  }, [currentAcademicYearId, mode])

  // Gửi form
  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault()
    const payload = { ...data, role_ids: Array.from(selectedRoles) }

    if (mode === 'edit' && regulation) {
      put(`/management/regulations/${regulation.id}`, {
        onSuccess: () => {
          router.visit('/management/regulations')
        },
        onError: (errors: any) => {
          console.error('Validation errors:', errors)
        }
      })
    } else {
      post(`/management/regulations`, {
        onSuccess: () => {
          router.visit('/management/regulations')
        },
        onError: (errors: any) => {
          console.error('Validation errors:', errors)
        }
      })
    }
  }

  const handleDelete = () => {
    if (regulation) {
      destroy(`/management/regulations/${regulation.id}`, {
        onSuccess: () => router.visit('/management/regulations'),
      })
    }
  }

  const handleBack = () => router.visit('/management/regulations')

  // Toggle role
  const handleRoleToggle = (roleId: number, checked: boolean) => {
    const updated = new Set(selectedRoles)
    checked ? updated.add(roleId) : updated.delete(roleId)
    setSelectedRoles(updated)
    setData('role_ids', Array.from(updated))
  }

  // Chọn tất cả / bỏ chọn tất cả
  const handleSelectAll = () => {
    const filtered = allRoles.filter(
      (r) =>
        r.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        r.description?.toLowerCase().includes(searchTerm.toLowerCase())
    )
    const allIds = filtered.map((r) => r.id)
    setSelectedRoles(new Set(allIds))
    setData('role_ids', allIds)
  }

  const handleSelectNone = () => {
    setSelectedRoles(new Set())
    setData('role_ids', [])
  }

  // Lọc theo từ khóa
  const filteredRoles = allRoles.filter(
    (r) =>
      r.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      r.description?.toLowerCase().includes(searchTerm.toLowerCase())
  )

  const groupRolesIntoRows = (roles: Role[], perRow = 4) => {
    const rows: Role[][] = []
    for (let i = 0; i < roles.length; i += perRow) {
      rows.push(roles.slice(i, i + perRow))
    }
    return rows
  }

  const roleRows = groupRolesIntoRows(filteredRoles, 4)

  return (
    <AppLayout breadcrumbs={getBreadcrumbs(mode, regulation?.description)}>
      <Head title={mode === 'edit' ? 'Chỉnh sửa nội quy' : 'Thêm nội quy mới'} />

      <div className="px-4 py-6">
        {/* Header */}
        <div className="mb-6">
          <div className="flex items-center gap-4 mb-4">
            <Button
              variant="outline"
              size="sm"
              onClick={handleBack}
              className="flex items-center gap-2"
            >
              <ArrowLeft className="h-4 w-4" />
              Quay lại
            </Button>
          </div>

          <h1 className="text-2xl font-bold">
            {mode === 'edit' ? 'Chỉnh sửa nội quy' : 'Thêm nội quy mới'}
          </h1>
          <p className="text-muted-foreground">
            {mode === 'edit'
              ? 'Cập nhật thông tin nội quy hiện tại'
              : 'Nhập thông tin để tạo nội quy mới trong hệ thống'}
          </p>
        </div>

        {/* Form */}
        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Thông tin cơ bản */}
          <div className="rounded-lg border bg-card p-6">
            <h2 className="text-lg font-semibold mb-4">Thông tin cơ bản</h2>

            {/* Niên khóa + loại */}
            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div className="space-y-2">
                <Label htmlFor="academic_year_id">Niên khóa *</Label>
                <Select
                  value={data.academic_year_id?.toString() ?? ''}
                  onValueChange={(v) => setData('academic_year_id', parseInt(v))}
                >
                  <SelectTrigger className="w-full">
                    <SelectValue placeholder="Chọn niên khóa" />
                  </SelectTrigger>
                  <SelectContent>
                    {academicYears.map((y) => (
                      <SelectItem key={y.id} value={y.id.toString()}>
                        {y.name}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
                {(errors.academic_year_id ) && (
                  <p className="text-sm text-red-500 mt-1">
                    {errors.academic_year_id }
                  </p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="type">Loại nội quy *</Label>
                <Select
                  value={data.type}
                  onValueChange={(v) => setData('type', v as 'plus' | 'minus')}
                >
                  <SelectTrigger className="w-full">
                    <SelectValue placeholder="Chọn loại" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="plus">Cộng điểm</SelectItem>
                    <SelectItem value="minus">Trừ điểm</SelectItem>
                  </SelectContent>
                </Select>
                {(errors.type ) && (
                  <p className="text-sm text-red-500 mt-1">
                    {errors.type }
                  </p>
                )}
              </div>
            </div>

            {/* Điểm số + thứ tự */}
            <div className="grid grid-cols-1 gap-4 md:grid-cols-2 mt-4">
              <div className="space-y-2">
                <Label htmlFor="points">Điểm số *</Label>
                <Input
                  id="points"
                  type="number"
                  min="1"
                  max="100"
                  value={data.points}
                  onChange={(e) =>
                    setData('points', parseInt(e.target.value) || 1)
                  }
                />
                {(errors.points ) && (
                  <p className="text-sm text-red-500 mt-1">
                    {errors.points }
                  </p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="ordering">Thứ tự *</Label>
                <Input
                  id="ordering"
                  type="number"
                  min="1"
                  value={data.ordering}
                  onChange={(e) =>
                    setData('ordering', parseInt(e.target.value) || 1)
                  }
                />
                {(errors.ordering ) && (
                  <p className="text-sm text-red-500 mt-1">
                    {errors.ordering }
                  </p>
                )}
              </div>
            </div>

            {/* Nội dung */}
            <div className="space-y-2 mt-4">
              <Label htmlFor="description">Nội dung nội quy *</Label>
              <textarea
                id="description"
                className="flex min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                value={data.description}
                onChange={(e) => setData('description', e.target.value)}
                placeholder="Nhập nội dung nội quy..."
              />
              {(errors.description ) && (
                <p className="text-sm text-red-500 mt-1">
                  {errors.description }
                </p>
              )}
            </div>
          </div>

          {/* Vai trò áp dụng */}
          <div className="rounded-lg border bg-card p-6">
            <div className="space-y-4">
              <div>
                <h2 className="text-lg font-semibold">Vai trò áp dụng</h2>
                <p className="text-sm text-muted-foreground">
                  Chọn các vai trò sẽ áp dụng nội quy này
                </p>
              </div>

              {/* Tìm kiếm + chọn nhanh */}
              <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div className="flex items-center gap-2">
                  <Search className="h-4 w-4 text-muted-foreground" />
                  <Input
                    placeholder="Tìm kiếm vai trò..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="max-w-sm"
                  />
                </div>
                <div className="flex items-center gap-2">
                  <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    onClick={handleSelectAll}
                  >
                    Chọn tất cả
                  </Button>
                  <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    onClick={handleSelectNone}
                  >
                    Bỏ chọn tất cả
                  </Button>
                </div>
              </div>

              {/* Grid vai trò */}
              <div className="space-y-4">
                {roleRows.length === 0 ? (
                  <div className="text-center py-8 text-muted-foreground">
                    {searchTerm
                      ? 'Không tìm thấy vai trò nào phù hợp'
                      : 'Không có vai trò nào để chọn'}
                  </div>
                ) : (
                  roleRows.map((row, rowIndex) => (
                    <div
                      key={rowIndex}
                      className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3"
                    >
                      {row.map((r) => {
                        const isSelected = selectedRoles.has(r.id)
                        return (
                          <div
                            key={r.id}
                            className={`flex items-center space-x-3 p-3 rounded-lg border transition-colors ${
                              isSelected
                                ? 'bg-primary/5 border-primary/20'
                                : 'hover:bg-muted/30'
                            }`}
                          >
                            <Checkbox
                              id={`role-${r.id}`}
                              checked={isSelected}
                              onCheckedChange={(checked) =>
                                handleRoleToggle(r.id, !!checked)
                              }
                            />
                            <div className="flex-1 min-w-0">
                              <Label
                                htmlFor={`role-${r.id}`}
                                className="font-medium cursor-pointer text-sm"
                              >
                                {r.name}
                              </Label>
                              {r.description && (
                                <p className="text-xs text-muted-foreground mt-1 line-clamp-2">
                                  {r.description}
                                </p>
                              )}
                              <p className="text-xs text-muted-foreground mt-1">
                                Thứ tự: {r.ordering}
                              </p>
                            </div>
                          </div>
                        )
                      })}
                    </div>
                  ))
                )}
              </div>

              {errors.role_ids && (
                <p className="text-sm text-red-500 mt-2">{errors.role_ids}</p>
              )}

              <div className="text-sm text-muted-foreground bg-muted/50 p-3 rounded-md">
                ✓ Đã chọn {selectedRoles.size} vai trò
              </div>
            </div>
          </div>

          <Separator />

          {/* Buttons */}
          <div className="flex justify-end gap-3">
            <Button type="button" variant="outline" onClick={handleBack}>
              Hủy
            </Button>
            <Button type="submit" disabled={processing}>
              {processing
                ? mode === 'edit'
                  ? 'Đang cập nhật...'
                  : 'Đang tạo...'
                : mode === 'edit'
                ? 'Cập nhật'
                : 'Tạo mới'}
            </Button>
          </div>
        </form>
      </div>

      {/* Dialog Xóa */}
      <AlertDialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Xác nhận xóa nội quy</AlertDialogTitle>
            <AlertDialogDescription>
              Bạn có chắc chắn muốn xóa nội quy này? Hành động này không thể hoàn tác.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Hủy</AlertDialogCancel>
            <AlertDialogAction
              onClick={handleDelete}
              className="bg-red-600 hover:bg-red-700"
            >
              Xóa
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </AppLayout>
  )
}
