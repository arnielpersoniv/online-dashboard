<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ResponseTraits;
use Illuminate\Validation\Rule;

class OpenInfraRequest extends FormRequest
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
            // 'lid_no'      =>   ['required','integer',Rule::unique('open_infra_tasks')->ignore($this->edit_id)->whereNull('deleted_at')],
            'lid_no'      => 'required|integer',
            'category'    => 'required',
            'task'        => 'required',
            'status'      => 'required',
            //'adhoc'       => 'required',
            //'adhoc_task'  => 'required',
        ];

    }

    public function messages()
    {
        return [
            'lid_no.required'     => 'LID No. is required',
            // 'lid_no.unique'       => 'LID No. is already registered',
            'task.required'       => 'Task is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 200));
    }
}
