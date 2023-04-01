<?php

namespace App\Http\Requests\Admin\AccessTier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateAccessTier extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.access-tier.edit', $this->accessTier);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'tier_id' => ['sometimes'],
            'user_id' => ['sometimes'],
            'branch_id' => ['sometimes']

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
