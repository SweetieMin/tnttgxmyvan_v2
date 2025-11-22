<!DOCTYPE html>
<html class="light" lang="vi">

<head>
    @include('partials.head')
</head>

<body class="font-display">
    <div
        class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="flex flex-1 justify-center">
                <div class="layout-content-container flex flex-col max-w-5xl w-full flex-1">
                    <header
                        class="flex items-center justify-between whitespace-nowrap border-b border-solid border-primary/20 dark:border-primary/30 px-4 sm:px-10 py-4">
                        <div class="flex items-center gap-3 text-[#1b0d0d] dark:text-white">
                            <div class="size-6 text-primary">
                                <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M42.4379 44C42.4379 44 36.0744 33.9038 41.1692 24C46.8624 12.9336 42.2078 4 42.2078 4L7.01134 4C7.01134 4 11.6577 12.932 5.96912 23.9969C0.876273 33.9029 7.27094 44 7.27094 44L42.4379 44Z">
                                    </path>
                                </svg>
                            </div>
                            <h2
                                class="text-[#1b0d0d] dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">
                                Sắc Xuân May Mắn</h2>
                        </div>
                        <div class="hidden md:flex flex-1 justify-end gap-8">
                            <div class="flex items-center gap-9">
                                <a class="text-[#1b0d0d] dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary dark:hover:text-primary"
                                    href="#">Trang Chủ</a>
                                <a class="text-[#1b0d0d] dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary dark:hover:text-primary"
                                    href="#">Kết Quả</a>
                                <a class="text-[#1b0d0d] dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary dark:hover:text-primary"
                                    href="#">Cơ Cấu Giải Thưởng</a>
                                <a class="text-[#1b0d0d] dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary dark:hover:text-primary"
                                    href="#">Liên Hệ</a>
                            </div>
                            <button
                                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-red-700 transition-colors">
                                <span class="truncate">Mua Vé Ngay</span>
                            </button>
                        </div>
                    </header>
                    <main class="flex-1 px-4 sm:px-6 lg:px-8 py-10">
                        <div class="@container mb-12">
                            <div class="@[480px]:p-4">
                                <div class="flex min-h-[480px] flex-col gap-6 bg-cover bg-center bg-no-repeat @[480px]:gap-8 @[480px]:rounded-xl items-center justify-center p-6"
                                    data-alt="A luxury car decorated with a red bow, representing the grand prize for the Tet lottery."
                                    style='background-image: linear-gradient(rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.5) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuBFAnmogR-6FeryQ6R2AL-4IEYzocrnMXd6Sn_LzBnrBAfiEU6QDHlxEy3vuNt67UnP8YdB4NFuJHc1Qb2ohNrEZUC_B1lpZV-XHqcs8EYoeYX8j5HZ478VFP40QrIJ9ouA5SIbY58Fe54GARKgNy16iToM23-WrV2F6ZDA3lLpEBZ3vko3zD2d4hSsFDdmvHCuXMJC_abKfKrwPAR8bZYd1xjXafTck5dbi8IdZjcqOB8b9kQCHaipCpPNey402Jf23b7RqAcTKkk");'>
                                    <div class="flex flex-col gap-4 text-center">
                                        <h1
                                            class="text-white text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-5xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em]">
                                            Giải Đặc Biệt Tết 2024: Rước Lộc Về Nhà!
                                        </h1>
                                        <h2
                                            class="text-white text-base font-normal leading-normal @[480px]:text-lg @[480px]:font-normal @[480px]:leading-normal max-w-xl">
                                            Cơ hội trúng thưởng một chiếc xe hơi sang trọng và nhiều giải thưởng giá trị
                                            khác đang chờ đón bạn.
                                        </h2>
                                    </div>
                                    <button
                                        class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-accent text-[#1b0d0d] text-base font-bold leading-normal tracking-[0.015em] hover:brightness-90 transition-all">
                                        <span class="truncate">Tham Gia Ngay</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <marquee >This text will scroll from right to
                            left</marquee>

                        <section class="mb-16">
                            <div class="text-center mb-8">
                                <h2
                                    class="text-[#1b0d0d] dark:text-white text-3xl font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-5">
                                    Cơ Cấu Giải Thưởng Thịnh Vượng</h2>
                                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Khám phá 7 giải thưởng giá
                                    trị, mang tài lộc và niềm vui đến mọi nhà trong dịp xuân mới.</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div
                                    class="lg:col-span-3 bg-white dark:bg-zinc-900 rounded-2xl shadow-lg overflow-hidden flex flex-col md:flex-row group transition-transform duration-300 hover:scale-105">
                                    <div class="md:w-1/2 h-64 md:h-auto">
                                        <img alt="Xe Máy Vision" class="w-full h-full object-cover"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBALH-ijcZxIOM6dEQLR3c0W1NaE8p6a01YRPbeqcVZCZ7Egpitomy1E-gHfO2OvQ-kR8eFPgTMWiH_lUStIGRwE1Au-N_32z7rYlJAjoKy3hAb4Sn7Ceo3THxdKi0j-YYKxRbE5qhiJh_FVKEoEsbh4u6mPPEhkV3qYMNo-CvvrOBI93x2pv1But-IUibRZGPoFVIqYMrZLI_59cvJeabtV6T8aIh87YkhN0l_-cF_corf5LMRpw_yshr47Ioc_nCEaqBN-AWlzpA" />
                                    </div>
                                    <div class="md:w-1/2 p-6 flex flex-col justify-center">
                                        <p class="text-accent font-semibold text-sm">Giải Nhất</p>
                                        <h3 class="text-2xl font-bold text-primary dark:text-primary/90 mt-1">Xe Máy
                                            Vision</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-2">Sang trọng, thanh lịch và tiết
                                            kiệm nhiên liệu, là người bạn đồng hành lý tưởng trên mọi nẻo đường.</p>
                                    </div>
                                </div>
                                
                                <div
                                    class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg overflow-hidden group transition-transform duration-300 hover:scale-105">
                                    <div class="h-48 overflow-hidden"><img alt="Xe Đạp Điện"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBLrwE4EVdX3bwsra8c6DXGHSeQRMHB7GnUzQ5mmoNPHqb52G2t3l2jWbUv7O9dfdxvLkKEx3HBW4Xp6X2ZzjJFopyArNpRwRmPXfKLrMXFiB5CK_sKubKFAbM6ziO6j-SN5tdlWDwE68hn_YCNI6_SpQaJUDSuUsHM-m88birM9sHw50ARW7WIHJIFFiDNLizO2_k3SWNMtjOyFoFSqqKctXaHwNd44NMvq8OoDUsAE7F-pLTYX-yBn6QlBCQwy72lyR59kItyWSA" />
                                    </div>
                                    <div class="p-6">
                                        <p class="text-accent font-semibold text-sm">Giải Nhì</p>
                                        <h3 class="text-xl font-bold text-primary dark:text-primary/90 mt-1">Xe Đạp Điện
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">Thân thiện với môi
                                            trường, di chuyển nhẹ nhàng và tiện lợi trong thành phố.</p>
                                    </div>
                                </div>
                                <div
                                    class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg overflow-hidden group transition-transform duration-300 hover:scale-105">
                                    <div class="h-48 overflow-hidden"><img alt="Lò Vi Sóng"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBqdQdZhWd8NE0FhGvaJArfjoucQSEZvzXmqUKzOivtzZS3O-bjmCEL814GHhb1xcYFCK-6dlniVheBKX-1v4rjVJGjH5otYGyUINZB7Mwct3afG0RsEGSXW2ZM_YEaReA1ta3LGbiuOsuTSvCoqA8b3t2hglp9jpxPmU6ViASR1MjRNG40XcsDxwQnj6hX3v_TYizOV-CG_7CPF5tL2SwtffUwMK-q6NQJuloI5eH6ARxupRbB-eCF-7iO3PDcEW2GyUFN1tFhBlA" />
                                    </div>
                                    <div class="p-6">
                                        <p class="text-accent font-semibold text-sm">Giải Ba</p>
                                        <h3 class="text-xl font-bold text-primary dark:text-primary/90 mt-1">Lò Vi Sóng
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">Trợ thủ đắc lực cho căn
                                            bếp, giúp chuẩn bị bữa ăn nhanh chóng và ngon miệng.</p>
                                    </div>
                                </div>
                                <div
                                    class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg overflow-hidden group transition-transform duration-300 hover:scale-105">
                                    <div class="h-48 overflow-hidden"><img alt="Quạt Asia"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAQpaTQSWSPsKpayEAXSThvCHoOKyXD8U-d3slR6MccI5b7kwFFuXJ-g9zzc-rXwlWFCd72CmkhpKjc42YG71BBigahSj0WN_vg6bUc3FoEuQwWVjRY4qSVtBZnWO6F2_SXmi-giBoO-gNGJIbybSHmP0jcea30ZlxJtQ9oC6nYjtFn0jfSwceZCWM1jRf0d-6IxLPtEn29R7gOwslHvnjQcetjvQVcsKOPeg9Uy1NLia4fOVVyQjsE2nhc_WjjnEQ_aq_nIjMPs-k" />
                                    </div>
                                    <div class="p-6">
                                        <p class="text-accent font-semibold text-sm">Giải Tư</p>
                                        <h3 class="text-xl font-bold text-primary dark:text-primary/90 mt-1">Quạt Asia
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">Mang làn gió mát lành,
                                            xua tan nóng nực, cho không gian sống thêm dễ chịu.</p>
                                    </div>
                                </div>
                                <div
                                    class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg overflow-hidden group transition-transform duration-300 hover:scale-105">
                                    <div class="h-48 overflow-hidden"><img alt="Bếp Ga Đôi"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAR8WsvlPPcJMhF3hi2tVw4aeyY_GaKFBJKzORv5pkC7S89d8J1rcS5WMbKyrzHNsZVHxK3BoMSQdTJ2p-O3y0ghy7txFx9jLD3KhHtdVr1wXHncbdAHtLhB5CxpW4QxYVAEF5dCtDMg3FxzH01Zsc2cD8d6XxiKnVqAc6n12IL11YM0q4lvEQnNlPfyzafMxdMvMaKm8puNt2DfC3ZQ1AJs2x4--rUfSTULdAe3ohSH1SokLRKeylH3BfDlH_ywuKxaQsFvYEJERI" />
                                    </div>
                                    <div class="p-6">
                                        <p class="text-accent font-semibold text-sm">Giải Năm</p>
                                        <h3 class="text-xl font-bold text-primary dark:text-primary/90 mt-1">Bếp Ga Đôi
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">Nấu ăn tiện lợi, an
                                            toàn và hiệu quả, cho bữa cơm gia đình thêm ấm cúng.</p>
                                    </div>
                                </div>
                                <div
                                    class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg overflow-hidden group transition-transform duration-300 hover:scale-105">
                                    <div class="h-48 overflow-hidden"><img alt="Bộ Chén Sứ"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBRoCsv6i3RwuJYGFKopjDSR_C9Ro5hR8IMRSe9pzGvf0z1b7baXwKyT6RcIaD1EuIw3_Ww__xGmCEUYatr3eHlInRxtQV1IpK2RvtGx6QSwpL0N5_NcumlOmN-dXfnnBO5gGzCbiM8wdAxZsKjJB9sCHTPQdtBhCrKme_Cgc-E0ODBCovd60acFms-pX96BIu9V2WsJ2hTZtOuJ1i9SZvddM2xBWF8Vl4yQxtBXkuNj2nnf1hai4p9a14SDCs8feHGsiqLhnf01Cc" />
                                    </div>
                                    <div class="p-6">
                                        <p class="text-accent font-semibold text-sm">Giải Sáu</p>
                                        <h3 class="text-xl font-bold text-primary dark:text-primary/90 mt-1">Bộ Chén Sứ
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">Chất liệu cao cấp,
                                            thiết kế tinh xảo, mang lại vẻ sang trọng cho bàn ăn ngày Tết.</p>
                                    </div>
                                </div>
                                <div
                                    class="bg-white dark:bg-zinc-900 rounded-2xl shadow-lg overflow-hidden group transition-transform duration-300 hover:scale-105">
                                    <div class="h-48 overflow-hidden"><img alt="Bao Lì Xì"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuA4k5-OuY59Was_43FdCuAFjXRT3JCQy6b1bufEfv0a8WxF1DYFPjxGOTr_W0EpYXmuHUKISdzJ0dzPKG37-bexxfjXZOOk697GSoZoW9eiRAWphMIJnaiXdnz2_OBYKLP9z95X-zkWUdY3ekjzLbT-MBBrwbhDzENu4mksseSoiQLKJDmENHMT-wLGQY6s21y2SwsUwRggJ0iIwSOopOS1SBvoVg88rWx1Qco1TF_fo-S0RSlsCXD8GzPgvAHn-AfexyRQ-dFyqrA" />
                                    </div>
                                    <div class="p-6">
                                        <p class="text-accent font-semibold text-sm">Giải Bảy</p>
                                        <h3 class="text-xl font-bold text-primary dark:text-primary/90 mt-1">Bao Lì Xì
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">Trao tay may mắn, gửi
                                            gắm lời chúc một năm mới an khang, thịnh vượng.</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section>
                            <h2
                                class="text-[#1b0d0d] dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-5 pt-5">
                                Kết Quả Xổ Số Mới Nhất</h2>
                            <div
                                class="bg-white dark:bg-background-dark/50 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] p-6">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-lg font-bold text-[#1b0d0d] dark:text-white">Kỳ quay ngày
                                        28/08/2024</h3>
                                    <a class="text-sm font-medium text-primary dark:text-primary/90 hover:underline"
                                        href="#">Xem kết quả cũ hơn</a>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                                    <div
                                        class="flex items-center justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-4">
                                        <span class="text-base font-medium text-gray-700 dark:text-gray-300">Giải Đặc
                                            Biệt</span>
                                        <span
                                            class="text-2xl font-bold text-accent tracking-wider bg-primary/10 dark:bg-primary/20 px-4 py-1 rounded-lg">123456</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-4">
                                        <span class="text-base font-medium text-gray-700 dark:text-gray-300">Giải
                                            Nhất</span>
                                        <span
                                            class="text-2xl font-bold text-primary dark:text-primary/90 tracking-wider">65432</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-4">
                                        <span class="text-base font-medium text-gray-700 dark:text-gray-300">Giải
                                            Nhì</span>
                                        <span
                                            class="text-2xl font-bold text-primary dark:text-primary/90 tracking-wider">98765</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-4">
                                        <span class="text-base font-medium text-gray-700 dark:text-gray-300">Giải
                                            Ba</span>
                                        <span
                                            class="text-2xl font-bold text-primary dark:text-primary/90 tracking-wider">56789</span>
                                    </div>
                                    <div
                                        class="flex items-center justify-between border-b border-dashed border-gray-200 dark:border-gray-700 pb-4 md:border-b-0">
                                        <span class="text-base font-medium text-gray-700 dark:text-gray-300">Giải
                                            Tư</span>
                                        <span
                                            class="text-2xl font-bold text-primary dark:text-primary/90 tracking-wider">13579</span>
                                    </div>
                                    <div class="flex items-center justify-between pb-4 md:pb-0">
                                        <span class="text-base font-medium text-gray-700 dark:text-gray-300">Giải
                                            Năm</span>
                                        <span
                                            class="text-2xl font-bold text-primary dark:text-primary/90 tracking-wider">24680</span>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </main>
                    <footer class="bg-primary/10 dark:bg-primary/20 text-gray-700 dark:text-gray-300 mt-12">
                        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div>
                                    <h3 class="font-bold text-lg text-[#1b0d0d] dark:text-white mb-2">Sắc Xuân May Mắn
                                    </h3>
                                    <p class="text-sm">Mang tài lộc và may mắn đến mọi nhà trong dịp Tết Nguyên Đán.
                                    </p>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-[#1b0d0d] dark:text-white mb-2">Liên kết nhanh
                                    </h3>
                                    <ul class="space-y-2 text-sm">
                                        <li><a class="hover:text-primary dark:hover:text-primary/90"
                                                href="#">Điều khoản sử dụng</a></li>
                                        <li><a class="hover:text-primary dark:hover:text-primary/90"
                                                href="#">Chính sách bảo mật</a></li>
                                        <li><a class="hover:text-primary dark:hover:text-primary/90"
                                                href="#">Câu hỏi thường gặp</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-[#1b0d0d] dark:text-white mb-2">Theo dõi chúng
                                        tôi</h3>
                                    <div class="flex space-x-4">
                                        <a class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary/90"
                                            data-alt="Facebook social media icon" href="#">
                                            <svg aria-hidden="true" class="h-6 w-6" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path clip-rule="evenodd"
                                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                                    fill-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                        <a class="text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-primary/90"
                                            data-alt="YouTube social media icon" href="#">
                                            <svg aria-hidden="true" class="h-6 w-6" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path clip-rule="evenodd"
                                                    d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.78 22 12 22 12s0 3.22-.42 4.814a2.506 2.506 0 0 1-1.768 1.768c-1.594.42-7.812.42-7.812.42s-6.218 0-7.812-.42a2.506 2.506 0 0 1-1.768-1.768C2 15.22 2 12 2 12s0-3.22.42-4.814a2.506 2.506 0 0 1 1.768-1.768C5.782 5 12 5 12 5s6.218 0 7.812.418ZM15.194 12 10 15V9l5.194 3Z"
                                                    fill-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="mt-8 border-t border-primary/20 dark:border-primary/30 pt-4 text-center text-sm">
                                <p>© 2024 Sắc Xuân May Mắn. All rights reserved.</p>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
