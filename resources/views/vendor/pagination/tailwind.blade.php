@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between mt-4">
        {{-- Nút "Trước" --}}
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 text-sm text-zinc-400 bg-zinc-100 dark:bg-zinc-800 rounded-lg cursor-not-allowed select-none">
                &laquo; Trước
            </span>
        @else
            <button
                wire:click="previousPage"
                wire:loading.attr="disabled"
                rel="prev"
                class="px-4 py-2 text-sm font-medium text-accent-text bg-accent-background/50 border border-accent/20 rounded-lg hover:bg-accent-background/80 transition"
            >
                &laquo; Trước
            </button>
        @endif

        {{-- Danh sách số trang --}}
        <div class="hidden sm:flex items-center space-x-1">
            @foreach ($elements as $element)
                {{-- Dấu "..." --}}
                @if (is_string($element))
                    <span class="px-3 py-2 text-sm text-zinc-400 select-none">{{ $element }}</span>
                @endif

                {{-- Liệt kê các trang --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                aria-current="page"
                                class="px-4 py-2 text-sm font-semibold text-white bg-accent-background rounded-lg shadow-sm cursor-default select-none"
                            >
                                {{ $page }}
                            </span>
                        @else
                            <button
                                wire:click="gotoPage({{ $page }})"
                                class="px-4 py-2 text-sm text-accent-text bg-accent-background/40 border border-accent/10 rounded-lg hover:bg-accent-background/60 transition"
                            >
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Nút "Sau" --}}
        @if ($paginator->hasMorePages())
            <button
                wire:click="nextPage"
                wire:loading.attr="disabled"
                rel="next"
                class="px-4 py-2 text-sm font-medium text-accent-text bg-accent-background/50 border border-accent/20 rounded-lg hover:bg-accent-background/80 transition"
            >
                Sau &raquo;
            </button>
        @else
            <span class="px-4 py-2 text-sm text-zinc-400 bg-zinc-100 dark:bg-zinc-800 rounded-lg cursor-not-allowed select-none">
                Sau &raquo;
            </span>
        @endif
    </nav>


@endif
