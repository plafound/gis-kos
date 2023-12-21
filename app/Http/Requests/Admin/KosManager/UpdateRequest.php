<?php

namespace App\Http\Requests\Admin\KosManager;

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
            'name' => 'required',
            'address' => 'required',
            'district_id' => 'required',
            'postal_code' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'phone_number' => 'required',
            'type' => 'required',
            'capacity' => 'required',
            'filled_capacity' => 'required',
            'price' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $status_code = 422;
        
        throw new ValidationException($validator, response()->json([
            'error' => true,
            'message' => 'Gagal Memperbarui Kos!',
            'data' => $validator->errors()
        ], $status_code));
    }
}
