<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ResponseTraits;
use Illuminate\Validation\Rule;

class ActivityRequest extends FormRequest
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
            'order_no'    => 'required',
            'account_no'  => ['required',Rule::unique('activities')->ignore($this->activity_id)->whereNull('deleted_at')],
            'status'      => 'required',
            'category_id' => 'required',
            'task_id'     => 'required',
        ];

    }

    public function messages()
    {
        return [
            'order_no.required'   => 'Order No. is required',
            'account_no.required' => 'Account No. is required',
            'account_no.unique'   => 'Account No. is already registered',
            'category_id.required'=> 'Category is required',
            'task_id.required'    => 'Task is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 200));
    }
}
