@extends('layouts.admin')

@section('title', 'إنشاء حملة بريدية جديدة')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">إنشاء حملة بريدية جديدة</h1>
    <a href="{{ route('admin.campaigns.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors bg-white">
        العودة للقائمة
    </a>
</div>

<form action="{{ route('admin.campaigns.store') }}" method="POST" id="campaign-form">
    @csrf
    
    <div class="flex flex-col lg:flex-row lg:items-start gap-6">
        <!-- Main Column (Content) -->
        <div class="w-full lg:w-2/3 space-y-6">
            
            <!-- Subject & Title -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="mb-5">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            عنوان البريد الإلكتروني (Subject)
                        </span>
                    </label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" 
                           class="w-full px-4 py-3 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent transition-all @error('subject') border-red-500 ring-1 ring-red-500 @enderror"
                           placeholder="مثال: نشرتنا الأسبوعية - أحدث المقالات">
                    @error('subject')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                            </svg>
                            العنوان الداخلي (Internal Title)
                        </span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                           class="w-full px-4 py-3 text-xl font-bold border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent transition-all @error('title') border-red-500 ring-1 ring-red-500 @enderror"
                           placeholder="العنوان الرئيسي داخل الرسالة">
                    <p class="mt-1 text-xs text-gray-500">هذا العنوان سيظهر كعنوان رئيسي داخل محتوى الرسالة.</p>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Intro Text -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        النص التمهيدي (Intro Text)
                    </span>
                </label>
                <textarea name="content" id="content" rows="5"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent transition-all resize-none @error('content') border-red-500 ring-1 ring-red-500 @enderror"
                          placeholder="مرحباً بك في نشرتنا الأسبوعية! إليك أحدث المقالات التي قد تهمك...">{{ old('content') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">رسالة ترحيبية قصيرة تظهر قبل قائمة المقالات.</p>
                @error('content')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Article Selection -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100" 
                 x-data="{
                    selectedPosts: @json(old('posts', [])),
                    toggleSelection(id) {
                        if (this.selectedPosts.includes(id)) {
                            this.selectedPosts = this.selectedPosts.filter(p => p !== id);
                        } else {
                            this.selectedPosts.push(id);
                        }
                    },
                    isSelected(id) {
                        return this.selectedPosts.includes(id);
                    }
                 }">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            اختر المقالات للنشرة
                        </span>
                    </label>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        تم اختيار: <span class="font-bold text-brand-primary" x-text="selectedPosts.length">0</span> مقال
                    </span>
                </div>
                
                @error('posts')
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    </div>
                @enderror

                <!-- Hidden inputs for form submission -->
                <template x-for="id in selectedPosts" :key="id">
                    <input type="hidden" name="posts[]" :value="id">
                </template>

                @if($posts->isEmpty())
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500">لا توجد مقالات منشورة حالياً.</p>
                        <a href="{{ route('admin.posts.create') }}" class="mt-3 inline-block text-brand-accent hover:underline">إنشاء مقال جديد</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($posts as $post)
                            {{-- 
                                Article Card with Selection State
                                - Uses box-shadow for selection (respects border-radius)
                                - No gap between card and selection indicator
                                - Brand-accent color via CSS variable
                            --}}
                            <div class="group relative cursor-pointer" 
                                 @click="toggleSelection({{ $post->id }})">
                                
                                <div class="h-full bg-white rounded-xl overflow-hidden transition-all duration-200 
                                            border-2"
                                     :class="isSelected({{ $post->id }}) 
                                         ? 'border-brand-accent shadow-[0_0_0_3px_rgba(195,124,84,0.25)]' 
                                         : 'border-gray-200 hover:border-gray-300 hover:shadow-lg'"
                                     :style="isSelected({{ $post->id }}) ? 'border-color: var(--brand-accent);' : ''">
                                    <!-- Thumbnail -->
                                    <div class="relative aspect-video bg-gray-100 overflow-hidden">
                                        @if($post->featured_image_url)
                                            <img src="{{ $post->featured_image_url }}" 
                                                 alt="{{ $post->featured_image_alt ?? $post->title }}"
                                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Selection Indicator -->
                                        <div class="absolute top-3 left-3 w-6 h-6 rounded-full border-2 border-white flex items-center justify-center shadow-sm transition-all duration-200"
                                             :class="isSelected({{ $post->id }}) ? 'bg-brand-accent' : 'bg-white/80 backdrop-blur-sm'">
                                            <svg class="w-4 h-4 text-white transition-opacity duration-200"
                                                 :class="isSelected({{ $post->id }}) ? 'opacity-100' : 'opacity-0'"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="p-4">
                                        <h3 class="font-bold text-gray-800 text-sm line-clamp-2 mb-2 group-hover:text-brand-primary transition-colors">
                                            {{ $post->title }}
                                        </h3>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $post->published_at?->translatedFormat('j M Y') ?? 'غير منشور' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Sidebar Column -->
        <div class="w-full lg:w-1/3 lg:sticky lg:top-6 h-fit space-y-5">
            
            <!-- Progress Stepper -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 text-sm">خطوات الإنشاء</h3>
                
                <div class="relative">
                    <!-- Vertical Line -->
                    <div class="absolute right-[11px] top-6 bottom-6 w-0.5 bg-gray-200"></div>
                    
                    <!-- Step 1: Content (Active) -->
                    <div class="flex items-center gap-3 mb-5 relative">
                        <div class="w-6 h-6 rounded-full bg-brand-accent flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                            <span class="text-white text-xs font-bold">1</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">المحتوى</p>
                            <p class="text-xs text-brand-accent">الخطوة الحالية</p>
                        </div>
                    </div>
                    
                    <!-- Step 2: Preview (Pending) -->
                    <div class="flex items-center gap-3 mb-5 relative">
                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center flex-shrink-0 z-10">
                            <span class="text-gray-400 text-xs font-bold">2</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">المعاينة</p>
                            <p class="text-xs text-gray-400">مراجعة الرسالة</p>
                        </div>
                    </div>
                    
                    <!-- Step 3: Sending (Pending) -->
                    <div class="flex items-center gap-3 relative">
                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center flex-shrink-0 z-10">
                            <span class="text-gray-400 text-xs font-bold">3</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">الإرسال</p>
                            <p class="text-xs text-gray-400">إرسال للمشتركين</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <div class="space-y-3">
                    <!-- Primary Button: Save & Continue -->
                    <button type="submit" 
                            name="action"
                            value="continue"
                            class="w-full px-6 py-3.5 bg-brand-accent text-white rounded-xl hover:bg-brand-accent-hover transition-all font-bold shadow-md hover:shadow-lg flex items-center justify-center gap-2 group">
                        <span>حفظ ومتابعة</span>
                        <svg class="w-5 h-5 transform rotate-180 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                    
                    <!-- Secondary Button: Save as Draft -->
                    <button type="submit" 
                            name="action" 
                            value="draft"
                            class="w-full px-6 py-3 bg-white text-gray-600 border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        <span>حفظ كمسودة وخروج</span>
                    </button>
                </div>
                
                <!-- Helper Text -->
                <p class="text-xs text-gray-400 text-center mt-3">
                    سيتم حفظ المحتوى كمسودة يمكنك تعديلها لاحقاً
                </p>
            </div>

            <!-- Tips Card -->
            <div class="bg-blue-50 p-5 rounded-xl">
                <h3 class="font-bold text-slate-700 mb-3 text-sm flex items-center gap-2">
                    💡 نصائح سريعة
                </h3>
                <ul class="space-y-2.5">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-slate-600">اختر عنوان بريد جذاب ومختصر</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-slate-600">3-5 مقالات هو العدد المثالي</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-slate-600">اكتب نص ترحيبي شخصي وممميز</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-slate-600">راجع المعاينة قبل الإرسال</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
    /* Post card selection styles */
    .post-card .card-content {
        position: relative;
    }
    
    .post-card .post-checkbox:checked + .card-content {
        border-color: var(--color-brand-accent, #f97316);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }
    
    .post-card .post-checkbox:checked + .card-content .selection-indicator {
        background-color: var(--color-brand-accent, #f97316);
        border-color: var(--color-brand-accent, #f97316);
    }
    
    .post-card .post-checkbox:checked + .card-content .check-icon {
        opacity: 1;
    }
    
    /* Line clamp for title */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
