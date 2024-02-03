<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|integer',
            'x' => 'required|integer',
            'y' => 'required|integer'
        ];
    }
}
