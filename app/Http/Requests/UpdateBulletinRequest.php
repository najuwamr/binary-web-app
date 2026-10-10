<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBulletinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'edisi' => ['required', 'string', 'max:50'],
            'tahun' => ['required', 'integer', 'min:1900', 'max:2100'],
            'sampul' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'file_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
        ];
    }
}
