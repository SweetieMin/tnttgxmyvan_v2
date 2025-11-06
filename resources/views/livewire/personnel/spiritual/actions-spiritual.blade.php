<div>
    <x-contents.layout heading="Người linh hướng" subheading="Quản lý danh sách và thông tin Người linh hướng"
        icon="squares-plus" :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Người linh hướng']]" buttonLabelBack="Quay lại" buttonBackAction="backRole">


        <div
            class="flex overflow-x-auto overflow-y-hidden border-b border-gray-200 whitespace-nowrap dark:border-gray-700">
            <button
                class="inline-flex items-center h-10 px-2 py-2 -mb-px text-center text-blue-600 bg-transparent border-b-2 border-blue-500 sm:px-4 -px-1 dark:border-blue-400 dark:text-blue-300 whitespace-nowrap focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-1 sm:w-6 sm:h-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                </svg>

                <span class="mx-1 text-sm sm:text-base">
                    Profile
                </span>
            </button>

            <button
                class="inline-flex items-center h-10 px-2 py-2 -mb-px text-center text-gray-700 bg-transparent border-b-2 border-transparent sm:px-4 -px-1 dark:text-white whitespace-nowrap cursor-base focus:outline-none hover:border-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-1 sm:w-6 sm:h-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>

                <span class="mx-1 text-sm sm:text-base">
                    Account
                </span>
            </button>

            <button
                class="inline-flex items-center h-10 px-2 py-2 -mb-px text-center text-gray-700 bg-transparent border-b-2 border-transparent sm:px-4 -px-1 dark:text-white whitespace-nowrap cursor-base focus:outline-none hover:border-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-1 sm:w-6 sm:h-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                <span class="mx-1 text-sm sm:text-base">
                    Notification
                </span>
            </button>
        </div>

        <div class="p-6 space-y-6">

            <!-- Ảnh đại diện -->
            <div class="flex items-center space-x-4">
                <img src="https://ui-avatars.com/api/?name=Le+Thanh+Tuan&background=3b82f6&color=fff"
                    class="w-20 h-20 rounded-full border shadow" alt="Avatar">
        
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Lê Thành Tuấn</h2>
                    <p class="text-gray-500 dark:text-gray-300 text-sm">Người linh hướng - Giáo xứ Mỹ Vân</p>
                </div>
            </div>
        
            <!-- Thông tin cá nhân -->
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <p>
                        <span class="font-medium text-gray-600 dark:text-gray-300">Họ và tên:</span>
                        <span class="ml-2">Lê Thành Tuấn</span>
                    </p>
                    <p>
                        <span class="font-medium text-gray-600 dark:text-gray-300">Ngày sinh:</span>
                        <span class="ml-2">1987-04-15</span>
                    </p>
                    <p>
                        <span class="font-medium text-gray-600 dark:text-gray-300">Giới tính:</span>
                        <span class="ml-2">Nam</span>
                    </p>
                    <p>
                        <span class="font-medium text-gray-600 dark:text-gray-300">CMND/CCCD:</span>
                        <span class="ml-2">079123456789</span>
                    </p>
                </div>
        
                <div class="space-y-3">
                    <p>
                        <span class="font-medium text-gray-600 dark:text-gray-300">Email:</span>
                        <span class="ml-2">tuan.linhhuong@example.com</span>
                    </p>
                    <p>
                        <span class="font-medium text-gray-600 dark:text-gray-300">Số điện thoại:</span>
                        <span class="ml-2">0903 456 789</span>
                    </p>
                    <p>
                        <span class="font-medium text-gray-600 dark:text-gray-300">Địa chỉ:</span>
                        <span class="ml-2">Ấp 1, Xuân Hiệp, Long Khánh, Đồng Nai</span>
                    </p>
                    <p>
                        <span class="font-medium text-gray-600 dark:text-gray-300">Ngày nhận chức:</span>
                        <span class="ml-2">2019-08-12</span>
                    </p>
                </div>
            </div>
        
            <!-- Ghi chú -->
            <div class="pt-4 border-t dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-200">
                    <span class="font-medium">Ghi chú:</span>
                    Cha Tuấn là người chịu trách nhiệm đồng hành và hướng dẫn thiếu nhi trong đời sống đức tin.
                </p>
            </div>
        
        </div>
        

    </x-contents.layout>

</div>
