@extends('layouts.admin')

@section('title', 'إدارة الحملة: ' . $campaign->subject)

@section('content')
<div x-data="{ 
    showConfirmModal: false,
    confirmed: false,
    isSending: false,
    testEmail: '',
    testSending: false,
    testSuccess: false
}">

    {{-- Flash messages are handled globally by layouts.admin --}}
    {{-- No duplicate flash block needed here --}}

    <!-- Breadcrumbs & Header -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3 text-sm text-gray-500">
            <a href="{{ route('admin.campaigns.index') }}" class="hover:text-brand-accent transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                الحملات البريدية
            </a>
            <span class="text-gray-300">|</span>
            <span class="text-gray-800 font-medium">{{ Str::limit($campaign->subject, 40) }}</span>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Status Badge in Header -->
            @if($campaign->isSent())
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    تم الإرسال
                </span>
            @elseif($campaign->isSending())
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    جارٍ الإرسال...
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    مسودة
                </span>
            @endif
            
            @if($campaign->isDraft())
                <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors bg-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    تعديل المحتوى
                </a>
            @endif
        </div>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold text-brand-primary">{{ $campaign->subject }}</h1>
        <p class="text-gray-500 mt-2 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            معاينة وإدارة الحملة البريدية
        </p>
    </div>

    <!-- Main Content Grid (RTL: First child appears on Right) -->
    <div class="flex flex-col lg:flex-row lg:items-start gap-8">
        
        <!-- Right Column: Email Preview (First in DOM = Right side in RTL) -->
        <div class="w-full lg:w-2/3">
            
            <!-- Preview Header -->
            <div class="bg-white rounded-t-xl border border-b-0 border-gray-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    </div>
                    <span class="text-sm text-gray-500 font-medium">معاينة البريد الإلكتروني</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    وضع المعاينة
                </div>
            </div>
            
            <!-- Preview Container (Browser Frame Style) -->
            <div class="bg-gray-100 border border-gray-200 rounded-b-xl p-6 shadow-inner overflow-hidden" style="max-height: 80vh; overflow-y: auto;">
                
                <!-- Rendered Email -->
                <div class="mx-auto" style="max-width: 600px;">
                    @include('emails.campaign', ['campaign' => $campaign])
                </div>
                
            </div>
            
        </div>
        
        <!-- Left Column: Control Panel (Second in DOM = Left side in RTL) -->
        <div class="w-full lg:w-1/3 space-y-6 lg:sticky lg:top-8 h-fit">
            
            <!-- Campaign Summary Card (Consolidated) -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 pb-3 border-b flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    ملخص الحملة
                </h3>
                
                <div class="space-y-4">
                    <!-- Audience Highlight -->
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                        <div class="flex items-center justify-between">
                            <span class="text-blue-700 font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                المستلمون
                            </span>
                            <span class="text-2xl font-bold text-blue-700">{{ number_format($subscriberCount) }}</span>
                        </div>
                        <p class="text-xs text-blue-600 mt-2">
                            @if($subscriberCount > 0)
                                سيتم الإرسال لهؤلاء المشتركين النشطين
                            @else
                                لا يوجد مشتركين نشطين حالياً
                            @endif
                        </p>
                    </div>
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-gray-50 rounded-lg text-center">
                            <div class="text-lg font-bold text-gray-800">{{ $campaign->posts->count() }}</div>
                            <div class="text-xs text-gray-500">مقالات</div>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg text-center">
                            <div class="text-lg font-bold text-gray-800">{{ $campaign->created_at->translatedFormat('j M') }}</div>
                            <div class="text-xs text-gray-500">تاريخ الإنشاء</div>
                        </div>
                    </div>
                    
                    @if($campaign->sent_at)
                        <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-center gap-2 text-green-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium">تم الإرسال: {{ $campaign->sent_at->translatedFormat('j M Y - H:i') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Test Send Card -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 pb-3 border-b flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    أرسل نسخة للمراجعة
                </h3>
                
                <!-- Success Message -->
                <div x-show="testSuccess" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center gap-2 text-green-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium">تم إرسال النسخة التجريبية بنجاح!</span>
                    </div>
                </div>
                
                <form action="{{ route('admin.campaigns.send-test', $campaign) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-xs text-gray-500 mb-1 block">البريد الإلكتروني للاختبار</label>
                        <input type="email" name="email" x-model="testEmail" placeholder="example@email.com" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent text-left" dir="ltr">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" 
                            :disabled="!testEmail"
                            :class="{ 'opacity-50 cursor-not-allowed': !testEmail }"
                            class="w-full px-4 py-2.5 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors font-medium flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span>إرسال نسخة تجريبية</span>
                    </button>
                </form>
            </div>
            
            <!-- Final Action Section -->
            @if($campaign->isDraft())
                <section class="mt-8" x-data="{
                    showModal: false,
                    confirmed: false,
                    isSending: false,
                    pollInterval: null,
                    statusMessage: '',
                    
                    // Start polling for status changes
                    startPolling() {
                        this.statusMessage = 'جارٍ إرسال الحملة...';
                        
                        // Poll every 3 seconds
                        this.pollInterval = setInterval(() => {
                            this.checkStatus();
                        }, 3000);
                        
                        // Safety: stop polling after 5 minutes max
                        setTimeout(() => {
                            this.stopPolling();
                        }, 300000);
                    },
                    
                    // Check campaign status via API
                    async checkStatus() {
                        try {
                            const response = await fetch('{{ route('admin.campaigns.status', $campaign) }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            
                            if (response.ok) {
                                const data = await response.json();
                                
                                if (data.status === 'sent') {
                                    this.statusMessage = 'تم الإرسال بنجاح! جارٍ التحديث...';
                                    this.stopPolling();
                                    // Reload page to show success state
                                    setTimeout(() => window.location.reload(), 1000);
                                } else if (data.status === 'sending') {
                                    this.statusMessage = 'جارٍ الإرسال... (' + (data.progress || 0) + '%)';
                                } else if (data.status === 'failed') {
                                    this.statusMessage = 'فشل الإرسال!';
                                    this.stopPolling();
                                    setTimeout(() => window.location.reload(), 2000);
                                }
                            }
                        } catch (error) {
                            console.error('Status check failed:', error);
                        }
                    },
                    
                    // Stop polling
                    stopPolling() {
                        if (this.pollInterval) {
                            clearInterval(this.pollInterval);
                            this.pollInterval = null;
                        }
                    },
                    
                    // Handle form submit
                    handleSubmit() {
                        this.isSending = true;
                        this.startPolling();
                        return true; // Allow form to submit
                    }
                }">
                    <!-- Section Divider -->
                    <div class="border-t border-gray-200 mb-4"></div>
                    
                    <!-- Section Label -->
                    <p class="text-xs text-center text-gray-400 uppercase tracking-wider mb-4">إجراء نهائي</p>
                    
                    <!-- Send Campaign Card -->
                    <div class="bg-white p-6 rounded-xl border-2 border-brand-accent/30 shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-accent -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            إرسال الحملة
                        </h3>
                        
                        <!-- Warning Alert -->
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg mb-4">
                            <p class="text-sm text-amber-800">
                                <strong>⚠️ إجراء نهائي:</strong> بعد الإرسال، لا يمكن التراجع أو التعديل.
                            </p>
                            <p class="text-xs text-amber-700 mt-1">
                                سيتم إرسال الرسالة إلى <strong>{{ number_format($subscriberCount) }}</strong> مشترك نشط.
                            </p>
                        </div>
                        
                        <!-- Confirmation Checkbox -->
                        <label class="flex items-start gap-3 mb-4 cursor-pointer group">
                            <input type="checkbox" 
                                   x-model="confirmed"
                                   class="mt-0.5 w-5 h-5 rounded border-gray-300 text-brand-accent focus:ring-brand-accent transition-all">
                            <span class="text-sm text-gray-700 group-hover:text-gray-900">
                                أؤكد أنني راجعت محتوى الرسالة وأريد إرسالها للجميع
                            </span>
                        </label>
                        
                        <!-- Open Modal Button -->
                        <button type="button"
                                @click="showModal = true"
                                :disabled="!confirmed || {{ $subscriberCount }} === 0"
                                :class="{ 
                                    'opacity-50 cursor-not-allowed': !confirmed || {{ $subscriberCount }} === 0,
                                    'hover:bg-brand-accent-hover hover:shadow-xl transform hover:-translate-y-0.5': confirmed && {{ $subscriberCount }} > 0
                                }"
                                class="w-full px-6 py-4 bg-brand-accent text-white rounded-xl transition-all duration-200 font-bold text-lg shadow-lg flex items-center justify-center gap-3">
                            <svg class="w-6 h-6 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            إرسال للجميع ← {{ number_format($subscriberCount) }} مشترك
                        </button>
                    </div>
                    
                    <!-- Confirmation Modal -->
                    <div x-show="showModal"
                         x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4"
                         @keydown.escape.window="showModal = false">
                        
                        <!-- Backdrop -->
                        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" 
                             @click="showModal = false"></div>
                        
                        <!-- Modal Content -->
                        <div x-show="showModal"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden z-10">
                            
                            <!-- Modal Header (with accent bar) -->
                            <div class="bg-gradient-to-r from-brand-accent to-amber-500 h-1.5"></div>
                            
                            <div class="p-6">
                                <!-- Icon & Title -->
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-amber-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">هل أنت متأكد؟</h3>
                                    <p class="text-gray-500 mt-2">
                                        أنت على وشك إرسال هذه الحملة إلى 
                                        <span class="font-bold text-brand-accent">{{ number_format($subscriberCount) }}</span>
                                        مشترك.
                                    </p>
                                </div>
                                
                                <!-- Campaign Summary -->
                                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">العنوان:</span>
                                            <span class="font-medium text-gray-800 truncate max-w-[200px]">{{ $campaign->subject }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">المقالات:</span>
                                            <span class="font-medium text-gray-800">{{ $campaign->posts->count() }} مقالات</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">المستلمون:</span>
                                            <span class="font-bold text-brand-accent">{{ number_format($subscriberCount) }} مشترك</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Warning -->
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-6">
                                    <p class="text-sm text-red-700 text-center font-medium">
                                        ⚠️ هذا الإجراء لا يمكن التراجع عنه
                                    </p>
                                </div>
                                
                                <!-- Actions -->
                                <div class="grid grid-cols-2 gap-3 items-stretch">
                                    <!-- Submit Form (Right side in RTL) -->
                                    <form action="{{ route('admin.campaigns.send', $campaign) }}" 
                                          method="POST"
                                          class="h-full"
                                          @submit="handleSubmit()">
                                        @csrf
                                        <button type="submit"
                                                :disabled="isSending"
                                                :class="{ 'opacity-75 cursor-wait': isSending }"
                                                class="w-full h-full px-4 py-3 bg-brand-accent text-white rounded-xl hover:bg-brand-accent-hover transition-all font-bold flex items-center justify-center gap-2 disabled:hover:bg-brand-accent">
                                            <!-- Loading Spinner -->
                                            <svg x-show="isSending" 
                                                 x-cloak
                                                 class="animate-spin w-5 h-5" 
                                                 fill="none" 
                                                 viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <!-- Send Icon (hidden when loading) -->
                                            <svg x-show="!isSending" 
                                                 class="w-5 h-5 -rotate-45" 
                                                 fill="none" 
                                                 stroke="currentColor" 
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                            <span x-text="isSending ? 'جارٍ الإرسال...' : 'نعم، أرسل الآن'">نعم، أرسل الآن</span>
                                        </button>
                                    </form>
                                    
                                    <!-- Cancel Button (Left side in RTL) -->
                                    <button type="button"
                                            @click="showModal = false"
                                            :disabled="isSending"
                                            class="w-full h-full px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium disabled:opacity-50">
                                        إلغاء
                                    </button>
                                </div>
                                
                                <!-- Status Message (shown during polling) -->
                                <div x-show="isSending && statusMessage" 
                                     x-cloak
                                     x-transition
                                     class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-center">
                                    <div class="flex items-center justify-center gap-2 text-blue-700">
                                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span x-text="statusMessage" class="text-sm font-medium"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @else
                <!-- Sent Success State -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl border border-green-200">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-green-800">تم إرسال الحملة بنجاح! 🎉</p>
                            <p class="text-sm text-green-700 mt-1">
                                تم الإرسال في {{ $campaign->sent_at?->translatedFormat('j M Y') ?? 'الآن' }} الساعة {{ $campaign->sent_at?->translatedFormat('H:i') ?? '--:--' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
        </div>
        
    </div>

</div>
@endsection
