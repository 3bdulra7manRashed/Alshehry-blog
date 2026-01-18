@extends('layouts.blog')

{{-- Contact Page SEO --}}
@section('title', 'تواصل مع صالح الشهري | استشارات ريادة الأعمال')
@section('description', 'تواصل مع صالح الشهري للاستشارات في ريادة الأعمال، تأسيس المشاريع، التدريب والتطوير.')
@section('keywords', 'تواصل صالح الشهري, استشارات ريادة الأعمال, استشارات أعمال جدة')

{{-- ============================================================ --}}
{{-- 🎨 intl-tel-input v17.0.8 CSS                                --}}
{{-- ============================================================ --}}
@push('styles')
{{-- CDN v17.0.8 - Stable version with correct flag sprites --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" crossorigin="anonymous" />

<style>
    /* === Container Full Width === */
    .iti { 
        width: 100%; 
        display: block; 
    }
    
    /* === Flag Container - LEFT side (standard for phones) === */
    .iti__flag-container {
        left: 0;
        right: auto;
    }
    
    /* === Input Styling - Extra padding for separateDialCode === */
    #phone {
        text-align: left;
        direction: ltr;
        padding-left: 108px !important;
        padding-right: 12px !important;
    }
    
    .iti--separate-dial-code input {
        padding-left: 108px !important;
    }
    
    /* === Dropdown Fixes - Prevent clipping === */
    .iti__country-list {
        z-index: 10000 !important;
        text-align: left !important;
        direction: ltr !important;
        width: 320px;
        min-width: 280px;
        color: #000;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        max-height: 250px;
        overflow-y: auto;
        /* CRITICAL: Ensure dropdown stays within viewport */
        left: 0 !important;
        right: auto !important;
    }
    
    /* === Country Item Styling === */
    .iti__country {
        padding: 10px 12px;
        display: flex;
        align-items: center;
        cursor: pointer;
        direction: ltr;
    }
    
    .iti__country:hover {
        background-color: #f3f4f6;
    }
    
    .iti__country.iti__highlight {
        background-color: rgba(195, 124, 84, 0.15);
    }
    
    .iti__country-name {
        margin-left: 8px;
        margin-right: 6px;
        color: #374151;
    }
    
    .iti__dial-code {
        color: #6b7280;
        direction: ltr;
    }
    
    /* === Selected Flag Styling === */
    .iti__selected-flag {
        background: #f9fafb;
        border-radius: 0.75rem 0 0 0.75rem;
        padding: 0 8px;
    }
    
    .iti__selected-dial-code {
        margin-left: 6px;
        color: #374151;
        font-size: 14px;
    }
    
    /* === Divider for preferred countries === */
    .iti__divider {
        border-bottom: 1px solid #e5e7eb;
        margin: 4px 0;
    }

    /* ============================================= */
    /* === Mobile Specific Fixes for RTL Layout === */
    /* === ROBUST FIX: Fixed Position Dropdown   === */
    /* ============================================= */
    @media (max-width: 640px) {
        /* Force the dropdown to float in the center of the screen on mobile */
        .iti__country-list {
            position: fixed !important;
            top: 15% !important;
            left: 5% !important;
            right: 5% !important;
            width: 90% !important;
            max-width: none !important;
            max-height: 60vh !important;
            overflow-y: auto !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.35) !important;
            border-radius: 12px !important;
            z-index: 99999 !important;
            background: #fff !important;
        }

        /* Fix Flag Visibility - Use absolute positioning */
        .iti__country {
            position: relative !important;
            padding-left: 50px !important; /* Make space for flag */
            padding-right: 12px !important;
            text-align: left !important;
            direction: ltr !important;
            min-height: 44px;
            display: flex !important;
            align-items: center !important;
        }

        /* Position Flag Box strictly on the left */
        .iti__flag-box {
            position: absolute !important;
            left: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
            display: inline-block !important;
        }
        
        /* Ensure Name text doesn't overlap */
        .iti__country-name {
            margin-left: 0 !important;
            margin-right: 8px !important;
            font-size: 14px !important;
            flex: 1;
        }
        
        .iti__dial-code {
            font-size: 13px !important;
            color: #6b7280;
            flex-shrink: 0;
        }
        
        /* Input field adjustments for mobile */
        #phone {
            padding-left: 95px !important;
            font-size: 16px !important; /* Prevent iOS zoom on focus */
        }
        
        .iti--separate-dial-code input {
            padding-left: 95px !important;
        }
        
        /* Add overlay effect when dropdown is open */
        .iti--container {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background: rgba(0,0,0,0.3) !important;
            z-index: 99998 !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-16" dir="rtl">
    
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-serif font-bold text-brand-primary mb-4">تواصل معي</h1>
        <p class="text-gray-500 text-lg max-w-2xl mx-auto">
            أسعد دائماً بالاستماع إلى آرائكم واقتراحاتكم. لا تتردد في مراسلتي في أي وقت.
        </p>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
            <div class="w-full p-8 md:p-12">
                
                @if(session('success'))
                    <div class="mb-8 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-800">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-bold">شكراً لرسالتك. سيتم الرد عليك قريباً!</span>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class="space-y-6" id="contact-form">
                    @csrf
                    
                    {{-- Grid: Name (Full) | Email + Phone (Half each) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- 1. NAME (Full Width) --}}
                        <div class="space-y-2 md:col-span-2">
                            <label for="name" class="block text-sm font-bold text-gray-700">الاسم</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-brand-accent focus:border-brand-accent transition-all outline-none placeholder-gray-400 @error('name') border-red-500 @enderror"
                                   placeholder="أدخل اسمك">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 2. EMAIL (Half Width) --}}
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-bold text-gray-700">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-brand-accent focus:border-brand-accent transition-all outline-none placeholder-gray-400 @error('email') border-red-500 @enderror"
                                   placeholder="name@example.com"
                                   dir="ltr">
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 3. PHONE (Half Width) - International Input --}}
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-bold text-gray-700">
                                رقم الهاتف <span class="text-gray-400 font-normal">(اختياري)</span>
                            </label>
                            <div class="w-full">
                                <input type="tel" id="phone" dir="ltr"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-brand-accent focus:border-brand-accent transition-all outline-none @error('phone') border-red-500 @enderror">
                            </div>
                            <input type="hidden" name="phone" id="phone_full" value="{{ old('phone') }}">
                            {{-- Client-side validation error message --}}
                            <p id="phone-error" class="text-sm text-red-600 mt-1 hidden flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span id="phone-error-text">رقم الهاتف غير صحيح</span>
                            </p>
                            @error('phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                    </div>

                    {{-- 4. MESSAGE --}}
                    <div class="space-y-2">
                        <label for="message" class="block text-sm font-bold text-gray-700">نص الرسالة</label>
                        <textarea name="message" id="message" rows="6" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-brand-accent focus:border-brand-accent transition-all outline-none resize-none placeholder-gray-400 @error('message') border-red-500 @enderror"
                                  placeholder="اكتب رسالتك هنا...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 5. reCAPTCHA --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">التحقق الأمني</label>
                        <div class="@error('g-recaptcha-response') border-2 border-red-500 rounded-lg p-2 @enderror" style="display: inline-block;">
                            @if(config('services.recaptcha.site_key'))
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            @else
                                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 text-sm">
                                    ⚠️ reCAPTCHA غير مفعّل
                                </div>
                            @endif
                        </div>
                        @error('g-recaptcha-response')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 6. SUBMIT --}}
                    <div class="pt-4 text-right">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-8 py-3.5 text-white font-bold rounded-xl transition-all transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-accent bg-brand-accent hover:bg-opacity-90">
                            <span>إرسال الرسالة</span>
                            <svg class="w-5 h-5 mr-2 -ml-1 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- ============================================================ --}}
{{-- 📞 intl-tel-input v17.0.8 JavaScript                         --}}
{{-- ============================================================ --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var phoneInput = document.getElementById('phone');
    var phoneHidden = document.getElementById('phone_full');
    var phoneError = document.getElementById('phone-error');
    var phoneErrorText = document.getElementById('phone-error-text');
    var form = document.getElementById('contact-form');
    
    if (!phoneInput) return;
    
    // Error messages in Arabic
    var errorMessages = {
        0: 'رقم الهاتف صحيح', // IS_POSSIBLE
        1: 'رمز الدولة غير صحيح',
        2: 'رقم الهاتف قصير جداً',
        3: 'رقم الهاتف طويل جداً',
        4: 'رقم الهاتف غير صحيح',
        5: 'طول الرقم غير صحيح',
        '-99': 'رقم الهاتف غير صحيح'
    };
    
    // Initialize intl-tel-input v17.0.8 with strict validation
    var iti = window.intlTelInput(phoneInput, {
        initialCountry: "sa",
        separateDialCode: true,
        preferredCountries: ["sa", "ae", "eg", "kw", "qa", "bh", "om"],
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        nationalMode: true,
        formatOnDisplay: true
    });
    
    // Function to show error
    function showError(message) {
        phoneInput.classList.add('border-red-500');
        phoneInput.classList.remove('border-gray-300', 'border-green-500');
        phoneErrorText.textContent = message;
        phoneError.classList.remove('hidden');
    }
    
    // Function to hide error
    function hideError() {
        phoneInput.classList.remove('border-red-500');
        phoneInput.classList.add('border-gray-300');
        phoneError.classList.add('hidden');
    }
    
    // Function to show valid state
    function showValid() {
        phoneInput.classList.remove('border-red-500', 'border-gray-300');
        phoneInput.classList.add('border-green-500');
        phoneError.classList.add('hidden');
    }
    
    // Validation on blur
    phoneInput.addEventListener('blur', function() {
        var value = phoneInput.value.trim();
        
        if (value === '') {
            // Field is optional, reset to default
            hideError();
            phoneHidden.value = '';
            return;
        }
        
        if (iti.isValidNumber()) {
            // Valid number - show success and sync
            showValid();
            phoneHidden.value = iti.getNumber(); // E.164 format: +966501234567
        } else {
            // Invalid number - show specific error
            var errorCode = iti.getValidationError();
            var errorMessage = errorMessages[errorCode] || 'رقم الهاتف غير صحيح';
            showError(errorMessage);
            phoneHidden.value = '';
        }
    });
    
    // Clear validation on focus
    phoneInput.addEventListener('focus', function() {
        phoneInput.classList.remove('border-green-500');
        if (!phoneInput.classList.contains('border-red-500')) {
            phoneInput.classList.add('border-gray-300');
        }
    });
    
    // Real-time input sync (for valid numbers)
    phoneInput.addEventListener('input', function() {
        if (phoneInput.value.trim() && iti.isValidNumber()) {
            phoneHidden.value = iti.getNumber();
        }
    });
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        var value = phoneInput.value.trim();
        
        // If phone field is empty, allow submission (it's optional)
        if (value === '') {
            phoneHidden.value = '';
            return true;
        }
        
        // If phone field has a value, validate strictly
        if (!iti.isValidNumber()) {
            e.preventDefault();
            e.stopPropagation();
            
            var errorCode = iti.getValidationError();
            var errorMessage = errorMessages[errorCode] || 'رقم الهاتف غير صحيح';
            showError(errorMessage);
            
            // Focus the phone input
            phoneInput.focus();
            
            // Scroll to phone input
            phoneInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            return false;
        }
        
        // Valid number - set hidden field with E.164 format
        phoneHidden.value = iti.getNumber();
        return true;
    });
    
    // Handle country change - reset validation
    phoneInput.addEventListener('countrychange', function() {
        hideError();
        if (phoneInput.value.trim()) {
            // Re-validate with new country
            phoneInput.dispatchEvent(new Event('blur'));
        }
    });
});
</script>
@endpush

@if(config('services.recaptcha.site_key'))
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endif
