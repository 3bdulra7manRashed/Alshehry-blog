@extends('layouts.blog')

{{-- Homepage SEO - Profile type for personal brand --}}


@section('description', 'مدونة صالح الشهري - خبير ريادة الأعمال ومستشار في منشآت. مقالات متخصصة في تأسيس المشاريع الناشئة، التدريب والتطوير، والابتكار. اكتشف أسرار النجاح في عالم ريادة الأعمال.')

@section('keywords', 'صالح الشهري, ريادة الأعمال, تأسيس مشاريع, منشآت, استشارات ريادية, جدة, تدريب وتطوير, مشاريع ناشئة, Saleh Alshehry, مستشار أعمال')

@section('og_type', 'profile')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-5xl">
    
    @if($posts->count() > 0)
        <div class="space-y-12">
            @foreach($posts as $post)
                <article class="border-b border-gray-100 pb-12 last:border-0">
                    @if($post->featured_image_url)
                        <a href="{{ route('post.show', $post->slug) }}" class="block mb-8">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full aspect-video md:aspect-auto md:h-[450px] object-cover rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        </a>
                    @endif
                    
                    <div class="text-center max-w-4xl mx-auto">
                        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-6 leading-tight text-brand-primary">
                            <a href="{{ route('post.show', $post->slug) }}" class="hover:text-brand-accent transition-colors">
                                {{ $post->title }}
                            </a>
                        </h2>

                        @if($post->excerpt)
                            <p class="text-gray-600 leading-loose mb-8 text-base md:text-lg">{{ $post->excerpt }}</p>
                        @endif

                        @include('partials.share-buttons', ['post' => $post])
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16">
            {{ $posts->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-brand-muted text-lg">لا توجد مقالات حالياً.</p>
        </div>
    @endif
</div>
@endsection
