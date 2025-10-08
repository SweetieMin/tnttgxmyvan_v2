import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    christian_name?: string;
    last_name: string;
    name: string;
    birthday?: string;
    account_code: string;
    email: string;
    token: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    status_login: 'active' | 'inactive' | 'lock';
    created_at: string;
    updated_at: string;
    christian_full_name: string;
    full_name: string;
    short_name: string;
    // Thông tin từ bảng details
    details?: {
        picture?: string;
        bio?: string;
        phone?: string;
        address?: string;
        gender: 'male' | 'female';
        created_at: string;
        updated_at: string;
    };

    
    [key: string]: unknown; // This allows for additional properties...
}
