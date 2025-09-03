<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public const PER_PAGE = 'per_page';
    public const PAGE = 'page';
    public const NAME = 'name';
    public const STATUS = 'status';
    public const SORT = 'sort';

    public const SORT_MAP = [
        'price_asc'       => ['price', 'asc'],
        'price_desc'      => ['price', 'desc'],
        'amount_asc'      => ['amount', 'asc'],
        'amount_desc'     => ['amount', 'desc'],
        'created_at_asc'  => ['created_at', 'asc'],
        'created_at_desc' => ['created_at', 'desc'],
        'deleted_at_asc'  => ['deleted_at', 'asc'],
        'deleted_at_desc' => ['deleted_at', 'desc'],
    ];

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            self::PER_PAGE => 'integer|min:1|max:50',
            self::PAGE     => 'integer|min:1',
            self::NAME     => 'nullable|string|max:50',
            self::STATUS   => 'nullable|in:0,1',
            self::SORT     => 'nullable|in:' . implode(',', array_keys(self::SORT_MAP)),
        ];
    }

    public static function matchSort(string $sort): array
    {
        return self::SORT_MAP[$sort] ?? [];
    }
}
