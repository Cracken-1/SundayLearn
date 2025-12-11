<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SecureRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            // Base security rules - can be extended by child classes
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a valid string.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'min' => 'The :attribute must be at least :min characters.',
            'email' => 'The :attribute must be a valid email address.',
            'url' => 'The :attribute must be a valid URL.',
            'integer' => 'The :attribute must be an integer.',
            'numeric' => 'The :attribute must be a number.',
            'boolean' => 'The :attribute must be true or false.',
            'array' => 'The :attribute must be an array.',
            'in' => 'The selected :attribute is invalid.',
            'exists' => 'The selected :attribute is invalid.',
            'unique' => 'The :attribute has already been taken.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'regex' => 'The :attribute format is invalid.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Additional security validations
            $this->validateNoSqlInjection($validator);
            $this->validateNoXss($validator);
            $this->validateFileUploads($validator);
        });
    }

    /**
     * Validate against SQL injection patterns
     */
    protected function validateNoSqlInjection(Validator $validator): void
    {
        $sqlPatterns = [
            '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION|SCRIPT)\b)/i',
            '/(\b(OR|AND)\s+\d+\s*=\s*\d+)/i',
            '/(\b(OR|AND)\s+[\'"]?\w+[\'"]?\s*=\s*[\'"]?\w+[\'"]?)/i',
            '/(--|\/\*|\*\/|;)/i',
            '/(\bxp_\w+)/i',
            '/(\bsp_\w+)/i',
        ];

        foreach ($this->all() as $key => $value) {
            if (is_string($value)) {
                foreach ($sqlPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $validator->errors()->add($key, 'The ' . $key . ' field contains invalid characters.');
                        break;
                    }
                }
            }
        }
    }

    /**
     * Validate against XSS patterns
     */
    protected function validateNoXss(Validator $validator): void
    {
        $xssPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/mi',
            '/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/mi',
        ];

        foreach ($this->all() as $key => $value) {
            if (is_string($value)) {
                foreach ($xssPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $validator->errors()->add($key, 'The ' . $key . ' field contains invalid content.');
                        break;
                    }
                }
            }
        }
    }

    /**
     * Validate file uploads for security
     */
    protected function validateFileUploads(Validator $validator): void
    {
        $dangerousExtensions = [
            'php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'jar', 'sh'
        ];

        foreach ($this->allFiles() as $key => $file) {
            if ($file && $file->isValid()) {
                $extension = strtolower($file->getClientOriginalExtension());
                if (in_array($extension, $dangerousExtensions)) {
                    $validator->errors()->add($key, 'The ' . $key . ' file type is not allowed.');
                }

                // Check file size (max 10MB)
                if ($file->getSize() > 10485760) {
                    $validator->errors()->add($key, 'The ' . $key . ' file is too large. Maximum size is 10MB.');
                }
            }
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}