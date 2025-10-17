'use client'

import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController'
import InputError from '@/components/input-error'
import AppLayout from '@/layouts/app-layout'
import SettingsLayout from '@/layouts/settings/layout'
import { type BreadcrumbItem } from '@/types'
import { Transition } from '@headlessui/react'
import { Form, Head } from '@inertiajs/react'
import { useRef, useState } from 'react'

import HeadingSmall from '@/components/heading-small'
import { Button } from '@/components/ui/button'
import { AppPasswordInput } from '@/components/app-password-input'
import { AppPasswordStrongInput } from '@/components/app-password-strong-input'
import { edit } from '@/routes/password'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Cài đặt mật khẩu',
    href: edit().url,
  },
]

export default function Password() {
  const passwordInput = useRef<HTMLInputElement>(null)
  const currentPasswordInput = useRef<HTMLInputElement>(null)

  // 🧠 State theo dõi tình trạng hợp lệ
  const [confirmPassword, setConfirmPassword] = useState('')
  const [isPasswordValid, setIsPasswordValid] = useState(false)
  const [isMatched, setIsMatched] = useState(false)

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Cài đặt mật khẩu" />

      <SettingsLayout>
        <div className="space-y-6">
          <HeadingSmall
            title="Cài đặt mật khẩu"
            description="Đảm bảo tài khoản của bạn đang sử dụng mật khẩu dài, ngẫu nhiên để đảm bảo an toàn"
          />

          <Form
            {...PasswordController.update.form()}
            options={{
              preserveScroll: true,
            }}
            resetOnError={[
              'password',
              'password_confirmation',
              'current_password',
            ]}
            resetOnSuccess
            onError={(errors) => {
              if (errors.password) passwordInput.current?.focus()
              if (errors.current_password)
                currentPasswordInput.current?.focus()
            }}
            className="space-y-6"
          >
            {({ errors, processing, recentlySuccessful }) => (
              <>
                {/* Mật khẩu hiện tại */}
                <div className="grid gap-2">
                  <AppPasswordInput
                    id="current_password"
                    ref={currentPasswordInput}
                    name="current_password"
                    label="Mật khẩu hiện tại"
                    placeholder="Nhập mật khẩu hiện tại"
                    autoComplete="current-password"
                    error={errors.current_password}
                    required
                  />
                </div>

                {/* Mật khẩu mới */}
                <div className="grid gap-2">
                  <AppPasswordStrongInput
                    id="password"
                    ref={passwordInput}
                    name="password"
                    label="Mật khẩu mới"
                    placeholder="Tạo mật khẩu mới"
                    autoComplete="new-password"
                    error={errors.password}
                    required
                    confirmValue={confirmPassword}
                    showConfirmCheck
                    // ⚙️ Nhận tín hiệu hợp lệ & khớp từ AppPasswordStrongInput
                    onValidityChange={(valid, matched) => {
                      setIsPasswordValid(valid)
                      setIsMatched(matched)
                    }}
                  />
                </div>

                {/* Xác nhận mật khẩu */}
                <div className="grid gap-2">
                  <AppPasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    label="Xác nhận mật khẩu"
                    placeholder="Nhập lại mật khẩu mới"
                    autoComplete="new-password"
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                    error={errors.password_confirmation}
                    required
                  />
                </div>

                {/* Nút lưu — luôn hiển thị nhưng disable khi chưa hợp lệ */}
                <div className="flex items-center gap-4">
                  <Button
                    disabled={
                      processing || !isPasswordValid || !isMatched
                    }
                    data-test="update-password-button"
                  >
                    {processing
                      ? 'Đang lưu...'
                      : !isPasswordValid
                      ? 'Vui lòng nhập mật khẩu'
                      : !isMatched
                      ? 'Mật khẩu chưa khớp'
                      : 'Lưu mật khẩu'}
                  </Button>

                  <Transition
                    show={recentlySuccessful}
                    enter="transition ease-in-out"
                    enterFrom="opacity-0"
                    leave="transition ease-in-out"
                    leaveTo="opacity-0"
                  >
                    <p className="text-sm text-neutral-600">Đã lưu</p>
                  </Transition>
                </div>
              </>
            )}
          </Form>
        </div>
      </SettingsLayout>
    </AppLayout>
  )
}
