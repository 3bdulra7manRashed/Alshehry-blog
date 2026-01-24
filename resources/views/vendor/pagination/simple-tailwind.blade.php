@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-not-allowed rounded-lg">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-brand-accent hover:text-white hover:border-brand-accent hover:shadow-md focus:outline-none focus:ring-2 focus:ring-brand-accent focus:ring-offset-1 transition-all duration-200">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-brand-accent hover:text-white hover:border-brand-accent hover:shadow-md focus:outline-none focus:ring-2 focus:ring-brand-accent focus:ring-offset-1 transition-all duration-200">
                {!! __('pagination.next') !!}
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @else
            <span class="relative inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-not-allowed rounded-lg">
                {!! __('pagination.next') !!}
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        @endif
    </nav>
@endif
