<div class="form-group row align-items-center" :class="{'has-danger': errors.has('expense_name'), 'has-success': fields.expense_name && fields.expense_name.valid }">
    <label for="expense_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.expense.columns.expense_name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.expense_name" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('expense_name'), 'form-control-success': fields.expense_name && fields.expense_name.valid}" id="expense_name" name="expense_name" placeholder="{{ trans('admin.expense.columns.expense_name') }}">
        <div v-if="errors.has('expense_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('expense_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cost'), 'has-success': fields.cost && fields.cost.valid }">
    <label for="cost" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.expense.columns.cost') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.cost" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cost'), 'form-control-success': fields.cost && fields.cost.valid}" id="cost" name="cost" placeholder="{{ trans('admin.expense.columns.cost') }}">
        <div v-if="errors.has('cost')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cost') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('branch_id'), 'has-success': fields.branch_id && fields.branch_id.valid }">
    <label for="branch_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.expense.columns.branch_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.branch_id" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('branch_id'), 'form-control-success': fields.branch_id && fields.branch_id.valid}" id="branch_id" name="branch_id" placeholder="{{ trans('admin.expense.columns.branch_id') }}">
        <div v-if="errors.has('branch_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('branch_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('remarks'), 'has-success': fields.remarks && fields.remarks.valid }">
    <label for="remarks" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.expense.columns.remarks') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.remarks" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('remarks'), 'form-control-success': fields.remarks && fields.remarks.valid}" id="remarks" name="remarks" placeholder="{{ trans('admin.expense.columns.remarks') }}">
        <div v-if="errors.has('remarks')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('remarks') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('created_by'), 'has-success': fields.created_by && fields.created_by.valid }">
    <label for="created_by" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.expense.columns.created_by') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.created_by" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('created_by'), 'form-control-success': fields.created_by && fields.created_by.valid}" id="created_by" name="created_by" placeholder="{{ trans('admin.expense.columns.created_by') }}">
        <div v-if="errors.has('created_by')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('created_by') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('updated_by'), 'has-success': fields.updated_by && fields.updated_by.valid }">
    <label for="updated_by" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.expense.columns.updated_by') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.updated_by" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('updated_by'), 'form-control-success': fields.updated_by && fields.updated_by.valid}" id="updated_by" name="updated_by" placeholder="{{ trans('admin.expense.columns.updated_by') }}">
        <div v-if="errors.has('updated_by')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('updated_by') }}</div>
    </div>
</div>


