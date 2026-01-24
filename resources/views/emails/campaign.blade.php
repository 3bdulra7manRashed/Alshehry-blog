<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $campaign->subject }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* ============================================================
           🎨 EMAIL BRAND TOKENS
           Must match the app brand colors for consistency
           ============================================================ */
        :root {
            --brand-accent: #c37c54;
            --brand-accent-hover: #a86844;
            --brand-primary: #1f1f1f;
        }
        
        /* Reset styles */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: #f4f4f5;
        }
        /* Custom styles */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        /* Brand Accent Button */
        .btn-accent {
            display: inline-block;
            padding: 12px 28px;
            background-color: #c37c54; /* --brand-accent */
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .btn-accent:hover {
            background-color: #a86844; /* --brand-accent-hover */
        }
        .post-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .post-image {
            width: 100%;
            height: auto;
            display: block;
        }
        .post-content {
            padding: 20px;
        }
        .post-title {
            color: #1f2937;
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 10px 0;
            line-height: 1.4;
        }
        .post-title a {
            color: #1f2937;
            text-decoration: none;
        }
        .post-title a:hover {
            color: #c37c54; /* --brand-accent */
        }
        .post-excerpt {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            margin: 0 0 16px 0;
        }
        /* Brand accent for links */
        .link-accent {
            color: #c37c54 !important;
            text-decoration: none;
        }
        .link-accent:hover {
            color: #a86844 !important;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            .post-content {
                padding: 16px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <!-- Preheader Text (hidden preview text) -->
    <div style="display: none; max-height: 0; overflow: hidden;">
        {{ Str::limit(strip_tags($campaign->content), 100) }}
    </div>
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f5;">
        <tr>
            <td style="padding: 40px 20px;">
                
                <!-- Main Content Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header with Brand Accent -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px; text-align: center; background: linear-gradient(135deg, #1f1f1f 0%, #2d2d2d 100%);">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="text-align: center;">
                                        <!-- Brand Name + Accent Dot -->
                                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px; display: inline;">
                                            {{ config('app.name') }}
                                        </h1>
                                        <span style="display: inline-block; width: 10px; height: 10px; background-color: #c37c54; border-radius: 50%; margin-right: 8px; vertical-align: middle;"></span>
                                    </td>
                                </tr>
                            </table>
                            <!-- Brand Underline -->
                            <div style="width: 40px; height: 4px; background-color: #c37c54; margin: 16px auto 0; border-radius: 2px;"></div>
                        </td>
                    </tr>
                    
                    <!-- Hero Section -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 20px 0; color: #1f2937; font-size: 26px; font-weight: 700; line-height: 1.3; text-align: center;">
                                {{ $campaign->title }}
                            </h2>
                            <p style="margin: 0; color: #4b5563; font-size: 16px; line-height: 1.7; text-align: center;">
                                {{ $campaign->content }}
                            </p>
                            <div style="width: 60px; height: 3px; background-color: #e5e7eb; margin: 30px auto; border-radius: 2px;"></div>
                        </td>
                    </tr>
                    
                    <!-- Articles Section -->
                    <tr>
                        <td style="padding: 0 40px 40px 40px;">
                            
                            @foreach($campaign->posts as $post)
                                <!-- Post Card -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" class="post-card" style="border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-bottom: 24px;">
                                    @if($post->featured_image_url)
                                        <tr>
                                            <td>
                                                <a href="{{ route('post.show', $post->slug) }}" target="_blank">
                                                    <img src="{{ $post->featured_image_url }}" 
                                                         alt="{{ $post->featured_image_alt ?? $post->title }}" 
                                                         width="520" 
                                                         class="post-image"
                                                         style="width: 100%; height: auto; display: block; object-fit: cover;">
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="post-content" style="padding: 24px;">
                                            <h3 class="post-title" style="margin: 0 0 12px 0; font-size: 18px; font-weight: 700; line-height: 1.4;">
                                                <a href="{{ route('post.show', $post->slug) }}" 
                                                   target="_blank"
                                                   style="color: #1f2937; text-decoration: none;">
                                                    {{ $post->title }}
                                                </a>
                                            </h3>
                                            <p class="post-excerpt" style="margin: 0 0 20px 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                                {{ Str::limit(strip_tags($post->content), 150) }}
                                            </p>
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                <tr>
                                                    <td>
                                                        <!-- Brand Accent CTA Button -->
                                                        <a href="{{ route('post.show', $post->slug) }}" 
                                                           target="_blank"
                                                           class="btn-accent"
                                                           style="display: inline-block; padding: 12px 28px; background-color: #c37c54; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px;">
                                                            اقرأ المزيد ←
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @endforeach
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; background-color: #f9fafb; border-top: 1px solid #e5e7eb;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="margin: 0 0 10px 0; color: #9ca3af; font-size: 13px;">
                                            تصلك هذه الرسالة لأنك مشترك في نشرتنا البريدية
                                        </p>
                                        <p style="margin: 0; color: #9ca3af; font-size: 13px;">
                                            <a href="{{ $unsubscribeLink ?? '#' }}" class="link-accent" style="color: #c37c54; text-decoration: none;">إلغاء الاشتراك</a>
                                            &nbsp;|&nbsp;
                                            <a href="{{ config('app.url') }}" class="link-accent" style="color: #c37c54; text-decoration: none;">زيارة الموقع</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Brand Footer -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #1f1f1f; text-align: center;">
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                © {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.
                            </p>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>
