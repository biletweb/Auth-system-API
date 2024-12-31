<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        if ($errors->has('name')) {
            $response = response()->json([
                'field' => 'name',
                'error' => $errors->first('name'),
            ]);

            throw new ValidationException($validator, $response);
        }

        if ($errors->has('surname')) {
            $response = response()->json([
                'field' => 'surname',
                'error' => $errors->first('surname'),
            ]);

            throw new ValidationException($validator, $response);
        }

        if ($errors->has('email')) {
            $response = response()->json([
                'field' => 'email',
                'error' => $errors->first('email'),
            ]);

            throw new ValidationException($validator, $response);
        }

        if ($errors->has('password')) {
            $response = response()->json([
                'field' => 'password',
                'error' => $errors->first('password'),
            ]);

            throw new ValidationException($validator, $response);
        }
    }
}
