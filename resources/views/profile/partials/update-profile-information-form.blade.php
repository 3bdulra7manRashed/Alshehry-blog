<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('معلومات الملف الشخصي') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("تحديث معلومات حسابك والبريد الإلكتروني.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" id="profile-form" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Hidden Profile Photo Input (UI is in Left Sidebar) -->
        <input type="file" 
               name="photo" 
               id="photo" 
               class="hidden"
               accept="image/*"
               x-on:change="
                    const file = $event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => { photoPreview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
               ">
        <x-input-error class="mt-2" :messages="$errors->get('photo')" />

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('الاسم')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('عنوان بريدك الإلكتروني غير مؤكد.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('انقر هنا لإعادة إرسال رسالة التحقق.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('تم إرسال رابط تحقق جديد إلى عنوان بريدك الإلكتروني.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Short Bio -->
        <div>
            <x-input-label for="short_bio" :value="__('النبذة القصيرة')" />
            <textarea id="short_bio" name="short_bio" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" placeholder="نبذة مختصرة تظهر في بطاقة المؤلف">{{ old('short_bio', $user->short_bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('short_bio')" />
        </div>

        <!-- Detailed Biography -->
        <div>
            <x-input-label for="biography" :value="__('النبذة التعريفية')" />
            <p class="text-xs text-gray-500 mb-2">اكتب نبذة تفصيلية عنك تظهر في صفحة "عني"</p>
            <textarea 
                id="biography" 
                name="biography" 
                class="ckeditor mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                rows="10"
                data-min-height="250px"
                data-placeholder="اكتب نبذتك التعريفية هنا..."
            >{{ old('biography', $user->biography) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('biography')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('حفظ التغييرات') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('تم الحفظ.') }}</p>
            @endif
        </div>
    </form>
</section>
