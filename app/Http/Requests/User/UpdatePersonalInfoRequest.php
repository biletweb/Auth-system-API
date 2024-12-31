<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdatePersonalInfoRequest extends FormRequest
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
    }
}
