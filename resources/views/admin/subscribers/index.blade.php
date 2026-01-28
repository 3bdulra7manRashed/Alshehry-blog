@extends('layouts.admin')

@section('title', 'إدارة المشتركين')

@section('content')
<div class="mb-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-serif font-bold text-gray-900 mb-1">إدارة المشتركين</h1>
                <p class="text-gray-500 text-sm">إدارة قائمة مشتركي النشرة البريدية</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <!-- Total Subscribers -->
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-5">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">إجمالي المشتركين</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>

        <!-- Active Subscribers -->
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-5">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">المشتركون النشطون</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active']) }}</p>
                </div>
            </div>
        </div>

        <!-- Unsubscribed -->
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-5">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">ملغو الاشتراك</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['unsubscribed']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Subscriber Section -->
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-brand-accent rounded-full inline-block"></span>
                    إضافة مشترك جديد
                </h2>
            </div>
        </div>
        
        <form action="{{ route('admin.subscribers.store') }}" method="POST" novalidate class="flex flex-col md:flex-row items-end gap-5">
            @csrf
            
            <!-- Email Input -->
            <div class="w-full md:flex-[2]">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني</label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="block w-full pr-10 pl-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent transition-all {{ $errors->has('email') ? 'border-red-300 bg-red-50 text-red-900 placeholder-red-300 focus:ring-red-500' : '' }}"
                           placeholder="example@email.com" dir="ltr">
                </div>
                @error('email')
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name Input (Optional) -->
            <div class="w-full md:flex-1">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">الاسم (اختياري)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="block w-full pr-10 pl-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent transition-all {{ $errors->has('name') ? 'border-red-300 bg-red-50' : '' }}"
                           placeholder="اسم المشترك...">
                </div>
                @error('name')
                    <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto h-[44px] inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-bold rounded-lg text-white bg-brand-accent hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-accent transition-all shadow-sm">
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إضافة مشترك
                </button>
            </div>
        </form>
    </div>

    <!-- Subscribers List -->
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-base font-bold text-gray-900">قائمة المشتركين</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                {{ $subscribers->total() }} مشترك
            </span>
        </div>

        @if($subscribers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المشترك</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الاشتراك</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($subscribers as $subscriber)
                            <tr class="group hover:bg-gray-50 transition-colors {{ !$subscriber->is_active ? 'bg-gray-50/70' : '' }}">
                                {{-- Subscriber Info Column --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ $subscriber->is_active ? 'bg-brand-accent/10 text-brand-accent' : 'bg-gray-200 text-gray-400' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="mr-4">
                                            <div class="text-sm font-bold {{ $subscriber->is_active ? 'text-gray-900' : 'text-gray-500' }}" dir="ltr">
                                                {{ $subscriber->email }}
                                            </div>
                                            @if($subscriber->name)
                                                <div class="text-xs text-gray-400 mt-0.5">{{ $subscriber->name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Joined Date Column --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle">
                                    <div class="text-sm text-gray-900">{{ $subscriber->created_at->format('Y/m/d') }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $subscriber->created_at->translatedFormat('h:i A') }}</div>
                                </td>

                                {{-- Status Column --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                                    @if($subscriber->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            نشط
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            </svg>
                                            ملغي
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions Column --}}
                                <td class="px-6 py-4 whitespace-nowrap align-middle">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Toggle Status Button --}}
                                        <form action="{{ route('admin.subscribers.toggle', $subscriber) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            @if($subscriber->is_active)
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded border transition-colors shadow-sm bg-gray-50 text-gray-600 border-gray-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200"
                                                        title="إلغاء الاشتراك">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    </svg>
                                                    إلغاء
                                                </button>
                                            @else
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded border transition-colors shadow-sm bg-gray-50 text-gray-600 border-gray-200 hover:bg-green-50 hover:text-green-600 hover:border-green-200"
                                                        title="إعادة التفعيل">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    تفعيل
                                                </button>
                                            @endif
                                        </form>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المشترك نهائياً؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="js-confirm p-1.5 text-red-600 hover:text-white hover:bg-red-500 rounded border border-red-100 hover:border-red-500 transition-colors shadow-sm" 
                                                    title="حذف المشترك"
                                                    data-confirm-message="هل أنت متأكد من حذف هذا المشترك نهائياً؟">
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
            
            @if($subscribers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $subscribers->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">لا يوجد مشتركون حالياً</h3>
                <p class="text-gray-500 text-sm max-w-xs mx-auto">قم بإضافة مشترك جديد من النموذج أعلاه أو انتظر اشتراكات جديدة من الموقع.</p>
            </div>
        @endif
    </div>
</div>
@endsection
