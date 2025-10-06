<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\App;

class StoreAppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // يمكنك تخصيصها لاحقًا إذا عندك صلاحيات
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // يفضل تعملها بـ Service أو Helper
        $existingApps = App::pluck('name')->toArray();
        $allApps = getAppsName(); // تأكد إنه موجود وراجع الكاش لو محتاج
        $newApps = array_diff($allApps, $existingApps);

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($newApps) {
                    if (!in_array($value, $newApps)) {
                        $fail("اسم التطبيق المحدد موجود بالفعل أو غير متاح.");
                    }
                }
            ],
            'status' => 'required|integer|in:0,1',
            'google_auth' => 'required|integer|in:0,1',
            'version' => 'required|string|max:10',
            'is_updating' => 'required|integer|in:0,1',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم التطبيق مطلوب.',
            'name.string' => 'اسم التطبيق يجب أن يكون نص.',
            'name.max' => 'اسم التطبيق يجب ألا يزيد عن 255 حرف.',

            'status.required' => 'حقل الحالة مطلوب.',
            'status.integer' => 'يجب أن تكون الحالة رقمية (0 أو 1).',
            'status.in' => 'قيمة الحالة غير صحيحة.',

            'google_auth.required' => 'حقل التسجيل بجوجل مطلوب.',
            'google_auth.integer' => 'يجب أن يكون التسجيل بجوجل رقمية.',
            'google_auth.in' => 'قيمة التسجيل بجوجل غير صحيحة.',

            'version.required' => 'إصدار التطبيق مطلوب.',
            'version.string' => 'يجب أن يكون الإصدار نصي.',
            'version.max' => 'يجب ألا يزيد الإصدار عن 10 حروف.',

            'is_updating.required' => 'حقل وضع التحديث مطلوب.',
            'is_updating.integer' => 'يجب أن يكون وضع التحديث رقمي.',
            'is_updating.in' => 'قيمة وضع التحديث غير صحيحة.',
        ];
    }
}
