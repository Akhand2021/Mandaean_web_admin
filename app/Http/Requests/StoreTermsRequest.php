<?php



namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTermsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10',
            'is_active' => 'boolean',
        ];
    }
}
