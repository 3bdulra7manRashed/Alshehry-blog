@extends('layouts.admin')

@section('title', 'مكتبة الملفات والمرفقات')

@section('content')
<div class="mb-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-serif font-bold text-gray-900 mb-1">مكتبة الملفات</h1>
                <p class="text-gray-500 text-sm">إدارة الملفات وروابط التحميل المباشرة</p>
            </div>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-brand-accent rounded-full inline-block"></span>
                    رفع ملف جديد
                </h2>
            </div>
        </div>
        
        <form action="{{ route('admin.downloads.store') }}" method="POST" enctype="multipart/form-data" novalidate class="flex flex-col md:flex-row items-end gap-5">
            @csrf
            
            <!-- Title Input -->
            <div class="w-full md:flex-[2]">
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">عنوان الملف</label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="block w-full pr-10 pl-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent transition-all {{ $errors->has('title') ? 'border-red-300 bg-red-50 text-red-900 placeholder-red-300 focus:ring-red-500' : '' }}"
                           placeholder="أدخل عنواناً وصفياً للملف...">
                </div>
                @error('title')
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Input -->
            <div class="w-full md:flex-1">
                <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">الملف المرفق</label>
                <div class="relative">
                    <input type="file" name="file" id="file" required
                           class="block w-full text-sm text-gray-500
                                  file:ml-4 file:py-2.5 file:px-4
                                  file:rounded-l-lg file:rounded-r-none file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-brand-primary file:text-white
                                  hover:file:bg-opacity-90 file:cursor-pointer
                                  bg-gray-50 border border-gray-200 rounded-lg focus:outline-none {{ $errors->has('file') ? 'border-red-300 bg-red-50' : '' }}">
                </div>
                @error('file')
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto h-[44px] inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-bold rounded-lg text-white bg-brand-accent hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-accent transition-all shadow-sm">
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    رفع الملف
                </button>
            </div>
        </form>
    </div>

    <!-- Files List -->
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-base font-bold text-gray-900">الملفات المرفوعة</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                {{ $downloads->count() }} ملف
            </span>
        </div>

        @if($downloads->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تفاصيل الملف</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الرفع</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">التحميلات</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($downloads as $download)
                            <tr class="group hover:bg-gray-50 transition-colors">
                                {{-- File Details Column --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-white group-hover:text-brand-accent transition-colors">
                                            @if(Str::contains($download->mime_type, 'image'))
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            @elseif(Str::contains($download->mime_type, 'pdf'))
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            @else
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            @endif
                                        </div>
                                        <div class="mr-4">
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-brand-primary transition-colors">{{ $download->title }}</div>
                                            {{-- Arabic-friendly Slug Display --}}
                                            <div class="text-xs text-gray-400 font-mono mt-0.5 text-right" dir="auto" title="{{ $download->public_url }}">
                                                {{ Str::limit($download->slug, 30) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status Toggle Column --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                                    <form action="{{ route('admin.downloads.toggle', $download) }}" method="POST" class="inline-flex items-center justify-center">
                                        @csrf
                                        @method('PATCH')
                                        <label class="relative inline-flex items-center cursor-pointer" title="{{ $download->is_active ? 'تعطيل التحميل' : 'تفعيل التحميل' }}">
                                            <input type="checkbox" 
                                                   class="sr-only peer" 
                                                   {{ $download->is_active ? 'checked' : '' }}
                                                   onchange="this.form.submit()">
                                            {{-- Toggle Track --}}
                                            <div class="w-11 h-6 bg-gray-200 rounded-full peer 
                                                        peer-checked:bg-green-500
                                                        peer-focus:ring-4 peer-focus:ring-green-100
                                                        after:content-[''] after:absolute after:top-0.5 after:right-0.5
                                                        after:bg-white after:border-gray-300 after:border after:rounded-full 
                                                        after:h-5 after:w-5 after:transition-all after:shadow-sm
                                                        peer-checked:after:translate-x-[-100%] peer-checked:after:border-white
                                                        transition-colors duration-200"></div>
                                            {{-- Status Label --}}
                                            <span class="mr-2 text-xs font-medium {{ $download->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                                {{ $download->is_active ? 'مفعّل' : 'معطّل' }}
                                            </span>
                                        </label>
                                    </form>
                                </td>

                                {{-- Date Column --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle">
                                    <div class="text-sm text-gray-900">{{ $download->created_at->format('Y/m/d') }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $download->created_at->translatedFormat('h:i A') }}</div>
                                </td>

                                {{-- Downloads Count Column --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700">
                                        {{ $download->downloads_count }} تحميل
                                    </span>
                                </td>

                                {{-- Actions Column --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle" x-data="{ copied: false }">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Copy Link Button --}}
                                        <button @click="
                                            navigator.clipboard.writeText('{{ $download->public_url }}');
                                            copied = true;
                                            setTimeout(() => copied = false, 2000);
                                        " 
                                        class="flex items-center justify-center gap-1.5 py-1.5 px-3 rounded transition-all duration-200 text-xs font-medium shadow-sm"
                                        :class="copied 
                                            ? 'bg-green-50 text-green-600 border border-green-200' 
                                            : 'bg-gray-50 hover:bg-brand-accent hover:text-white text-gray-600 border border-gray-200'"
                                        title="نسخ الرابط">
                                            <span class="icon-placeholder">
                                                <template x-if="!copied">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                    </svg>
                                                </template>
                                                <template x-if="copied">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </template>
                                            </span>
                                            <span class="btn-text" x-text="copied ? 'تم النسخ!' : 'نسخ'">نسخ</span>
                                        </button>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('admin.downloads.destroy', $download->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الملف؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="js-confirm p-1.5 text-red-600 hover:text-white hover:bg-red-500 rounded border border-red-100 hover:border-red-500 transition-colors shadow-sm" 
                                                    title="حذف الملف"
                                                    data-confirm-message="هل أنت متأكد من حذف هذا الملف؟">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($downloads->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $downloads->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">المكتبة فارغة</h3>
                <p class="text-gray-500 text-sm max-w-xs mx-auto">لم يتم رفع أي ملفات بعد. استخدم النموذج أعلاه لإضافة أول ملف.</p>
            </div>
        @endif
    </div>
</div>
@endsection
