<?php

namespace App\Http\Requests\Admin\Price;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StorePrice extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.price.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'item' => ['required'],
            'branch' => ['required'],
            'box_amount' => ['required', 'numeric'],
            'cut_amount' => ['required', 'numeric']

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

    public function getBranchId(){
        if ($this->has('branch')){
            return $this->get('branch')['id'];
        }
        return null;
    }
}
