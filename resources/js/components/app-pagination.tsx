'use client';

import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationLink,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';

interface AppPaginationProps {
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    total: number;
    from: number;
    to: number;
}

/**
 * 🔢 AppPagination — Phân trang chung cho toàn hệ thống
 * Dùng cho Inertia pagination (Laravel -> React)
 */
export default function AppPagination({
    links,
    total,
    from,
    to,
}: AppPaginationProps) {
    if (!links || links.length <= 1) return null;

    // Lọc ra các link có ý nghĩa (không phải ellipsis)
    const meaningfulLinks = links.filter(link => link.url && link.label !== '...');
    
    // Nếu chỉ có 1 trang, không hiển thị pagination
    if (meaningfulLinks.length <= 1) {
        return (
            <div className="mt-6 border-t pt-4">
                <div className="text-center text-sm text-muted-foreground">
                    Hiển thị {from}–{to} trong tổng {total} kết quả
                </div>
            </div>
        );
    }

    return (
        <div className="mt-6 border-t border-border/50 pt-6">
            <div className="flex flex-col items-center justify-between gap-6 lg:flex-row lg:gap-8">
                {/* Thông tin hiển thị */}
                <div className="text-sm text-muted-foreground">
                    Hiển thị <span className="font-semibold text-foreground">{from}</span>–<span className="font-semibold text-foreground">{to}</span> trong tổng <span className="font-semibold text-foreground">{total}</span> kết quả
                </div>

                {/* Pagination links */}
                <Pagination>
                    <PaginationContent className="flex-wrap gap-1">
                        {/* Previous button */}
                        {links[0]?.url && (
                            <PaginationItem>
                                <PaginationPrevious href={links[0].url!} />
                            </PaginationItem>
                        )}
                        
                        {/* Page numbers */}
                        {links.slice(1, -1).map((link, index) =>
                            link.url ? (
                                <PaginationItem key={index}>
                                    <PaginationLink
                                        href={link.url!}
                                        isActive={link.active}
                                    >
                                        {link.label}
                                    </PaginationLink>
                                </PaginationItem>
                            ) : (
                                <PaginationItem key={index}>
                                    <PaginationEllipsis />
                                </PaginationItem>
                            ),
                        )}
                        
                        {/* Next button */}
                        {links[links.length - 1]?.url && (
                            <PaginationItem>
                                <PaginationNext href={links[links.length - 1].url!} />
                            </PaginationItem>
                        )}
                    </PaginationContent>
                </Pagination>
            </div>
        </div>
    );
}
