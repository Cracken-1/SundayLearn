<?php

namespace App\Http\Requests;

class SearchRequest extends SecureRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'search' => [
                'nullable',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-Z0-9\s\-_.,!?()\'":;]+$/'
            ],
            'type' => [
                'nullable',
                'string',
                // Relaxed validation to allow various content types including resource types
                // 'in:lesson,blog,resource,event,teaching_tip,worksheet,coloring_page,activity_guide,craft,game,other'
                // Actually, just let it be a string to avoid maintenance issues when new types are added
                'max:50',
                'regex:/^[a-zA-Z0-9\s\-_]+$/'
            ],
            'category' => [
                'nullable',
                'string',
                'max:100',
                // Allow & and other common chars
                'regex:/^[a-zA-Z0-9\s\-_.,&()]+$/'
            ],
            'age_group' => [
                'nullable',
                'string',
                'max:50',
                // Allow + (e.g. 13+) and parens
                'regex:/^[a-zA-Z0-9\s\-_.,+()]+$/'
            ],
            'sort' => [
                'nullable',
                'string',
                'in:newest,oldest,popular,title,relevance'
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
                'max:1000'
            ],
            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100'
            ]
        ]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'search.regex' => 'The search field contains invalid characters.',
            'search.min' => 'The search term must be at least 2 characters.',
            'search.max' => 'The search term may not be greater than 255 characters.',
            'type.in' => 'The selected type is invalid.',
            'category.regex' => 'The category contains invalid characters.',
            'age_group.regex' => 'The age group contains invalid characters.',
            'sort.in' => 'The selected sort option is invalid.',
            'page.max' => 'The page number is too high.',
            'per_page.max' => 'Too many items requested per page.',
        ]);
    }
}