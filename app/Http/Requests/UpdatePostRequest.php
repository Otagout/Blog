<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
           'title' => 'required|string|max:255',
           'slug' => 'required|string|unique:posts,slug',
           'content' => 'required|string',
           'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for image upload
           'published_at' => 'nullable|date', // Adjust date validation as per your needs
       ];
    }
}
