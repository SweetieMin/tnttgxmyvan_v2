<div>
    <x-contents.layout heading="Tiền Quỹ" subheading="Quản lý danh sách và thông tin Tiền Quỹ" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Tiền Quỹ']]" buttonLabel="Thêm Tiền Quỹ" buttonAction="addTransaction">

        {{-- Component Search & Filter --}}

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-contents.search searchPlaceholder="Tìm kiếm Tiền Quỹ..." wire:model.live.debounce.300ms="search"
                    :items="$items" :count="$transactions->total() ?? 0"/>
            </div>
        </div>


        {{-- Main content area --}}
        <div class="mt-2">
            <div class="theme-table">
                {{-- Desktop Table View --}}
                <div class="hidden md:block ">
                    <table>
                        <thead>
                            <tr>
                                <td colspan="7" class="font-black text-xl text-red-500">
                                    Số tiền hiện tại: {{ number_format($balance, 0, ',', '.') }} ₫
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center w-12">Ngày</th>
                                <th class="text-center">Hạng mục</th>
                                <th>Mô tả</th>
                                <th class="text-center">Thu</th>
                                <th class="text-center">Chi</th>
                                <th class="text-center">File đính kèm</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr class="bg-green-100 ">
                                <td colspan="3" class="text-right font-black text-xl dark:text-black">Tổng cộng:</td>
                                <td class="text-center font-black text-xl text-green-500 ">{{ number_format($totalIncome, 0, ',', '.') }} ₫</td>
                                <td class="text-center font-black text-xl text-red-500">{{ number_format($totalExpense, 0, ',', '.') }} ₫</td>
                                <td colspan="2"></td>
                            </tr>

                            @forelse ($transactions as $transaction)
                                <tr>

                                    <td>{{ $transaction->formatted_transaction_date }}</td>
                                    <td class="text-center">
                                        <flux:badge color="amber">{{ $transaction->item->name ?? '—' }}</flux:badge>
                                    </td>
                                    <td>{{ $transaction->description }}</td>
                                    <td class="text-center {{ $transaction->type === 'income' ? 'text-green-500' : 'text-muted' }}">
                                        {{ $transaction->type === 'income' ? $transaction->formatted_amount : '-' }}
                                    </td>
                                    <td class="text-center {{ $transaction->type === 'expense' ? 'text-red-500' : 'text-muted' }}">
                                        {{ $transaction->type === 'expense' ? $transaction->formatted_amount : '-' }}
                                    </td>
                                    
                                    <td class="text-center">
                                        @if ($transaction->file_name)
                                            <flux:link href="{{ $transaction->file_name }}" target="_blank" variant="ghost">Xem file</flux:link>
                                        @else
                                            -
                                        @endif
                                        
                                    </td>
                                    <td>
                                        <flux:dropdown position="bottom" align="end">
                                            <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                                variant="subtle" />
                                            <flux:menu>
                                                <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                    wire:click='editTransaction({{ $transaction->id }})'>
                                                    Sửa
                                                </flux:menu.item>

                                                <flux:menu.item class="cursor-pointer" icon="trash" variant="danger"
                                                    wire:click='deleteTransaction({{ $transaction->id }})'>
                                                    Xoá
                                                </flux:menu.item>

                                            </flux:menu>
                                        </flux:dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state flex flex-col items-center">
                                            <flux:icon.squares-plus class="w-8 h-8 mb-2" />
                                            <div class="text-sm">Không có dữ liệu</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-3">

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
