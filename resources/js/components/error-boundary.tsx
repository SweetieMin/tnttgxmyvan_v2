"use client";

import React from 'react';
import { AlertTriangle } from 'lucide-react';
import { Button } from '@/components/ui/button';

interface ErrorBoundaryState {
    hasError: boolean;
    error?: Error;
}

interface ErrorBoundaryProps {
    children: React.ReactNode;
    fallback?: React.ComponentType<{ error?: Error; resetError: () => void }>;
}

class ErrorBoundary extends React.Component<ErrorBoundaryProps, ErrorBoundaryState> {
    constructor(props: ErrorBoundaryProps) {
        super(props);
        this.state = { hasError: false };
    }

    static getDerivedStateFromError(error: Error): ErrorBoundaryState {
        return { hasError: true, error };
    }

    componentDidCatch(error: Error, errorInfo: React.ErrorInfo) {
        console.error('ErrorBoundary caught an error:', error, errorInfo);
    }

    resetError = () => {
        this.setState({ hasError: false, error: undefined });
    };

    render() {
        if (this.state.hasError) {
            if (this.props.fallback) {
                const FallbackComponent = this.props.fallback;
                return <FallbackComponent error={this.state.error} resetError={this.resetError} />;
            }

            return (
                <div className="flex flex-col items-center justify-center min-h-[400px] p-8">
                    <AlertTriangle className="h-16 w-16 text-destructive mb-4" />
                    <h2 className="text-2xl font-semibold mb-2">Đã xảy ra lỗi</h2>
                    <p className="text-muted-foreground text-center mb-6 max-w-md">
                        Có vẻ như đã xảy ra lỗi không mong muốn. Vui lòng thử lại hoặc liên hệ quản trị viên nếu vấn đề vẫn tiếp tục.
                    </p>
                    {this.state.error && (
                        <details className="mb-6 max-w-2xl">
                            <summary className="cursor-pointer text-sm text-muted-foreground mb-2">
                                Chi tiết lỗi
                            </summary>
                            <pre className="text-xs bg-muted p-4 rounded-md overflow-auto">
                                {this.state.error.message}
                                {this.state.error.stack && (
                                    <>
                                        {'\n\n'}
                                        {this.state.error.stack}
                                    </>
                                )}
                            </pre>
                        </details>
                    )}
                    <Button onClick={this.resetError} variant="outline">
                        Thử lại
                    </Button>
                </div>
            );
        }

        return this.props.children;
    }
}

export default ErrorBoundary;
