import { AppPasswordInput } from '@/components/app-password-input'
import InputError from '@/components/input-error'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import AuthLayout from '@/layouts/auth-layout'
import { store } from '@/routes/password/confirm'
import { Form, Head, usePage } from '@inertiajs/react'
import { LoaderCircle, ArrowLeft } from 'lucide-react'

export default function ConfirmPassword() {
  const { url } = usePage()

  return (
    <AuthLayout
      title="Xác nhận mật khẩu"
      description="Đây là lớp bảo mật của hệ thống. Vui lòng nhập mật khẩu của bạn để tiếp tục."
    >
      <Head title="Xác nhận mật khẩu" />

      <Form {...store.form()} resetOnSuccess={['password']}>
        {({ processing, errors }) => (
          <div className="space-y-6">
            {/* Input mật khẩu */}
            <div className="grid gap-2">
              <Label htmlFor="password">Mật khẩu</Label>
              <AppPasswordInput
                id="password"
                type="password"
                name="password"
                placeholder="Nhập mật khẩu của bạn"
                autoComplete="current-password"
                autoFocus
              />

              <InputError message={errors.password} />
            </div>

            {/* Nút hành động */}
            <div className="flex items-center gap-3">
              {/* 🔙 Nút quay lại */}
              <Button
                type="button"
                variant="outline"
                className="flex items-center gap-2 w-1/3"
                onClick={() => window.history.back()}
              >
                <ArrowLeft className="h-4 w-4" />
                Quay lại
              </Button>

              {/* ✅ Nút xác nhận */}
              <Button
                className="flex-1"
                disabled={processing}
                data-test="confirm-password-button"
              >
                {processing && (
                  <LoaderCircle className="h-4 w-4 animate-spin mr-1" />
                )}
                Xác nhận mật khẩu
              </Button>
            </div>
          </div>
        )}
      </Form>
    </AuthLayout>
  )
}
