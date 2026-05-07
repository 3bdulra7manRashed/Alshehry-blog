<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.recaptcha.secret_key');

        // FAILSAFE: Only skip validation in local/testing environments.
        // In production, a missing key MUST fail validation — never silently pass.
        if (empty($secretKey)) {
            if (app()->environment('local', 'testing')) {
                return;
            }

            Log::critical('reCAPTCHA secret key is missing in production environment.');
            $fail('خطأ في إعدادات التحقق الأمني. يرجى التواصل مع مدير الموقع.');
            return;
        }

        // Check if the token value was actually provided by the client
        if (empty($value)) {
            $fail('يرجى إكمال التحقق من reCAPTCHA');
            return;
        }

        // Verify the token with Google's API
        try {
            $response = Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification request failed.', ['error' => $e->getMessage()]);
            $fail('فشل التحقق من reCAPTCHA. يرجى المحاولة مرة أخرى.');
            return;
        }

        if (!$response->successful()) {
            $fail('فشل التحقق من reCAPTCHA. يرجى المحاولة مرة أخرى.');
            return;
        }

        $result = $response->json();

        // Check if verification was successful
        if (!($result['success'] ?? false)) {
            $errorCodes = $result['error-codes'] ?? [];

            Log::warning('reCAPTCHA verification failed.', ['errors' => $errorCodes]);

            // Provide specific error messages
            if (in_array('timeout-or-duplicate', $errorCodes)) {
                $fail('انتهت صلاحية التحقق. يرجى المحاولة مرة أخرى.');
            } elseif (in_array('invalid-input-response', $errorCodes)) {
                $fail('التحقق غير صحيح. يرجى المحاولة مرة أخرى.');
            } else {
                $fail('فشل التحقق من reCAPTCHA. يرجى المحاولة مرة أخرى.');
            }
            return;
        }

        // reCAPTCHA v3: Enforce score threshold
        $score = $result['score'] ?? null;
        $threshold = config('services.recaptcha.threshold', 0.5);

        if ($score !== null && $score < $threshold) {
            Log::warning('reCAPTCHA v3 score below threshold.', [
                'score' => $score,
                'threshold' => $threshold,
                'ip' => request()->ip(),
            ]);
            $fail('فشل التحقق الأمني. يرجى المحاولة مرة أخرى.');
        }
    }
}
