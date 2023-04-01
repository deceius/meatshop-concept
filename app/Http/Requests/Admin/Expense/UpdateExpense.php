<?php

namespace App\Http\Requests\Admin\Expense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateExpense extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.expense.edit', $this->expense);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'expense_name' => ['sometimes', 'string'],
            'cost' => ['sometimes', 'numeric'],
            'branch_id' => ['sometimes', 'string'],
            'remarks' => ['sometimes', 'string'],
            'created_by' => ['sometimes', 'string'],
            'updated_by' => ['nullable', 'string'],
            
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
