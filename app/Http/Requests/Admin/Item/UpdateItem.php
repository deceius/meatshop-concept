<?php

namespace App\Http\Requests\Admin\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateItem extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.item.edit', $this->item);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'brand' => ['required'],
            'type' => ['required'],
            'created_by' => ['sometimes', 'string'],
            'updated_by' => ['sometimes', 'string'],

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

    public function getBrandId(){
        if ($this->has('brand')){
            return $this->get('brand')['id'];
        }
        return null;
    }

    public function getTypeId(){
        if ($this->has('type')){
            return $this->get('type')['id'];
        }
        return null;
    }
}
