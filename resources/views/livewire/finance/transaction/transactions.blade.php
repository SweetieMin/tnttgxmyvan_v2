<div>
    <x-contents.layout heading="Tiền Quỹ" subheading="Quản lý danh sách và thông tin Tiền Quỹ" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Tiền Quỹ']]" buttonLabel="Thêm Tiền Quỹ" buttonAction="addTransaction">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Tiền Quỹ..." wire:model.live.debounce.500ms="search"
                    :items="$items" :count="$transactions->total() ?? 0" :startDate=true :endDate=true :exportData="$transactions->total() > 0" />
            </div>
        </div>


        {{-- Main content area --}}
        <div class="mt-2">
            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
               {{-- Số tiền hiện tại --}}
               <flux:tooltip content="Số tiền còn lại sau khi tính tổng thu, tổng chi.">
                <flux:card class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground mb-1">Số tiền hiện tại</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($balance, 0, ',', '.') }} ₫
                            </p>
                        </div>
                        <flux:icon.banknotes class="w-10 h-10 text-blue-500" />
                    </div>
                </flux:card>
            </flux:tooltip>
        
            {{-- Tổng thu --}}
            <flux:tooltip content="Tổng tất cả các khoản tiền thu vào trong kỳ.">
                <flux:card class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground mb-1">Tổng thu</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ number_format($totalIncome, 0, ',', '.') }} ₫
                            </p>
                        </div>
                        <flux:icon.arrow-down-circle class="w-10 h-10 text-green-500" />
                    </div>
                </flux:card>
            </flux:tooltip>
        
            {{-- Tổng chi --}}
            <flux:tooltip content="Tổng toàn bộ các khoản chi trong kỳ.">
                <flux:card class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground mb-1">Tổng chi</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ number_format($totalExpense, 0, ',', '.') }} ₫
                            </p>
                        </div>
                        <flux:icon.arrow-up-circle class="w-10 h-10 text-red-500" />
                    </div>
                </flux:card>
            </flux:tooltip>
        
            {{-- Tổng công nợ --}}
            <flux:tooltip content="Tổng các khoản đang nợ hoặc chưa thanh toán.">
                <flux:card class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground mb-1">Tổng công nợ</p>
                            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                                {{ number_format($totalDebt, 0, ',', '.') }} ₫
                            </p>
                        </div>
                        <flux:icon.arrow-up-circle class="w-10 h-10 text-orange-500" />
                    </div>
                </flux:card>
            </flux:tooltip>
            </div>

            <div class="theme-table">
                {{-- Desktop Table View --}}
                <div class="hidden md:block">
                    <flux:card class="overflow-hidden border border-accent/20 rounded-xl shadow-sm">
                        <flux:table container:class="max-h-[calc(60vh-150px)] overflow-y-auto custom-scrollbar"
                            class="w-full transition [&>tbody>tr]:transition-colors [&>tbody>tr:hover>td]:text-accent-content/70 [&>tbody>tr:hover]:scale-[0.998] [&>tbody>tr:hover]:bg-transparent">
                            <flux:table.columns sticky class="bg-white dark:bg-zinc-700">
                                <flux:table.column class="w-32">Ngày</flux:table.column>
                                <flux:table.column align="center" class="w-40">Hạng mục</flux:table.column>
                                <flux:table.column align="left">Mô tả</flux:table.column>
                                <flux:table.column align="center" class="w-32 ">Thu</flux:table.column>
                                <flux:table.column align="center" class="w-32">Chi</flux:table.column>
                                <flux:table.column align="center" class="w-40">Người phụ trách</flux:table.column>
                                <flux:table.column align="center" class="w-32">Trạng thái</flux:table.column>
                                <flux:table.column align="center" class="w-32">File đính kèm</flux:table.column>
                                <flux:table.column class="w-20"></flux:table.column>
                            </flux:table.columns>

                            <flux:table.rows>
                                @forelse ($transactions as $transaction)
                                    <flux:table.row wire:key="transaction-desktop-{{ $transaction->id }}">
                                        <flux:table.cell>
                                            {{ $transaction->formatted_transaction_date }}
                                        </flux:table.cell>
                                        <flux:table.cell align="center">
                                            <flux:badge color="{{ $transaction->item->is_system ? 'amber' : 'zinc' }}">
                                                {{ $transaction->item->name ?? '—' }}
                                            </flux:badge>
                                        </flux:table.cell>
                                        <flux:table.cell align="left">
                                            {{ $transaction->description }}
                                        </flux:table.cell>
                                        <flux:table.cell align="center"
                                            class="{{ $transaction->type === 'income' ? 'text-green-500 font-semibold' : 'text-muted' }}">
                                            {{ $transaction->type === 'income' ? $transaction->formatted_amount : '-' }}
                                        </flux:table.cell>
                                        <flux:table.cell align="center"
                                            class="{{ $transaction->type === 'expense' ? 'text-red-500 font-semibold' : 'text-muted' }}">
                                            {{ $transaction->type === 'expense' ? $transaction->formatted_amount : '-' }}
                                        </flux:table.cell>
                                        <flux:table.cell align="center">
                                            {{ $transaction->in_charge }}
                                        </flux:table.cell>
                                        <flux:table.cell align="center">
                                            <flux:badge
                                                color="{{ $transaction->status === 'pending' ? 'red' : 'green' }}">
                                                {{ $transaction->status === 'pending' ? 'Chưa chi' : 'Đã chi' }}
                                            </flux:badge>
                                        </flux:table.cell>
                                        <flux:table.cell align="center">
                                            @if ($transaction->file_name)
                                                <flux:link href="{{ $transaction->file_name }}" target="_blank"
                                                    variant="ghost">
                                                    Xem file
                                                </flux:link>
                                            @else
                                                -
                                            @endif
                                        </flux:table.cell>
                                        <flux:table.cell class="text-right">
                                            <flux:dropdown position="bottom" align="end">
                                                <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                                    variant="subtle" />
                                                <flux:menu>
                                                    <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                        wire:click='editTransaction({{ $transaction->id }})'>
                                                        Sửa
                                                    </flux:menu.item>
                                                    <flux:menu.item class="cursor-pointer" icon="trash"
                                                        variant="danger"
                                                        wire:click='deleteTransaction({{ $transaction->id }})'>
                                                        Xoá
                                                    </flux:menu.item>
                                                </flux:menu>
                                            </flux:dropdown>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @empty
                                    <flux:table.row>
                                        <flux:table.cell colspan="9">
                                            <div class="empty-state flex flex-col items-center py-6">
                                                <flux:icon.squares-plus class="w-8 h-8 mb-2" />
                                                <div class="text-sm">Không có dữ liệu</div>
                                            </div>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforelse
                            </flux:table.rows>
                        </flux:table>
                    </flux:card>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-3">
                    @forelse ($transactions as $transaction)
                        <flux:accordion wire:key="transaction-{{ $transaction->id }}" transition variant="reverse">
                            <flux:card class="space-y-6">
                                <flux:accordion.item>
                                    <flux:accordion.heading>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex items-center justify-center min-w-8 min-h-8 rounded-full bg-accent text-sm font-semibold">
                                                {{ $loop->iteration }}
                                            </span>
                                            <div class="flex flex-col text-left flex-1">
                                                <span
                                                    class="font-semibold text-accent-text">{{ $transaction->description }}</span>
                                                <span
                                                    class="text-xs text-accent-text/70">{{ $transaction->formatted_transaction_date }}</span>
                                            </div>
                                            <div class="flex flex-col items-end gap-1">
                                                <flux:badge
                                                    color="{{ $transaction->item->is_system ? 'amber' : 'zinc' }}"
                                                    class="text-xs">
                                                    {{ $transaction->item->name ?? '—' }}
                                                </flux:badge>
                                                <flux:badge
                                                    color="{{ $transaction->status === 'pending' ? 'red' : 'green' }}"
                                                    class="text-xs">
                                                    {{ $transaction->status === 'pending' ? 'Chưa chi' : 'Đã chi' }}
                                                </flux:badge>
                                            </div>
                                        </div>
                                    </flux:accordion.heading>

                                    <flux:accordion.content>
                                        <div class="space-y-3 text-sm text-accent-text/90 mt-2">
                                            <div class="flex justify-between">
                                                <span>Thu:</span>
                                                <span
                                                    class="{{ $transaction->type === 'income' ? 'text-green-500 font-semibold' : 'text-muted' }}">
                                                    {{ $transaction->type === 'income' ? $transaction->formatted_amount : '-' }}
                                                </span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span>Chi:</span>
                                                <span
                                                    class="{{ $transaction->type === 'expense' ? 'text-red-500 font-semibold' : 'text-muted' }}">
                                                    {{ $transaction->type === 'expense' ? $transaction->formatted_amount : '-' }}
                                                </span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span>Người phụ trách:</span>
                                                <span>{{ $transaction->in_charge }}</span>
                                            </div>

                                            @if ($transaction->file_name)
                                                <div class="flex justify-between">
                                                    <span>File đính kèm:</span>
                                                    <flux:link href="{{ $transaction->file_name }}" target="_blank"
                                                        variant="ghost" class="text-xs">
                                                        Xem file
                                                    </flux:link>
                                                </div>
                                            @endif

                                            <div class="pt-3 border-t border-accent/10 flex gap-2">
                                                <flux:button wire:click="editTransaction({{ $transaction->id }})"
                                                    icon="pencil-square" variant="filled" class="flex-1">
                                                    Sửa
                                                </flux:button>
                                                <flux:button wire:click="deleteTransaction({{ $transaction->id }})"
                                                    icon="trash" variant="danger" class="flex-1">
                                                    Xóa
                                                </flux:button>
                                            </div>
                                        </div>
                                    </flux:accordion.content>
                                </flux:accordion.item>
                            </flux:card>
                        </flux:accordion>
                    @empty
                        <flux:card class="p-6 text-center">
                            <flux:icon.squares-plus class="w-8 h-8 mb-2 text-muted-foreground" />
                            <flux:text>Không có dữ liệu</flux:text>
                        </flux:card>
                    @endforelse
                </div>

                @if ($transactions->hasPages())
                    <div class="py-4 px-5">
                        {{ $transactions->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>

    </x-contents.layout>

    <livewire:finance.transaction.actions-transaction />

</div>
