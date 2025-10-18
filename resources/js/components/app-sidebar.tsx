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

import { index as spirituals } from '@/routes/personnel/spirituals';
import { index as catechists } from '@/routes/personnel/catechists';
import { index as scouters } from '@/routes/personnel/scouters';
import { index as childrenActive } from '@/routes/personnel/children/active';
import { index as childrenGraduation } from '@/routes/personnel/children/graduation';


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
    Cross,
    BookPlus,
    UserStar,
    Baby,
    GraduationCap,
} from 'lucide-react';
import AppLogo from './app-logo';

const generalNavItems: NavItem[] = [
    {
        title: 'Trang chủ',
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
        icon: BookPlus,
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

const personnelNavItems: NavItem[] = [
    {
        title: 'Linh hướng',
        href: spirituals.url(),
        icon: Cross,
    },
    {
        title: 'Giáo lý viên',
        href: catechists.url(),
        icon: BookOpenCheck,
    },
    {
        title: 'Huynh-Dự-Đội trưởng',
        href: scouters.url(),
        icon: UserStar,
    },
    {
        title: 'Thiếu nhi',
        href: childrenActive.url(),
        icon: Baby,
    },
    {
        title: 'Đã tốt nghiệp',
        href: childrenGraduation.url(),
        icon: GraduationCap,
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
                <NavMain items={generalNavItems} label="Tổng quan" />
                <NavMain items={managementNavItems} label="Quản lý" />
                <NavMain items={personnelNavItems} label="Nhân sự" />
                <NavMain items={financeNavItems} label="Tài chính" />
                <NavMain items={accessNavItems} label="Quyền truy cập" />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
