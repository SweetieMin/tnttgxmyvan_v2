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
import { index as roles } from '@/routes/management/roles';
import { index as courses } from '@/routes/management/courses';
import { index as sectors } from '@/routes/management/sectors';

import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { LayoutGrid, SquareLibrary, ShieldUser,Church, FerrisWheel } from 'lucide-react';
import AppLogo from './app-logo';

const generalNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const managementNavItems: NavItem[] = [
    {
        title: 'Niên khoá',
        href: academic_years(),
        icon: SquareLibrary,
    },
    {
        title: 'Lớp Giáo Lý',
        href: courses(),
        icon: Church,
    },
    {
        title: 'Ngành Sinh Hoạt',
        href: sectors(),
        icon: FerrisWheel,
    },
    {
        title: 'Chức vụ',
        href: roles(),
        icon: ShieldUser,
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
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
