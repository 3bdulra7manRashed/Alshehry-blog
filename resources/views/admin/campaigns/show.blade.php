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

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <!-- Page Title & Subtitle -->
        <div>
            <h1 class="text-3xl font-serif font-bold text-brand-primary">{{ $campaign->subject }}</h1>
            <p class="text-gray-500 mt-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                معاينة وإدارة الحملة البريدية
            </p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
            <!-- Edit Button -->
            @if($campaign->isDraft())
                <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors bg-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    تعديل
                </a>
            @endif

            <!-- Back Button -->
            <a href="{{ route('admin.campaigns.index') }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors bg-white">
                عودة للقائمة
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                </svg>
            </a>
        </div>
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
            <div class="bg-gray-100 border border-gray-200 rounded-b-xl p-6 shadow-inner overflow-hidden min-h-[850px]">
                
                <!-- Rendered Email -->
                <div class="mx-auto" style="max-width: 600px;">
                    @include('emails.campaign', ['campaign' => $campaign])
                </div>
                
            </div>
            
        </div>
        
        <!-- Left Column: Control Panel (Second in DOM = Left side in RTL) -->
        <div class="w-full lg:w-1/3 lg:sticky lg:top-6 h-fit lg:self-start space-y-5"
             x-data="{
                status: '{{ $campaign->status }}',
                sentTime: '{{ $campaign->sent_at?->format('h:i A') }}',
                isSending: false,
                statusMessage: '',
                showModal: false,
                pollInterval: null,
                
                init() {
                    if (this.status === 'sending') {
                        this.startPolling();
                    }
                },

                startPolling() {
                    this.statusMessage = 'جارٍ إرسال الحملة...';
                    this.pollInterval = setInterval(() => { this.checkStatus(); }, 3000);
                    setTimeout(() => { this.stopPolling(); }, 300000); // Stop after 5 mins
                },

                async checkStatus() {
                    try {
                        const response = await fetch('{{ route('admin.campaigns.show', $campaign) }}', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.status = data.status;
                            
                            if (this.status === 'sent') {
                                this.stopPolling();
                                this.sentTime = data.sent_at_formatted;
                                this.showModal = false;
                                this.isSending = false;
                            } else if (this.status === 'failed') {
                                this.statusMessage = 'فشل الإرسال!';
                                this.stopPolling();
                                this.isSending = false;
                            }
                        }
                    } catch (error) { console.error('Status check failed:', error); }
                },

                stopPolling() {
                    if (this.pollInterval) {
                        clearInterval(this.pollInterval);
                        this.pollInterval = null;
                    }
                },

                handleSubmit() {
                    this.isSending = true;
                    this.startPolling();
                    return true;
                }
             }">
            
            <!-- Reactive Progress Stepper -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 text-sm">خطوات الحملة</h3>
                
                <div class="relative">
                    <!-- Vertical Line -->
                    <div class="absolute right-[11px] top-6 bottom-6 w-0.5 bg-gray-200"></div>
                    
                    <!-- Step 1: Content (Always Completed) -->
                    <div class="flex items-center gap-3 mb-5 relative">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-green-600">المحتوى</p>
                            <p class="text-xs text-green-500">تم ✓</p>
                        </div>
                    </div>
                    
                    <!-- Step 2: Preview (Reactive) -->
                    <div class="flex items-center gap-3 mb-5 relative">
                        <!-- Green Check (Completed) -->
                        <div x-show="status === 'sent' || status === 'sending'" class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div x-show="status === 'sent' || status === 'sending'">
                            <p class="text-sm font-medium text-green-600">المعاينة</p>
                            <p class="text-xs text-green-500">تم ✓</p>
                        </div>

                        <!-- Active State -->
                        <div x-show="status !== 'sent' && status !== 'sending'" class="w-6 h-6 rounded-full bg-brand-accent flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                            <span class="text-white text-xs font-bold">2</span>
                        </div>
                        <div x-show="status !== 'sent' && status !== 'sending'">
                            <p class="text-sm font-bold text-gray-900">المعاينة</p>
                            <p class="text-xs text-brand-accent">الخطوة الحالية</p>
                        </div>
                    </div>
                    
                    <!-- Step 3: Launch (Reactive) -->
                    <div class="flex items-center gap-3 relative">
                        <!-- Green Check (Completed) -->
                        <div x-show="status === 'sent'" class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div x-show="status === 'sent'">
                            <p class="text-sm font-medium text-green-600">الإرسال</p>
                            <p class="text-xs text-green-500">تم الإرسال</p>
                        </div>

                        <!-- Pending/Active State -->
                        <div x-show="status !== 'sent'" class="w-6 h-6 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center flex-shrink-0 z-10">
                            <span class="text-gray-400 text-xs font-bold">3</span>
                        </div>
                        <div x-show="status !== 'sent'">
                            <p class="text-sm font-medium text-gray-400">الإرسال</p>
                            <p class="text-xs text-gray-400">إرسال للمشتركين</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Audience Summary Card (Static) -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($subscriberCount) }}</p>
                        <p class="text-sm text-gray-500">مشترك نشط</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="truncate" title="{{ $campaign->subject }}">{{ Str::limit($campaign->subject, 35) }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mt-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        <span>{{ $campaign->posts->count() }} مقالات</span>
                    </div>
                </div>
            </div>
            
            <!-- Test Email Tool (Static) -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-700 mb-3 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    إرسال نسخة تجريبية
                </h3>
                <form action="{{ route('admin.campaigns.send-test', $campaign) }}" method="POST">
                    @csrf
                    <div class="flex gap-2">
                        <input type="email" name="email" placeholder="example@email.com" required
                               class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-accent focus:border-transparent" dir="ltr">
                        <button type="submit" 
                                class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition-colors flex items-center gap-1.5 text-sm font-medium flex-shrink-0">
                            <svg class="w-4 h-4 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span>إرسال</span>
                        </button>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </form>
            </div>
            
            <!-- Launch / Success Section (Reactive) -->
            <div>
                <!-- Launch Card -->
                <div x-show="status === 'draft' || status === 'sending'" class="bg-gradient-to-br from-brand-accent/5 to-amber-50 p-5 rounded-xl border border-brand-accent/20">
                    <h3 class="font-bold text-gray-800 mb-3 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        </svg>
                        إطلاق الحملة
                    </h3>
                    
                    <button type="button"
                            @click="showModal = true"
                            :disabled="{{ $subscriberCount }} === 0"
                            :class="{ 'opacity-50 cursor-not-allowed': {{ $subscriberCount }} === 0, 'hover:shadow-xl transform hover:-translate-y-0.5': {{ $subscriberCount }} > 0 }"
                            class="w-full px-6 py-4 bg-brand-accent text-white rounded-xl transition-all duration-200 font-bold text-lg shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        إرسال للجميع
                    </button>
                    
                    <p class="text-xs text-gray-500 text-center mt-3">سيُطلب منك التأكيد قبل الإرسال</p>
                </div>

                <!-- Success State -->
                <div x-show="status === 'sent'" x-cloak class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl border border-green-200">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-green-800">تم إرسال الحملة بنجاح! 🎉</p>
                            <p class="text-sm text-green-700 mt-1">
                                تم الإرسال الآن الساعة <span x-text="sentTime"></span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Confirmation Modal -->
                <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="showModal = false">
                    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden z-10">
                        <div class="bg-gradient-to-r from-brand-accent to-amber-500 h-1.5"></div>
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <h3 class="text-xl font-bold text-gray-900">هل أنت متأكد؟</h3>
                                <p class="text-gray-500 mt-2">أنت على وشك إرسال هذه الحملة إلى <span class="font-bold text-brand-accent">{{ number_format($subscriberCount) }}</span> مشترك.</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3 items-stretch">
                                <form action="{{ route('admin.campaigns.send', $campaign) }}" method="POST" class="h-full" @submit="handleSubmit()">
                                    @csrf
                                    <button type="submit" :disabled="isSending" class="w-full h-full px-4 py-3 bg-brand-accent text-white rounded-xl hover:bg-brand-accent-hover transition-all font-bold flex items-center justify-center gap-2">
                                        <span x-text="isSending ? 'جارٍ الإرسال...' : 'نعم، أرسل الآن'">نعم، أرسل الآن</span>
                                    </button>
                                </form>
                                <button type="button" @click="showModal = false" :disabled="isSending" class="w-full h-full px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">إلغاء</button>
                            </div>
                            <div x-show="isSending && statusMessage" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-center">
                                <span x-text="statusMessage" class="text-sm font-medium text-blue-700"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>

</div>
@endsection
