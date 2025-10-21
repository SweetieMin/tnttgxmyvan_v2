@props([
    'locations' => [],
    'seasons' => [],
    'searchPlaceholder' => 'Tìm kiếm...',
])

<!-- Search & Filter -->

    <div class="bg-accent-background text-accent-text rounded-2xl shadow-sm mb-6 transition-colors">
        <div class="p-6">

            {{-- Hàng ngang chính --}}
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

                {{-- LEFT: Search + filters --}}
                <div
                    class="grid grid-cols-1 md:grid-cols-{{ (!empty($locations) && count($locations) > 0 ? 1 : 0) +
                        (!empty($seasons) && count($seasons) > 0 ? 1 : 0) +
                        1 }} gap-6 flex-1">

                    {{-- Search --}}
                    <div>
                        <label for="search" class="block text-sm font-medium mb-1 opacity-70">
                            Tìm kiếm
                        </label>
                        <input wire:model.live="search" type="text" id="search"
                            placeholder="{{ $searchPlaceholder }}"
                            class=" w-full md:w-80 px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 
                                   bg-white dark:bg-zinc-900 text-sm 
                                   focus:ring-2 focus:ring-accent focus:outline-none transition" />
                    </div>

                    {{-- Location Filter --}}
                    @if (!empty($locations) && count($locations) > 0)
                        <div>
                            <label for="locationFilter" class="block text-sm font-medium mb-1 opacity-70">
                                Lọc theo cơ sở
                            </label>
                            <select wire:model.live="locationFilter" id="locationFilter"
                                class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 
                                       bg-white dark:bg-zinc-900 text-sm 
                                       focus:ring-2 focus:ring-accent focus:outline-none transition">
                                <option value="">Tất cả cơ sở</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Season Filter --}}
                    @if (!empty($seasons) && count($seasons) > 0)
                        <div>
                            <label for="seasonFilter" class="block text-sm font-medium mb-1 opacity-70">
                                Lọc theo học kỳ
                            </label>
                            <select wire:model.live="seasonFilter" id="seasonFilter"
                                class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 
                                       bg-white dark:bg-zinc-900 px-4 py-3 text-sm 
                                       focus:ring-2 focus:ring-accent focus:outline-none transition">
                                <option value="">Tất cả học kỳ</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->id }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                {{-- RIGHT: Per Page --}}
                <div class="flex-shrink-0">
                    <label for="perPage" class="block text-sm font-medium mb-1 opacity-70">
                        Dòng/trang
                    </label>
                    <select wire:model.live="perPage" id="perPage"
                        class=" w-full md:w-20 rounded-xl border border-zinc-200 dark:border-zinc-700 
                               bg-white dark:bg-zinc-900 px-4 py-3 text-sm 
                               focus:ring-2 focus:ring-accent focus:outline-none transition">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

