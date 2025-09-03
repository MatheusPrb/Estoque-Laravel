@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-4 space-x-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-gray-400 cursor-default dark:text-gray-500">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 text-gray-700 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-1 text-gray-500 dark:text-gray-400">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 font-semibold text-white bg-gray-800 rounded-full dark:bg-gray-200 dark:text-gray-800">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 text-gray-700 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 text-gray-700 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="px-3 py-1 text-gray-400 cursor-default dark:text-gray-500">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
