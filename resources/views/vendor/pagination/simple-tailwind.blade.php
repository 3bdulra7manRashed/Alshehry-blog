{{--
    Modern "Floating Pills" Simple Pagination
    For use with: ->simplePaginate()
--}}

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center space-x-3 space-x-reverse">
        
        {{-- Previous Button --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gray-100 text-gray-300 text-sm font-medium cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                السابق
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" 
               rel="prev"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white text-gray-600 text-sm font-medium shadow-sm border border-gray-100 hover:bg-brand-accent hover:text-white hover:border-brand-accent hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                السابق
            </a>
        @endif

        {{-- Current Page Indicator --}}
        <span class="inline-flex items-center justify-center min-w-[2.5rem] h-10 px-3 rounded-full bg-brand-accent text-white font-bold text-sm shadow-lg shadow-brand-accent/30">
            {{ $paginator->currentPage() }}
        </span>

        {{-- Next Button --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" 
               rel="next"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white text-gray-600 text-sm font-medium shadow-sm border border-gray-100 hover:bg-brand-accent hover:text-white hover:border-brand-accent hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 transition-all duration-200">
                التالي
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        @else
            <span aria-disabled="true" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gray-100 text-gray-300 text-sm font-medium cursor-not-allowed">
                التالي
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </span>
        @endif
        
    </nav>
@endif
