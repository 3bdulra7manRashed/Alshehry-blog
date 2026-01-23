{{--
    Newsletter Subscription Form Component
    
    A brand-consistent, RTL-optimized newsletter form with:
    - Brand accent focus states (replaces browser defaults)
    - Proper RTL text alignment
    - Centered title for better scanability
    - WCAG-compliant focus indicators
    
    Usage:
    <x-newsletter-form />
    
    Variants:
    <x-newsletter-form variant="compact" />   - No description text
    <x-newsletter-form variant="horizontal" /> - Side-by-side on large screens
    
    Props:
    - variant: 'default' | 'compact' | 'horizontal'
    - showHeading: boolean (default: true)
--}}

@props([
    'variant' => 'default', // default, compact, horizontal
    'showHeading' => true,
])

<div 
    x-data="{
        email: '',
        loading: false,
        success: false,
        error: null,
        
        async submit() {
            this.loading = true;
            this.error = null;
            
            try {
                const response = await fetch('{{ route('newsletter.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ email: this.email })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    this.success = true;
                    this.email = '';
                } else {
                    if (data.errors && data.errors.email) {
                        this.error = data.errors.email[0];
                    } else {
                        this.error = data.message || 'حدث خطأ. يرجى المحاولة مرة أخرى.';
                    }
                }
            } catch (err) {
                this.error = 'حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.';
            } finally {
                this.loading = false;
            }
        }
    }"
    {{ $attributes->merge(['class' => 'newsletter-form']) }}
>
    {{-- ===================== --}}
    {{-- SUCCESS STATE         --}}
    {{-- ===================== --}}
    <div x-show="success" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="text-center py-8">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h4 class="text-xl font-bold text-gray-900 mb-2">شكراً لاشتراكك! 🎉</h4>
        <p class="text-gray-500 text-sm">ستصلك أحدث المقالات والنصائح مباشرة في بريدك.</p>
    </div>
    
    {{-- ===================== --}}
    {{-- FORM STATE            --}}
    {{-- ===================== --}}
    <div x-show="!success">
        
        @if($showHeading)
            {{-- ===================== --}}
            {{-- HEADING SECTION       --}}
            {{-- Centered for visual   --}}
            {{-- grouping & scanability --}}
            {{-- ===================== --}}
            <div class="text-center mb-6 {{ $variant === 'horizontal' ? 'lg:text-right lg:mb-0 lg:flex-1' : '' }}">
                {{-- Icon + Title --}}
                <div class="inline-flex items-center justify-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900">
                        اشترك في نشرتنا البريدية
                    </h3>
                </div>
                
                {{-- Description (hidden in compact mode) --}}
                @if($variant !== 'compact')
                    <p class="text-gray-500 text-sm leading-relaxed max-w-md mx-auto {{ $variant === 'horizontal' ? 'lg:mx-0' : '' }}">
                        احصل على أحدث المقالات والنصائح في ريادة الأعمال مباشرة في صندوق الوارد.
                    </p>
                @endif
            </div>
        @endif
        
        {{-- ===================== --}}
        {{-- FORM ELEMENT          --}}
        {{-- ===================== --}}
        <form @submit.prevent="submit" 
              class="{{ $variant === 'horizontal' ? 'lg:flex lg:items-start lg:gap-4 lg:flex-1' : 'mt-5' }}">
            
            {{-- Input Container --}}
            <div class="flex-1 {{ $variant === 'horizontal' ? '' : 'mb-4' }}">
                
                {{-- Email Input with Label --}}
                <label for="newsletter-email" class="sr-only">البريد الإلكتروني</label>
                <div class="relative">
                    <input 
                        type="email" 
                        id="newsletter-email"
                        name="email"
                        x-model="email"
                        placeholder="أدخل بريدك الإلكتروني"
                        required
                        autocomplete="email"
                        :disabled="loading"
                        class="w-full h-12 px-4 pr-11 
                               bg-white border border-gray-200 rounded-xl
                               text-gray-900 text-right placeholder:text-gray-400 placeholder:text-right
                               transition-all duration-200
                               focus:outline-none focus:border-brand-accent focus:ring-2 focus:ring-brand-accent/25
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-50"
                        dir="rtl"
                    >
                    {{-- Email Icon (decorative) --}}
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </span>
                </div>
                
                {{-- Error Message --}}
                <p x-show="error" 
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="opacity-0 -translate-y-1"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   x-text="error"
                   class="mt-2 text-sm text-red-600 text-right flex items-center justify-end gap-1.5"
                   role="alert">
                </p>
            </div>
            
            {{-- ===================== --}}
            {{-- SUBMIT BUTTON         --}}
            {{-- Brand-accent themed   --}}
            {{-- with proper states    --}}
            {{-- ===================== --}}
            <button 
                type="submit"
                :disabled="loading || !email"
                class="w-full h-12 {{ $variant === 'horizontal' ? 'lg:w-auto lg:px-8' : '' }} 
                       px-6 bg-brand-accent text-white rounded-xl font-medium
                       flex items-center justify-center gap-2
                       transition-all duration-200 ease-out
                       shadow-lg shadow-brand-accent/20
                       hover:bg-brand-accent-hover hover:shadow-xl hover:shadow-brand-accent/30
                       focus:outline-none focus:ring-2 focus:ring-brand-accent focus:ring-offset-2
                       disabled:opacity-60 disabled:cursor-not-allowed disabled:shadow-none disabled:hover:bg-brand-accent"
            >
                {{-- Loading Spinner --}}
                <svg x-show="loading" 
                     x-cloak
                     class="animate-spin w-5 h-5" 
                     fill="none" 
                     viewBox="0 0 24 24"
                     aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                
                {{-- Button Text --}}
                <span x-text="loading ? 'جارٍ الاشتراك...' : 'اشترك الآن'"></span>
                
                {{-- Arrow Icon (RTL: points left) --}}
                <svg x-show="!loading" 
                     x-cloak
                     class="w-4 h-4 rtl:rotate-180" 
                     fill="none" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24"
                     aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
            
        </form>
        
        {{-- ===================== --}}
        {{-- PRIVACY NOTE          --}}
        {{-- ===================== --}}
        <p class="mt-4 text-xs text-gray-400 text-center {{ $variant === 'horizontal' ? 'lg:text-right' : '' }}">
            <svg class="w-3.5 h-3.5 inline-block ml-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            نحترم خصوصيتك. يمكنك إلغاء الاشتراك في أي وقت.
        </p>
    </div>
</div>
