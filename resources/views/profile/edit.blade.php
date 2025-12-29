<x-app-layout>
    <div class="py-12" dir="rtl" x-data="{ photoPreview: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                
                <!-- Mobile: Minimal Header (Avatar + Name) -->
                <div class="md:hidden order-1">
                    <div class="bg-white shadow-md rounded-lg p-4 flex items-center gap-4">
                        <!-- Mobile Interactive Avatar -->
                        <div class="relative group cursor-pointer flex-shrink-0" onclick="document.getElementById('photo').click()">
                            <div class="w-16 h-16 rounded-full overflow-hidden shadow-inner" x-show="!photoPreview">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="w-16 h-16 rounded-full overflow-hidden shadow-inner bg-cover bg-center" x-show="photoPreview" x-cloak :style="'background-image: url(' + photoPreview + ')'"></div>
                            <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                            @if($user->is_super_admin)
                                <span class="inline-block mt-1 px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">مدير النظام</span>
                            @elseif($user->is_admin)
                                <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">مسؤول</span>
                            @else
                                <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">مستخدم</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Profile Card (Desktop: Right Sidebar, Hidden on Mobile) -->
                <div class="hidden md:block md:col-span-4 space-y-6">
                    <!-- Profile Info Card (Styled to match main cards) -->
                    <div class="bg-white shadow-md rounded-lg overflow-hidden md:sticky md:top-4">
                        
                        <!-- Card Header (Matches main cards styling) -->
                        <div class="px-4 md:px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-800">معلومات الحساب</h3>
                        </div>

                        <!-- Card Body -->
                        <div class="px-4 md:px-6 py-6">
                            
                            <!-- Profile Photo & Identity (First) -->
                            <div class="flex flex-col items-center text-center">
                                <!-- Interactive Avatar -->
                                <div class="relative group cursor-pointer mb-4" onclick="document.getElementById('photo').click()">
                                    <!-- Current Photo -->
                                    <div class="w-24 h-24 rounded-full overflow-hidden shadow-md border-2 border-gray-200 group-hover:border-indigo-400 transition-all" x-show="!photoPreview">
                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <!-- Preview Photo -->
                                    <div class="w-24 h-24 rounded-full overflow-hidden shadow-md border-2 border-indigo-500 bg-cover bg-center" x-show="photoPreview" x-cloak :style="'background-image: url(' + photoPreview + ')'"></div>
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mb-3">انقر لتغيير الصورة</p>
                                
                                <!-- Name & Email -->
                                <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 mb-3">{{ $user->email }}</p>
                                
                                <!-- Role Badge -->
                                @if($user->is_super_admin)
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">مدير النظام</span>
                                @elseif($user->is_admin)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">مسؤول</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">مستخدم</span>
                                @endif
                            </div>

                            <!-- Separator -->
                            <div class="border-t border-gray-200 my-6"></div>

                            <!-- Account Details (Last) -->
                            <div class="text-sm text-gray-600 space-y-3">
                                <div class="flex justify-between">
                                    <span>تاريخ التسجيل:</span>
                                    <span class="font-medium text-gray-900">{{ $user->created_at->format('Y/m/d') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>آخر تحديث:</span>
                                    <span class="font-medium text-gray-900">{{ $user->updated_at->format('Y/m/d') }}</span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Left Column: Forms (Desktop: Left, Mobile: Below Header) -->
                <div class="md:col-span-8 space-y-6">
                    
                    <!-- Consolidated Profile Information Form -->
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="px-4 md:px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-800">تعديل البيانات الشخصية</h3>
                        </div>
                        <div class="p-4 md:p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Password Update Form -->
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="px-4 md:px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-800">تحديث كلمة المرور</h3>
                        </div>
                        <div class="p-4 md:p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete Account Zone -->
                    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-red-100">
                        <div class="px-4 md:px-6 py-4 border-b border-red-100 bg-red-50">
                            <h3 class="text-lg font-bold text-red-800">منطقة الخطر</h3>
                        </div>
                        <div class="p-4 md:p-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- CKEditor Scripts -->
    @ckeditorScripts

    <!-- Mobile CKEditor Styles -->
    <style>
        @media (max-width: 768px) {
            .ck-editor__editable {
                min-height: 250px !important;
            }
            .ck-toolbar {
                flex-wrap: wrap;
            }
        }
    </style>

    <!-- Additional Profile Form Sync Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileForm = document.getElementById('profile-form');
            
            if (profileForm) {
                // Add submit handler with higher priority
                profileForm.addEventListener('submit', function(e) {
                    // Find biography textarea
                    const biographyTextarea = document.querySelector('textarea[name="biography"]');
                    
                    if (biographyTextarea && biographyTextarea.ckeditorInstance) {
                        // Sync editor content to textarea
                        const editorData = biographyTextarea.ckeditorInstance.getData();
                        biographyTextarea.value = editorData;
                        
                        console.log('Biography synced:', editorData.substring(0, 100) + '...');
                    }
                }, true); // Use capture phase for earlier execution
            }
        });
    </script>
</x-app-layout>
