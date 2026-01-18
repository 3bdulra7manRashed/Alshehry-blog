@extends('layouts.admin')

@section('title', 'عرض الرسالة')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.messages.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                العودة للرسائل
            </a>
        </div>
        
        <div class="flex items-center gap-2">
            {{-- Toggle Read Status --}}
            <form action="{{ route('admin.messages.toggle-read', $message) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center px-4 py-2 {{ $message->is_read ? 'bg-blue-50 text-blue-600 hover:bg-blue-100' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} rounded-lg transition-colors">
                    @if($message->is_read)
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                        </svg>
                        تحديد كغير مقروء
                    @else
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        تحديد كمقروء
                    @endif
                </button>
            </form>
            
            {{-- Delete --}}
            <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" 
                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟ لا يمكن التراجع عن هذا الإجراء.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    حذف الرسالة
                </button>
            </form>
        </div>
    </div>

    {{-- Message Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Sender Header --}}
        <div class="bg-gradient-to-l from-brand-accent/10 to-transparent border-b border-gray-100 px-6 py-5">
            <div class="flex items-start justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-brand-accent to-amber-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        {{ mb_substr($message->name, 0, 1) }}
                    </div>
                    <div class="mr-4">
                        <h2 class="text-xl font-bold text-gray-900">{{ $message->name }}</h2>
                        <div class="flex items-center gap-4 mt-1">
                            <a href="mailto:{{ $message->email }}" class="text-sm text-brand-accent hover:underline flex items-center" dir="ltr">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ $message->email }}
                            </a>
                            @if($message->phone)
                                <a href="tel:{{ $message->phone }}" class="text-sm text-gray-600 hover:text-brand-accent flex items-center" dir="ltr">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $message->phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="text-left">
                    @if(!$message->is_read)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <span class="w-2 h-2 ml-1.5 bg-blue-500 rounded-full"></span>
                            جديد
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            مقروء
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Message Body --}}
        <div class="p-6">
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <p class="text-gray-800 leading-relaxed whitespace-pre-wrap text-base">{{ $message->message }}</p>
            </div>
        </div>

        {{-- Message Metadata --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-500">
                <div class="flex items-center">
                    <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message->created_at->format('Y/m/d - H:i') }}</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message->created_at->diffForHumans() }}</span>
                </div>
                @if($message->ip_address)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                        <span dir="ltr">{{ $message->ip_address }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Reply Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">الرد السريع</h3>
        <div class="flex items-center gap-3">
            <a href="mailto:{{ $message->email }}?subject=رد: رسالة من موقع {{ config('app.name') }}&body=%0A%0A---%0Aفي {{ $message->created_at->format('Y/m/d') }}، كتب {{ $message->name }}:%0A{{ urlencode($message->message) }}" 
               class="inline-flex items-center px-5 py-2.5 bg-brand-accent text-white font-medium rounded-lg hover:bg-opacity-90 transition-colors hover:text-white hover:bg-amber-700 ">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                الرد عبر البريد الإلكتروني
            </a>
            
            @if($message->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" 
                   target="_blank"
                   class="inline-flex items-center px-5 py-2.5 bg-green-500 text-white font-medium rounded-lg hover:bg-green-600 transition-colors hover:text-white ">
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    واتساب
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
