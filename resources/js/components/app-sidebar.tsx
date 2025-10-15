import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as academic_years } from '@/routes/management/academic-years';
import { index as courses } from '@/routes/management/courses';
import { index as sectors } from '@/routes/management/sectors';
import { index as regulations } from '@/routes/management/regulations';

import { index as roles } from '@/routes/access/roles';

import { index as transactions } from '@/routes/finance/transactions';

import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import {
    Church,
    FerrisWheel,
    LayoutGrid,
    ShieldUser,
    SquareLibrary,
    BookOpenCheck,
    Banknote,
} from 'lucide-react';
import AppLogo from './app-logo';

const generalNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard.url(),
        icon: LayoutGrid,
    },
];

const managementNavItems: NavItem[] = [
    {
        title: 'Niên khoá',
        href: academic_years.url(),
        icon: SquareLibrary,
    },
    {
        title: 'Lớp Giáo Lý',
        href: courses.url(),
        icon: Church,
    },
    {
        title: 'Ngành Sinh Hoạt',
        href: sectors.url(),
        icon: FerrisWheel,
    },
    {
        title: 'Nội quy',
        href: regulations.url(),
        icon: BookOpenCheck,
    },
];

const accessNavItems: NavItem[] = [
    {
        title: 'Chức vụ',
        href: roles.url(),
        icon: ShieldUser,
    },
];

const financeNavItems: NavItem[] = [
    {
        title: 'Tiền quỹ',
        href: transactions.url(),
        icon: Banknote,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={generalNavItems} label="General" />
                <NavMain items={managementNavItems} label="Management" />
                <NavMain items={financeNavItems} label="Finance" />
                <NavMain items={accessNavItems} label="Access" />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
