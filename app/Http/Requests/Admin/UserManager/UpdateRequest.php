<?php

namespace App\Http\Requests\Admin\UserManager;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->id,
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $status_code = 422;
        
        throw new ValidationException($validator, response()->json([
            'error' => true,
            'message' => 'Gagal memperbarui user',
            'data' => $validator->errors()
        ], $status_code));
    }
}
