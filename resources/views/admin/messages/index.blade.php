@extends('layouts.admin')

@section('title', 'الرسائل')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">الرسائل</h1>
            <p class="text-gray-500 mt-1">إدارة رسائل نموذج التواصل ({{ $totalCount }} رسالة، {{ $unreadCount }} غير مقروءة)</p>
        </div>
        
        <div class="flex items-center gap-3">
            @if($unreadCount > 0)
                <form action="{{ route('admin.messages.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-accent text-white text-sm font-medium rounded-lg hover:bg-opacity-90 transition-colors">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        تحديد الكل كمقروء
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Filters & Search (Standardized with Posts Filter) --}}
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('admin.messages.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            {{-- Search Input --}}
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث في الرسائل..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent">
            
            {{-- Status Filter --}}
            <select name="filter" class="py-2 pr-8 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-accent">
                <option value="">جميع الرسائل</option>
                <option value="unread" {{ request('filter') === 'unread' ? 'selected' : '' }}>غير مقروءة</option>
                <option value="read" {{ request('filter') === 'read' ? 'selected' : '' }}>مقروءة</option>
            </select>
            
            {{-- Filter Button --}}
            <button type="submit" class="px-6 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800 whitespace-nowrap font-medium transition-colors">
                تطبيق
            </button>
            
            {{-- Clear Button (Conditional) --}}
            @if(request()->hasAny(['search', 'filter']))
                <a href="{{ route('admin.messages.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-200 text-center whitespace-nowrap transition-colors">
                    مسح
                </a>
            @endif
        </form>
    </div>


    {{-- Messages Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($messages->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">المرسل</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">الرسالة</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">التاريخ</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($messages as $message)
                            <tr class="hover:bg-gray-50 transition-colors {{ !$message->is_read ? 'bg-blue-50/50' : '' }}">
                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(!$message->is_read)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <span class="w-2 h-2 ml-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                                            جديد
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            مقروء
                                        </span>
                                    @endif
                                </td>
                                
                                {{-- Sender Info --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-brand-accent to-amber-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ mb_substr($message->name, 0, 1) }}
                                        </div>
                                        <div class="mr-3">
                                            <p class="text-sm font-medium text-gray-900 {{ !$message->is_read ? 'font-bold' : '' }}">
                                                {{ $message->name }}
                                            </p>
                                            <p class="text-xs text-gray-500" dir="ltr">{{ $message->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Message Snippet --}}
                                <td class="px-6 py-4 hidden md:table-cell">
                                    <p class="text-sm text-gray-600 truncate max-w-xs {{ !$message->is_read ? 'font-medium' : '' }}">
                                        {{ $message->snippet }}
                                    </p>
                                </td>
                                
                                {{-- Date --}}
                                <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                    <div class="text-sm text-gray-500">
                                        {{ $message->created_at->diffForHumans() }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $message->created_at->format('Y/m/d') }}
                                    </div>
                                </td>
                                
                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.messages.show', $message) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-brand-accent text-white text-xs font-medium rounded-lg hover:bg-opacity-90 transition-colors hover:text-white hover:bg-amber-700 ">
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            عرض
                                        </a>
                                        
                                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 transition-colors">
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

            {{-- Pagination --}}
            @if($messages->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $messages->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="px-6 py-16 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">لا توجد رسائل</h3>
                <p class="mt-2 text-gray-500">
                    @if(request('filter') || request('search'))
                        لم يتم العثور على رسائل تطابق البحث
                    @else
                        ستظهر الرسائل الواردة من نموذج التواصل هنا
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
