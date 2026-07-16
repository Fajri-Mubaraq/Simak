@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" style="display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pg-btn pg-disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pg-btn" aria-label="{{ __('pagination.previous') }}">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="pg-btn pg-dots">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg-btn pg-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pg-btn" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pg-btn" aria-label="{{ __('pagination.next') }}">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="pg-btn pg-disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif

    </nav>
@endif
