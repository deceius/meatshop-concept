<?php

namespace App\Http\Requests\Admin\AccessTier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreAccessTier extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.access-tier.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'tier_id' => ['required'],
            'user.id' => ['required',  Rule::unique('access_tiers', 'user_id')],
            'branch' => ['required'],

        ];
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'user.id.unique' => 'The user you are trying to setup has already been set. You might want to update instead?',
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

    public function getUserId(){
        if ($this->has('user')){
            return $this->get('user')['id'];
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
