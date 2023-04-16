<div class="form-group row align-items-center" :class="{'has-danger': errors.has('ref_no'), 'has-success': fields.ref_no && fields.ref_no.valid }">
    <label for="ref_no" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.'.$transactionType.'.columns.ref_no') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.ref_no" v-validate="'required' " @input="validate($event)" class="form-control" :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" :class="{'form-control-danger': errors.has('ref_no'), 'form-control-success': fields.ref_no && fields.ref_no.valid}" id="ref_no" name="ref_no" placeholder="{{ trans('admin.'.$transactionType.'.columns.ref_no') }}">
        <div v-if="errors.has('ref_no')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('ref_no') }}</div>
    </div>
</div>



<div class="form-group row align-items-center" :class="{'has-danger': errors.has('branch'), 'has-success': fields.branch && fields.branch.valid }">
    <label for="item" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.'.$transactionType.'.columns.branch_id') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <multiselect :disabled="true" v-model="form.branch" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" label="name" track-by="id" :options="{{ $branches->toJson() }}" :multiple="false" open-direction="bottom"></multiselect>
        <div v-if="errors.has('branch')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('branch') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('transaction_date'), 'has-success': fields.transaction_date && fields.transaction_date.valid }">
    <label for="transaction_date" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.'.$transactionType.'.columns.transaction_date') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div class="input-group input-group--custom">
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
            <datetime :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}"  v-model="form.transaction_date" :config="datetimePickerConfig" v-validate="'required'" class="flatpickr" :class="{'form-control-danger': errors.has('transaction_date'), 'form-control-success': fields.transaction_date && fields.transaction_date.valid}" id="transaction_date" name="transaction_date" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_a_date') }}"></datetime>
        </div>
        <div v-if="errors.has('transaction_date')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('transaction_date') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('remarks'), 'has-success': fields.remarks && fields.remarks.valid }">
    <label for="remarks" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.'.$transactionType.'.columns.remarks') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div class="input-group ">
            <input type="text" :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" v-model="form.remarks"  @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('remarks'), 'form-control-success': fields.remarks && fields.remarks.valid}" id="remarks" name="remarks" placeholder="{{ trans('admin.'.$transactionType.'.columns.remarks') }}">
        </div>
        <div v-if="errors.has('remarks')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('remarks') }}</div>
    </div>
</div>


<div class="form-group row align-items-center" :class="{'has-danger': errors.has('delivered_by'), 'has-success': fields.delivered_by && fields.delivered_by.valid }">
    <label for="delivered_by" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.'.$transactionType.'.columns.delivered_by') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div class="input-group ">
            <input type="text" :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" v-model="form.delivered_by"  @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('delivered_by'), 'form-control-success': fields.delivered_by && fields.delivered_by.valid}" id="delivered_by" name="delivered_by" placeholder="{{ trans('admin.'.$transactionType.'.columns.delivered_by') }}">
        </div>
        <div v-if="errors.has('delivered_by')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('delivered_by') }}</div>
    </div>
</div>
