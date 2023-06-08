<?php

namespace App\Http\Requests\Admin\TransactionDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use App\Models\TransactionHeader;

class UpdateTransactionDetail extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.transaction-detail.edit', $this->transactionDetail);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $th = TransactionHeader::where('id', '=', $this->transactionDetail->transaction_header_id)->first();
        if ($th->transaction_type_id == 1) {
            return [
                'quantity' => ['sometimes'],
                'qr_code' => ['sometimes', 'unique:transaction_details,qr_code,'.$this->get('qr_code')],
                'amount' => ['sometimes'],
            ];
        }

        return [
            'quantity' => ['sometimes'],
            'amount' => ['sometimes'],
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
