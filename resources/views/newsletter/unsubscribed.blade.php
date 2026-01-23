<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    
    <title>إلغاء الاشتراك - {{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    
    <div class="min-h-screen flex flex-col items-center justify-center p-6">
        
        <!-- Main Card -->
        <div class="w-full max-w-md">
            
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-2xl font-bold text-gray-800 hover:text-brand-accent transition-colors">
                    <span class="w-3 h-3 rounded-full bg-brand-accent"></span>
                    {{ config('app.name') }}
                </a>
            </div>
            
            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 overflow-hidden">
                
                <!-- Card Header with Icon -->
                <div class="p-8 text-center">
                    
                    @if($success)
                        <!-- Success State -->
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $message }}</h1>
                        
                        @if(isset($already_unsubscribed) && $already_unsubscribed)
                            <p class="text-gray-500 leading-relaxed">
                                يبدو أنك قمت بإلغاء اشتراكك مسبقاً.
                                <br>
                                لن تتلقى أي رسائل منا.
                            </p>
                        @else
                            <p class="text-gray-500 leading-relaxed">
                                نأسف لرؤيتك تغادر 💔
                                <br>
                                يمكنك الاشتراك مجدداً في أي وقت من الصفحة الرئيسية.
                            </p>
                        @endif
                        
                    @else
                        <!-- Error State -->
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-red-100 to-rose-100 flex items-center justify-center">
                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-900 mb-3">حدث خطأ</h1>
                        
                        <p class="text-gray-500 leading-relaxed">
                            {{ $message }}
                            <br>
                            إذا كنت تعتقد أن هذا خطأ، يرجى التواصل معنا.
                        </p>
                    @endif
                    
                </div>
                
                <!-- Card Footer -->
                <div class="px-8 pb-8">
                    <div class="flex flex-col gap-3">
                        <a href="{{ url('/') }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-brand-accent text-white rounded-xl hover:bg-brand-accent-hover transition-all font-medium shadow-lg shadow-brand-accent/20 hover:shadow-xl hover:shadow-brand-accent/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            العودة للرئيسية
                        </a>
                        
                        @if(!$success)
                            <a href="{{ route('contact') }}" 
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                تواصل معنا
                            </a>
                        @endif
                    </div>
                </div>
                
            </div>
            
            <!-- Footer Text -->
            <p class="text-center text-gray-400 text-sm mt-6">
                © {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.
            </p>
            
        </div>
        
    </div>
    
</body>
</html>
