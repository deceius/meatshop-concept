<?php

namespace App\Http\Requests\Admin\TransactionHeader;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateTransactionHeader extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.transaction-header.edit', $this->transactionHeader);
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
                    'ref_no' => ['sometimes', 'string'],
                    'transaction_date' => ['sometimes', 'date'],
                    'branch' => ['sometimes'],
                    'status' => ['sometimes'],
                    'remarks' => ['sometimes'],

                ];
                break;
            case 2:
                    $rules = [
                        'ref_no' => ['required', 'string'],
                        'invoice_no' => ['required', 'string'],
                        'transaction_date' => ['required', 'date'],
                        'remarks' => ['sometimes'],
                        'branch' => ['required'],
                        'status' => ['sometimes'],
                        'is_paid' => ['sometimes'],
                        'customer' => ['sometimes'],
                        'transaction_type_id' => ['required'],
                        'customer_category' => ['required'],
                        'payment_id' => ['sometimes'],
                        'payment_account_name' => ['sometimes', 'nullable'],
                        'payment_ref_no' => ['sometimes', 'nullable'],
                        'sale_type' => ['sometimes']

                    ];
                    break;
            case 3:
                    $rules = [
                        'ref_no' => ['sometimes', 'string'],
                        'transaction_date' => ['sometimes', 'date'],
                        'branch' => ['sometimes'],
                        'delivery_branch' => ['sometimes', 'different:branch'],
                        'status' => ['sometimes'],
                        'remarks' => ['sometimes'],

                    ];
                    break;

            case 4:
                        $rules = [
                            'ref_no' => ['sometimes', 'string'],
                            'transaction_date' => ['sometimes', 'date'],
                            'received_by' => ['sometimes'],
                            'delivered_by' => ['required'],
                            'branch' => ['sometimes'],
                            'status' => ['sometimes'],
                            'remarks' => ['sometimes'],

                        ];
                        break;
            default:
                # code...
                break;
        }

        return $rules;
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
