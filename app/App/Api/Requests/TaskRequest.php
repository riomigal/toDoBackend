<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:4000',
            'categories' => ['string', 'max:400', 'regex:/(^[A-Za-z0-9, ]+$)+/'],
            'priority_id' => 'required|exists:priorities,id',
        ];
    }
}
