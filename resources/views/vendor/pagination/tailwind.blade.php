@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        
        {{-- Mobile Pagination (Simplified) --}}
        <div class="flex justify-between flex-1 sm:hidden w-full">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-not-allowed rounded-lg">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-brand-accent hover:text-white hover:border-brand-accent transition-all duration-200">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            <span class="text-sm text-gray-500">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-brand-accent hover:text-white hover:border-brand-accent transition-all duration-200">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-not-allowed rounded-lg">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop Pagination --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            
            {{-- Results Info --}}
            <div>
                <p class="text-sm text-gray-500">
                    <span class="font-medium text-gray-700">{{ $paginator->firstItem() }}</span>
                    -
                    <span class="font-medium text-gray-700">{{ $paginator->lastItem() }}</span>
                    من
                    <span class="font-medium text-gray-700">{{ $paginator->total() }}</span>
                </p>
            </div>

            {{-- Page Numbers --}}
            <div>
                <span class="relative z-0 inline-flex items-center gap-1">
                    
                    {{-- Previous Page Arrow --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="relative inline-flex items-center p-2.5 text-gray-300 bg-white border border-gray-200 cursor-not-allowed rounded-lg">
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="relative inline-flex items-center p-2.5 text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-brand-accent hover:text-white hover:border-brand-accent hover:shadow-md focus:outline-none focus:ring-2 focus:ring-brand-accent focus:ring-offset-1 transition-all duration-200">
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($elements as $element)
                        {{-- Ellipsis Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true" class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-400 cursor-default">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Page Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    {{-- Active Page --}}
                                    <span aria-current="page" class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-brand-accent border border-brand-accent rounded-lg shadow-md cursor-default">
                                        {{ $page }}
                                    </span>
                                @else
                                    {{-- Inactive Page --}}
                                    <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}" class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-brand-accent hover:text-white hover:border-brand-accent hover:shadow-md focus:outline-none focus:ring-2 focus:ring-brand-accent focus:ring-offset-1 transition-all duration-200">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Arrow --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="relative inline-flex items-center p-2.5 text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-brand-accent hover:text-white hover:border-brand-accent hover:shadow-md focus:outline-none focus:ring-2 focus:ring-brand-accent focus:ring-offset-1 transition-all duration-200">
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="relative inline-flex items-center p-2.5 text-gray-300 bg-white border border-gray-200 cursor-not-allowed rounded-lg">
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                    
                </span>
            </div>
        </div>
    </nav>
@endif
