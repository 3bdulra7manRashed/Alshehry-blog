@extends('layouts.admin')

@section('title', 'الحملات البريدية')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-serif font-bold text-brand-primary">الحملات البريدية</h1>
    <a href="{{ route('admin.campaigns.create') }}" class="flex items-center px-4 py-2 bg-brand-accent text-white rounded-md hover:bg-amber-700 hover:text-white transition-colors shadow-sm">
        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        حملة جديدة
    </a>
</div>

<!-- Campaigns Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">الحملة</th>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">المقالات</th>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">الحالة</th>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">التاريخ</th>
                    <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($campaigns as $campaign)
                    {{-- 
                        Row with clickable behavior:
                        - Entire row has hover state for visual feedback
                        - Campaign title is the clickable element (semantic <a> tag)
                        - View = implicit (click title), Edit/Delete = explicit buttons
                    --}}
                    <tr class="group transition-colors duration-150 hover:bg-gray-50/80">
                        {{-- Campaign Title Cell (Primary Click Target) --}}
                        <td class="px-4 md:px-6 py-4 min-w-[200px]">
                            <div class="flex flex-col items-center text-center gap-y-2 md:block md:text-center">
                                {{-- Clickable Campaign Title --}}
                                <a href="{{ route('admin.campaigns.show', $campaign) }}" 
                                   class="inline-flex items-center gap-2 text-sm font-medium text-brand-primary 
                                          hover:text-brand-accent transition-colors duration-150
                                          focus:outline-none focus:underline focus:text-brand-accent
                                          group/link">
                                    <span>{{ $campaign->subject }}</span>
                                    {{-- Subtle arrow indicator on hover --}}
                                    <svg class="w-3.5 h-3.5 opacity-0 -translate-x-1 transition-all duration-200 
                                                group-hover/link:opacity-100 group-hover/link:translate-x-0
                                                text-brand-accent rtl:rotate-180" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                <p class="text-xs text-gray-500 mt-1 hidden md:block">{{ Str::limit($campaign->title, 50) }}</p>
                                <!-- Mobile: Status Badge -->
                                <div class="md:hidden">
                                    @if($campaign->isSent())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            تم الإرسال
                                        </span>
                                    @elseif($campaign->isSending())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            جارٍ الإرسال
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            مسودة
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        {{-- Posts Count --}}
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center hidden md:table-cell">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ $campaign->posts_count }} مقال
                            </span>
                        </td>
                        
                        {{-- Status Badge --}}
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-center hidden md:table-cell">
                            @if($campaign->isSent())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    تم الإرسال
                                </span>
                            @elseif($campaign->isSending())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    جارٍ الإرسال
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    مسودة
                                </span>
                            @endif
                        </td>
                        
                        {{-- Date --}}
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center hidden md:table-cell">
                            <div class="flex flex-col items-center">
                                <span class="text-gray-900 font-medium">
                                    @if($campaign->sent_at)
                                        {{ $campaign->sent_at->format('Y/m/d') }}
                                    @else
                                        {{ $campaign->created_at->format('Y/m/d') }}
                                    @endif
                                </span>
                                <span class="text-xs text-gray-400">
                                    @if($campaign->sent_at)
                                        أُرسلت
                                    @else
                                        أُنشئت
                                    @endif
                                </span>
                            </div>
                        </td>
                        
                        {{-- Actions --}}
                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <div class="flex items-center gap-2 justify-center flex-wrap">
                                {{-- Edit Button (only for drafts) --}}
                                @if($campaign->isDraft())
                                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md 
                                              hover:bg-blue-100 hover:text-blue-800 font-medium text-xs 
                                              transition-colors duration-200
                                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                                       title="تعديل الحملة">
                                        <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        تعديل
                                    </a>
                                @endif
                                
                                {{-- Delete Button --}}
                                <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="js-confirm inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-md 
                                                   hover:bg-red-100 hover:text-red-800 font-medium text-xs 
                                                   transition-colors duration-200
                                                   focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                            data-confirm-message="هل أنت متأكد من حذف هذه الحملة البريدية؟"
                                            title="حذف الحملة">
                                        <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 md:px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <p class="text-gray-500 text-lg mb-2">لا توجد حملات بريدية</p>
                                <p class="text-gray-400 text-sm mb-4">ابدأ بإنشاء أول حملة بريدية لإرسالها للمشتركين</p>
                                <a href="{{ route('admin.campaigns.create') }}" class="flex items-center px-4 py-2 bg-brand-accent text-white rounded-md hover:bg-amber-700 hover:text-white transition-colors shadow-sm">
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    إنشاء حملة جديدة
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($campaigns->hasPages())
    <div class="mt-6" dir="ltr">
        {{ $campaigns->links() }}
    </div>
@endif
@endsection
