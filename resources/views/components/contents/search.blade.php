@props([
    'years' => [],
    'searchPlaceholder' => 'Tìm kiếm...',
    'count' => 1,
])

<div class="bg-accent-background text-accent-text rounded-2xl shadow-sm mb-2">
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

            {{-- LEFT: Search + Filters --}}
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Search --}}
                    <div class="flex flex-col">
                        <label for="search" class="block text-sm font-medium mb-1 opacity-70">
                            Tìm kiếm
                        </label>
                        <input wire:model.live="search"
                               type="text"
                               id="search"
                               placeholder="{{ $searchPlaceholder }}"
                               class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 
                                      bg-white dark:bg-zinc-900 text-sm 
                                      focus:ring-2 focus:ring-accent focus:outline-none" />
                    </div>

                    {{-- Filter: Niên khoá --}}
                    @if (!empty($years) && count($years) > 0)
                        <div class="flex flex-col">
                            <label for="yearFilter" class="block text-sm font-medium mb-1 opacity-70">
                                Niên khoá
                            </label>
                            <select wire:model.live="yearFilter" id="yearFilter"
                                    class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 
                                           bg-white dark:bg-zinc-900 px-4 py-3 text-sm 
                                           focus:ring-2 focus:ring-accent focus:outline-none">
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif


                </div>
            </div>

            {{-- RIGHT: Dòng/trang --}}
            @if ($count > 10)
                <div class="flex-shrink-0 md:w-40">
                    <label for="perPage" class="block text-sm font-medium mb-1 opacity-70">
                        Dòng/trang
                    </label>
                    <select wire:model.live="perPage" id="perPage"
                            class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 
                                   bg-white dark:bg-zinc-900 px-4 py-3 text-sm 
                                   focus:ring-2 focus:ring-accent focus:outline-none ">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            @endif
        </div>
    </div>
</div>
