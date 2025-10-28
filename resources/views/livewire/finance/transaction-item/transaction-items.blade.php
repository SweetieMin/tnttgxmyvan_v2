<div>
    <x-contents.layout heading="Hạng mục chi" subheading="Quản lý danh sách và thông tin hạng mục chi" icon="squares-plus"
        :breadcrumb="[['label' => 'Bảng điều khiển', 'url' => route('dashboard')], ['label' => 'Hạng mục chi']]" buttonLabel="Thêm hạng mục chi" buttonAction="addTransactionItem">


        {{-- Main content area --}}
        <div class="mt-2">
            <div x-data="{
                initSortable() {
                    const el = document.getElementById('sortable-transaction');
                    if (el && !el.sortableInstance) {
                        el.sortableInstance = new Sortable(el, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: function() {
                                let orderedIds = [];
                                el.querySelectorAll('[data-id]').forEach(item => { orderedIds.push(item.getAttribute('data-id')); });
                                $wire.updateTransactionItemsOrdering(orderedIds);
                            }
                        });
                    }
                }
            }" x-init="initSortable()">
                <div class="theme-table">
                    {{-- Desktop Table View --}}
                    <div class="hidden md:block ">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-center w-12">STT</th>
                                    <th class="text-center">Hạng mục</th>
                                    <th>Mô tả</th>
                                    <th class="text-center">Loại</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="sortable-transaction" ">
                                @forelse ($transaction_items as $transaction_item)
                                    <tr wire:key="course-desktop-{{ $transaction_item->id }}" data-id="{{ $transaction_item->id }}">

                                        <td class="text-center w-12 drag-handle cursor-move" >{{ $transaction_item->ordering }}</td>
                                        <td class="text-center">

                                            <flux:badge color="{{ $transaction_item->is_system ? 'amber' : 'zinc' }}">
                                                {{ $transaction_item->title }}</flux:badge>
                                        </td>
                                        <td>{{ $transaction_item->description }}</td>
                                        <td class="text-center">

                                            <flux:badge color="{{ $transaction_item->is_system ? 'amber' : 'zinc' }}">
                                                {{ $transaction_item->source_label }}</flux:badge>
                                        </td>
                                        <td class="text-right">
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

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
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


                    @if ($transaction_items->hasPages())
                        <div class="py-4 px-5">
                            {{ $transaction_items->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </x-contents.layout>

    <livewire:finance.transaction-item.actions-transaction-item />

</div>
