<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Nama kategori wajib diisi.',
            'name.max'        => 'Nama kategori maksimal 100 karakter.',
            'name.unique'     => 'Nama kategori sudah digunakan.',
            'description.max' => 'Deskripsi maksimal 255 karakter.',
        ];
    }
}