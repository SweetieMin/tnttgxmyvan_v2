import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { useEffect, useState } from 'react';
import { AppPasswordInput } from '@/components/app-password-input'

interface LoginProps {
    status?: string;
    canResetPassword: boolean;
}

export default function Login({ status, canResetPassword }: LoginProps) {
    const [cooldown, setCooldown] = useState<number | null>(null);

    useEffect(() => {
        let timer: NodeJS.Timeout;

        if (cooldown !== null && cooldown > 0) {
            timer = setInterval(() => {
                setCooldown((prev) => {
                    if (prev === null || prev <= 0) {
                        return 0;
                    }
                    return prev - 1;
                });
            }, 1000);
        }

        return () => clearInterval(timer);
    }, [cooldown]);

    return (
        <AuthLayout title="Đăng nhập vào tài khoản" description="">
            <Head title="Đăng nhập" />

            <Form
                {...AuthenticatedSessionController.store.form()}
                resetOnSuccess={['password']}
                onError={(errors) => {
                    // ✅ Bắt lỗi "Too many login attempts"
                    if (errors.login_id && errors.login_id.includes('quá nhiều lần')) {
                        setCooldown(60); // 60 giây cooldown
                    }
                }}
                className="flex flex-col gap-6"
            >
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-6">
                            <div className="grid gap-2">
                                <Label htmlFor="login_id">Username / Email</Label>
                                <Input
                                    id="login_id"
                                    type="text"
                                    name="login_id"
                                    autoFocus
                                    tabIndex={1}
                                    placeholder="Username / Email"
                                    disabled={cooldown !== null && cooldown > 0}
                                />
                                <InputError message={errors.login_id} />
                            </div>

                            <div className="grid gap-2">
                                <div className="flex items-center">
                                    <Label htmlFor="password">Mật khẩu</Label>
                                    {canResetPassword && (
                                        <TextLink
                                            href={request()}
                                            className="ml-auto text-sm"
                                            tabIndex={5}
                                        >
                                            Quên mật khẩu?
                                        </TextLink>
                                    )}
                                </div>
                                <AppPasswordInput
                                    id="password"
                                    type="password"
                                    name="password"
                                    tabIndex={2}
                                    autoComplete="current-password"
                                    placeholder="**********"
                                    disabled={cooldown !== null && cooldown > 0}
                                />
                                <InputError message={errors.password} />
                            </div>

                            <div className="flex items-center space-x-3">
                                <Checkbox
                                    id="remember"
                                    name="remember"
                                    tabIndex={3}
                                    disabled={cooldown !== null && cooldown > 0}
                                />
                                <Label htmlFor="remember">Ghi nhớ đăng nhập</Label>
                            </div>

                            <Button
                                type="submit"
                                className="mt-4 w-full"
                                tabIndex={4}
                                disabled={processing || (cooldown !== null && cooldown > 0)}
                                data-test="login-button"
                            >
                                {processing && (
                                    <LoaderCircle className="h-4 w-4 animate-spin" />
                                )}
                                {cooldown !== null && cooldown > 0
                                    ? `Vui lòng đợi ${cooldown} giây...`
                                    : 'Đăng nhập'}
                            </Button>
                        </div>
                    </>
                )}
            </Form>

            {status && (
                <div className="mb-4 text-center text-sm font-medium text-green-600">
                    {status}
                </div>
            )}
        </AuthLayout>
    );
}
