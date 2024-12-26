<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        if ($errors->has('email')) {
            $response = response()->json([
                'error' => $errors->first('email'),
            ], 422);

            throw new ValidationException($validator, $response);
        }

        if ($errors->has('password')) {
            $response = response()->json([
                'error' => $errors->first('password'),
            ], 422);

            throw new ValidationException($validator, $response);
        }
    }
}
