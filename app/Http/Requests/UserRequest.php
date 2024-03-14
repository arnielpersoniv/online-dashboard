<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ResponseTraits;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    use ResponseTraits;
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
            'emp_id'    => ['required',Rule::unique('users')->ignore($this->user_id)],
            'name'      => 'required',
            'email'     => ['required','email','regex:/(.*)personiv\.com$/i',Rule::unique('users')->ignore($this->user_id)],
            'role'      => 'required',
        ];
    }

    public function messages()
    {
        return [
            'emp_id.required'   => 'ID No. is required',
            'emp_id.unique'     => 'ID No. is already registered',
            'name.required'     => 'Formal Name is required',
            'email.regex'       => 'Please use @personiv.com as company domain',
            'role.required'     => 'Role is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 200));
    }
}
