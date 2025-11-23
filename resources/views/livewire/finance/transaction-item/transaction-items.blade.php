<div>
    <x-contents.layout heading="Hạng mục chi" subheading="Quản lý danh sách và thông tin hạng mục chi" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Hạng mục chi']]" buttonLabel="Thêm hạng mục chi" buttonAction="addTransactionItem">


        {{-- Main content area --}}

        <div x-data="{
            initSortable() {
                // Desktop sortable
                const desktopEl = document.getElementById('sortable-transaction');
                if (desktopEl && !desktopEl.sortableInstance) {
                    desktopEl.sortableInstance = new Sortable(desktopEl, {
                        animation: 150,
                        handle: '.drag-handle',
                        onEnd: function() {
                            let orderedIds = [];
                            desktopEl.querySelectorAll('[data-id]').forEach(item => {
                                orderedIds.push(item.getAttribute('data-id'));
                            });
                            if (orderedIds.length > 0) {
                                $wire.updateTransactionItemsOrdering(orderedIds);
                            }
                        }
                    });
                }
        
                // Mobile sortable
                const mobileEl = document.getElementById('sortable-transaction-mobile');
                if (mobileEl && !mobileEl.sortableInstance) {
                    mobileEl.sortableInstance = new Sortable(mobileEl, {
                        animation: 150,
                        handle: '.drag-handle',
                        onEnd: function() {
                            let orderedIds = [];
                            mobileEl.querySelectorAll('[data-id]').forEach(item => {
                                orderedIds.push(item.getAttribute('data-id'));
                            });
                            if (orderedIds.length > 0) {
                                $wire.updateTransactionItemsOrdering(orderedIds);
                            }
                        }
                    });
                }
            }
        }" x-init="initSortable()">
            <div class="theme-table">
                {{-- Desktop Table View --}}
                <div class="hidden md:block ">
                    <flux:card class="overflow-hidden border border-accent/20 rounded-xl shadow-sm">
 <flux:table
                            container:class=" {{ $transaction_items->hasPages() ? 'max-h-[calc(100vh-425px)]' : 'max-h-[calc(100vh-339px)]' }}"
                            class="w-full transition [&>tbody>tr]:transition-colors [&>tbody>tr:hover>td]:text-accent-content/70 [&>tbody>tr:hover]:scale-[0.998] [&>tbody>tr:hover]:bg-transparent">
                            <flux:table.columns sticky class="bg-white dark:bg-zinc-700">
                                <flux:table.column class="w-16">STT</flux:table.column>
                                <flux:table.column align="center" class="w-40">Hạng mục</flux:table.column>
                                <flux:table.column align="left">Mô tả</flux:table.column>
                                <flux:table.column align="center">Loại</flux:table.column>
                                <flux:table.column class="w-20"></flux:table.column>
                            </flux:table.columns>

                            <flux:table.rows id="sortable-transaction">
                                @forelse ($transaction_items as $transaction_item)
                                    <flux:table.row wire:key="transaction-desktop-{{ $transaction_item->id }}"
                                        data-id="{{ $transaction_item->id }}">
                                        <flux:table.cell align="center" class="drag-handle cursor-move">
                                            {{ $transaction_item->ordering }}
                                        </flux:table.cell>
                                        <flux:table.cell align="center">
                                            <flux:badge color="{{ $transaction_item->is_system ? 'amber' : 'zinc' }}">
                                                {{ $transaction_item->name }}
                                            </flux:badge>
                                        </flux:table.cell>
                                        <flux:table.cell align="left">
                                            {{ $transaction_item->description }}
                                        </flux:table.cell>
                                        <flux:table.cell align="center">
                                            <flux:badge color="{{ $transaction_item->is_system ? 'amber' : 'zinc' }}">
                                                {{ $transaction_item->source_label }}
                                            </flux:badge>
                                        </flux:table.cell>
                                        <flux:table.cell class="text-right">
                                            @if (!$transaction_item->is_system)
                                                <flux:dropdown position="bottom" align="end">
                                                    <flux:button class="cursor-pointer" icon="ellipsis-horizontal"
                                                        variant="subtle" />
                                                    <flux:menu>
                                                        <flux:menu.item class="cursor-pointer" icon="pencil-square"
                                                            wire:click='editTransactionItem({{ $transaction_item->id }})'>
                                                            Sửa
                                                        </flux:menu.item>
                                                        <flux:menu.item class="cursor-pointer" icon="trash"
                                                            variant="danger"
                                                            wire:click='deleteTransactionItem({{ $transaction_item->id }})'>
                                                            Xoá
                                                        </flux:menu.item>
                                                    </flux:menu>
                                                </flux:dropdown>
                                            @endif
                                        </flux:table.cell>
                                    </flux:table.row>
                                @empty
                                    <flux:table.row>
                                        <flux:table.cell colspan="5">
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
                <div class="md:hidden space-y-3" id="sortable-transaction-mobile">
                    @forelse ($transaction_items as $transaction_item)
                        {{-- 🔹 Nếu là SYSTEM → chỉ hiển thị card, không accordion --}}
                        @if ($transaction_item->is_system)
                            <flux:card class="space-y-6" wire:key="transaction-mobile-{{ $transaction_item->id }}">
                                <div class="flex items-start justify-between gap-3 ">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="drag-handle cursor-move inline-flex items-center justify-center min-w-8 min-h-8 rounded-full bg-accent text-sm font-semibold">
                                            {{ $transaction_item->ordering }}
                                        </span>
                                        <div class="flex flex-col text-left">
                                            <span
                                                class="font-semibold text-accent-text">{{ $transaction_item->name }}</span>
                                            <span
                                                class="text-xs text-accent-text/70">{{ $transaction_item->description }}</span>
                                        </div>
                                    </div>

                                    <flux:badge color="amber">
                                        {{ $transaction_item->source_label }}
                                    </flux:badge>
                                </div>
                            </flux:card>

                            {{-- 🔹 Nếu KHÔNG phải system → rendering accordion --}}
                        @else
                            <flux:accordion wire:key="transaction-mobile-{{ $transaction_item->id }}" transition
                                variant="reverse" data-id="{{ $transaction_item->id }}">
                                <flux:card class="space-y-6">
                                    <flux:accordion.item>

                                        <flux:accordion.heading>
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="drag-handle cursor-move inline-flex items-center justify-center min-w-8 min-h-8 rounded-full bg-accent text-sm font-semibold">
                                                        {{ $transaction_item->ordering }}
                                                    </span>
                                                    <div class="flex flex-col text-left">
                                                        <span
                                                            class="font-semibold text-accent-text">{{ $transaction_item->name }}</span>
                                                        <span
                                                            class="text-xs text-accent-text/70">{{ $transaction_item->description }}</span>
                                                    </div>
                                                </div>
                                                <flux:badge color="zinc">
                                                    {{ $transaction_item->source_label }}
                                                </flux:badge>
                                            </div>
                                        </flux:accordion.heading>

                                        <flux:accordion.content>
                                            <div class="space-y-3 text-sm text-accent-text/90 mt-2">
                                                <div class="pt-3 border-t border-accent/10 flex gap-2">
                                                    <flux:button
                                                        wire:click='editTransactionItem({{ $transaction_item->id }})'
                                                        icon="pencil-square" variant="filled" class="flex-1">
                                                        Sửa
                                                    </flux:button>

                                                    <flux:button
                                                        wire:click='deleteTransactionItem({{ $transaction_item->id }})'
                                                        icon="trash" variant="danger" class="flex-1">
                                                        Xoá
                                                    </flux:button>
                                                </div>
                                            </div>
                                        </flux:accordion.content>
                                    </flux:accordion.item>
                                </flux:card>
                            </flux:accordion>
                        @endif

                    @empty
                        <flux:card class="p-6 text-center">
                            <flux:icon.squares-plus class="w-8 h-8 mb-2 text-muted-foreground" />
                            <flux:text>Không có dữ liệu</flux:text>
                        </flux:card>
                    @endforelse

                </div>

                @if ($transaction_items->hasPages())
                    <div class="py-4 px-5">
                        {{ $transaction_items->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>

    </x-contents.layout>

    <livewire:finance.transaction-item.actions-transaction-item />

</div>
