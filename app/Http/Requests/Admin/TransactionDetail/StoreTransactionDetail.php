<?php

namespace App\Http\Requests\Admin\TransactionDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreTransactionDetail extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.transaction-detail.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {


        return [
            'transaction_header_id' => ['required'],
            'item' => ['required'],
            'qr_code' => ['required'],
            'amount' => ['required'],
            'quantity' => ['required', 'lte:current_weight'],
            'sale_type' => ['sometimes']

        ];
    }


       /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'quantity.lte' => 'Unable to set quantity. It should not be more than the current stock.',
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

    public function getItemId(){
        if ($this->has('item')){
            return $this->get('item')['id'];
        }
        return null;
    }

    public function computePrice(){
        return $this->get('amount') * $this->get('quantity');
    }

    public function getTransactionHeaderId(){
        return $this->get('transaction_header_id');
    }
}
