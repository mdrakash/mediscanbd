<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AnalyzeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:10240',
            'type' => 'required|in:prescription,test_report',
            'language' => 'required|in:bn,en',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'ফাইল আপলোড করা আবশ্যক।',
            'file.image' => 'শুধুমাত্র ছবি ফাইল আপলোড করা যাবে।',
            'file.mimes' => 'ছবির ফরম্যাট jpeg, jpg, png, webp, অথবা gif হতে হবে।',
            'file.max' => 'ছবির সাইজ ১০MB এর বেশি হতে পারবে না।',
            'type.required' => 'ফাইলের ধরন নির্বাচন করা আবশ্যক।',
            'type.in' => 'ফাইলের ধরন prescription অথবা test_report হতে হবে।',
            'language.required' => 'ভাষা নির্বাচন করা আবশ্যক।',
            'language.in' => 'ভাষা bn অথবা en হতে হবে।',
        ];
    }
}
