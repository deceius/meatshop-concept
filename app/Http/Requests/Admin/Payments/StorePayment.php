<?php

namespace App\Http\Requests\Admin\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StorePayment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.transaction-header.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['required'],
            'transaction_header_id' => ['required'],
            'payment_date' => ['required'],
            'payment_amount' => ['required'],
            'account_name' => ['required_unless:type,Cash'],
            'reference_number' => ['required_unless:type,Cash'],

        ];
    }


    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
            'required_unless' => 'The :attribute field is required.',
        ];
    }
    /**
    * Modify input data
    *
    * @return array
    */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();

        //Add your code for manipulation with request data here

        return $sanitized;
    }

}
