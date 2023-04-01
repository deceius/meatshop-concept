<?php

namespace App\Http\Requests\Admin\TransactionHeader;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreTransactionHeader extends FormRequest
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

        $rules = [];
        switch ($this->get('transaction_type_id')) {
            case 1:
                $rules = [
                    'ref_no' => ['required', 'string'],
                    'transaction_date' => ['required', 'date'],
                    'remarks' => ['sometimes'],
                    'branch' => ['required'],
                    'transaction_type_id' => ['required'],

                ];
                break;
            case 2:
                    $rules = [
                        'ref_no' => ['required', 'string'],
                        'transaction_date' => ['required', 'date'],
                        'remarks' => ['sometimes'],
                        'branch' => ['required'],
                        'customer' => ['sometimes'],
                        'transaction_type_id' => ['required'],
                        'customer_category' => ['required'],

                    ];
                    break;
            case 3:
                $rules = [
                    'ref_no' => ['required', 'string'],
                    'transaction_date' => ['required', 'date'],
                    'remarks' => ['sometimes'],
                    'branch' => ['required'],
                    'transaction_type_id' => ['required'],
                    'delivery_branch' => ['required', 'different:branch'],

                ];
                break;
            default:
                break;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'delivery_branch.different' => 'Delivery branch cannot be the same branch as the Source branch.',
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

    public function getBranchId(){
        if ($this->has('branch')){
            return $this->get('branch')['id'];
        }
        return null;
    }

    public function getDeliveryBranchId(){
        if ($this->has('delivery_branch')){
            return $this->get('delivery_branch')['id'];
        }
        return null;
    }

    public function getCustomerId(){
        if ($this->has('customer')){
            return $this->get('customer')['id'];
        }
        return 0;
    }
}
