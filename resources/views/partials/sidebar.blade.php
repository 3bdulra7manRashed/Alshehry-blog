<aside id="sidebar" class="hidden fixed inset-y-0 left-0 w-80 bg-white shadow-xl z-50 overflow-y-auto transform transition-transform duration-300 -translate-x-full border-r border-gray-100">
    <div class="p-8">
        <div class="flex justify-between items-center mb-8">
             <!-- Close button on the right for RTL sidebar on left -->
            <button id="close-sidebar" class="text-gray-400 hover:text-brand-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>    
        </div>

        <div class="space-y-8">
            <!-- Search -->
            <div class="bg-brand-secondary p-6 rounded-lg">
                <h3 class="text-lg font-serif font-semibold mb-4">البحث</h3>
                <form action="{{ route('search') }}" method="GET">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث عن مقالات..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent">
                </form>
            </div>

            <!-- Categories -->
            @if(isset($categories) && $categories->count() > 0)
                <div class="bg-brand-secondary p-6 rounded-lg">
                    <h3 class="text-lg font-serif font-semibold mb-4">الأقسام</h3>
                    <ul class="space-y-2">
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('category.show', $category->slug) }}" class="flex items-center justify-between text-sm hover:text-brand-accent transition-colors">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-brand-muted">({{ $category->posts_count }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Recent Posts -->
            @if(isset($recentPosts) && $recentPosts->count() > 0)
                <div class="bg-brand-secondary p-6 rounded-lg">
                    <h3 class="text-lg font-serif font-semibold mb-4">أحدث المقالات</h3>
                    <ul class="space-y-4">
                        @foreach($recentPosts as $recentPost)
                            <li>
                                <a href="{{ route('post.show', $recentPost->slug) }}" class="block group">
                                    <h4 class="text-sm font-medium group-hover:text-brand-accent transition-colors mb-1">
                                        {{ $recentPost->title }}
                                    </h4>
                                    <p class="text-xs text-brand-muted">{{ $recentPost->published_at->format('Y/m/d') }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Most Liked Posts -->
            @if(isset($mostLikedPosts) && $mostLikedPosts->count() > 0)
                <div class="bg-brand-secondary p-6 rounded-lg">
                    <h3 class="text-lg font-serif font-semibold mb-4">المقالات الأكثر إعجاباً</h3>
                    <ul class="space-y-4">
                        @foreach($mostLikedPosts as $likedPost)
                            <li>
                                <a href="{{ route('post.show', $likedPost->slug) }}" class="block group">
                                    <h4 class="text-sm font-medium group-hover:text-brand-accent transition-colors mb-1">
                                        {{ $likedPost->title }}
                                    </h4>
                                    <p class="text-xs text-brand-muted flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                        {{ $likedPost->likes_count ?? 0 }} إعجاب
                                    </p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Most Read Posts -->
            @if(isset($mostReadPosts) && $mostReadPosts->count() > 0)
                <div class="bg-brand-secondary p-6 rounded-lg">
                    <h3 class="text-lg font-serif font-semibold mb-4">المقالات الأكثر قراءة</h3>
                    <ul class="space-y-4">
                        @foreach($mostReadPosts as $readPost)
                            <li>
                                <a href="{{ route('post.show', $readPost->slug) }}" class="block group">
                                    <h4 class="text-sm font-medium group-hover:text-brand-accent transition-colors mb-1">
                                        {{ $readPost->title }}
                                    </h4>
                                    <p class="text-xs text-brand-muted flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        {{ $readPost->views ?? 0 }} مشاهدة
                                    </p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</aside>
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300 opacity-0"></div>
