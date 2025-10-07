import HeadingSmall from '@/components/heading-small';
import TwoFactorRecoveryCodes from '@/components/two-factor-recovery-codes';
import TwoFactorSetupModal from '@/components/two-factor-setup-modal';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTwoFactorAuth } from '@/hooks/use-two-factor-auth';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { disable, enable, show } from '@/routes/two-factor';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/react';
import { ShieldBan, ShieldCheck } from 'lucide-react';
import { useState } from 'react';

interface TwoFactorProps {
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Xác thực hai yếu tố',
        href: show.url(),
    },
];

export default function TwoFactor({
    requiresConfirmation = false,
    twoFactorEnabled = false,
}: TwoFactorProps) {
    const {
        qrCodeSvg,
        hasSetupData,
        manualSetupKey,
        clearSetupData,
        fetchSetupData,
        recoveryCodesList,
        fetchRecoveryCodes,
        errors,
    } = useTwoFactorAuth();
    const [showSetupModal, setShowSetupModal] = useState<boolean>(false);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Xác thực hai yếu tố" />
            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title="Xác thực hai yếu tố"
                        description="Quản lý và bảo vệ tài khoản của bạn bằng xác thực hai yếu tố (2FA)."
                    />

                    {twoFactorEnabled ? (
                        // --- Khi đã bật 2FA ---
                        <div className="flex flex-col items-start justify-start space-y-4">
                            <Badge variant="default">Đã bật</Badge>
                            <p className="text-muted-foreground">
                                Khi xác thực hai yếu tố được bật, mỗi lần đăng
                                nhập bạn sẽ cần nhập mã PIN an toàn được tạo
                                ngẫu nhiên trong ứng dụng Authenticator trên
                                điện thoại của bạn.
                            </p>

                            <TwoFactorRecoveryCodes
                                recoveryCodesList={recoveryCodesList}
                                fetchRecoveryCodes={fetchRecoveryCodes}
                                errors={errors}
                            />

                            <div className="relative inline">
                                <Form {...disable.form()}>
                                    {({ processing }) => (
                                        <Button
                                            variant="destructive"
                                            type="submit"
                                            disabled={processing}
                                        >
                                            <ShieldBan />
                                            Tắt xác thực 2 yếu tố
                                        </Button>
                                    )}
                                </Form>
                            </div>
                        </div>
                    ) : (
                        // --- Khi chưa bật 2FA ---
                        <div className="flex flex-col items-start justify-start space-y-4">
                            <Badge variant="destructive">Đang tắt</Badge>
                            <p className="text-muted-foreground">
                                Khi bạn bật xác thực hai yếu tố, hệ thống sẽ yêu
                                cầu bạn nhập mã bảo mật mỗi khi đăng nhập. Mã
                                này được lấy từ ứng dụng xác thực (Authenticator)
                                trên điện thoại của bạn.
                            </p>

                            <div>
                                {hasSetupData ? (
                                    <Button
                                        onClick={() => setShowSetupModal(true)}
                                    >
                                        <ShieldCheck />
                                        Tiếp tục cài đặt
                                    </Button>
                                ) : (
                                    <Form
                                        {...enable.form()}
                                        onSuccess={() =>
                                            setShowSetupModal(true)
                                        }
                                    >
                                        {({ processing }) => (
                                            <Button
                                                type="submit"
                                                disabled={processing}
                                            >
                                                <ShieldCheck />
                                                Bật xác thực 2 yếu tố
                                            </Button>
                                        )}
                                    </Form>
                                )}
                            </div>
                        </div>
                    )}

                    <TwoFactorSetupModal
                        isOpen={showSetupModal}
                        onClose={() => setShowSetupModal(false)}
                        requiresConfirmation={requiresConfirmation}
                        twoFactorEnabled={twoFactorEnabled}
                        qrCodeSvg={qrCodeSvg}
                        manualSetupKey={manualSetupKey}
                        clearSetupData={clearSetupData}
                        fetchSetupData={fetchSetupData}
                        errors={errors}
                    />
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
