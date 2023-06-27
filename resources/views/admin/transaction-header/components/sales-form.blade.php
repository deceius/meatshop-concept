<div class="form-group row align-items-center" :class="{'has-danger': errors.has('ref_no'), 'has-success': fields.ref_no && fields.ref_no.valid }">
    <label for="ref_no" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.'.$transactionType.'.columns.ref_no') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" type="text" v-model="form.ref_no" v-validate="'required' " @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('ref_no'), 'form-control-success': fields.ref_no && fields.ref_no.valid}" id="ref_no" name="ref_no" placeholder="{{ trans('admin.'.$transactionType.'.columns.ref_no') }}">
        <div v-if="errors.has('ref_no')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('ref_no') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('invoice_no'), 'has-success': fields.invoice_no && fields.invoice_no.valid }">
    <label for="invoice_no" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Sales Invoice No.</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" type="text" v-model="form.invoice_no" v-validate="'required' " @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('invoice_no'), 'form-control-success': fields.invoice_no && fields.invoice_no.valid}" id="invoice_no" name="invoice_no" placeholder="Sales Invoice Number">
        <div v-if="errors.has('invoice_no')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('invoice_no') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('branch'), 'has-success': fields.branch && fields.branch.valid }">
    <label for="item" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.'.$transactionType.'.columns.branch_id') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <multiselect  :disabled="true" v-model="form.branch" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" label="name" track-by="id" :options="{{ $branches->toJson() }}" :multiple="false" open-direction="bottom"></multiselect>
        <div v-if="errors.has('branch')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('branch') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('transaction_date'), 'has-success': fields.transaction_date && fields.transaction_date.valid }">
    <label for="transaction_date" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.'.$transactionType.'.columns.transaction_date') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div class="input-group input-group--custom">
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
            <datetime :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" v-model="form.transaction_date" :config="datetimePickerConfig" v-validate="'required'" class="flatpickr" :class="{'form-control-danger': errors.has('transaction_date'), 'form-control-success': fields.transaction_date && fields.transaction_date.valid}" id="transaction_date" name="transaction_date" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_a_date') }}"></datetime>
        </div>
        <div v-if="errors.has('transaction_date')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('transaction_date') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('payment_id'), 'has-success': fields.payment_id && fields.payment_id.valid }">
    <label for="payment_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Payment</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div class="input-group ">
            <multiselect v-model="form.payment_id" @input="validateIfCash" :preselect-first="true" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}"  :options="['Cash', 'Bank', 'Check']" :multiple="false" :preselect-first="true" open-direction="bottom"></multiselect>
        </div>
        <div v-if="errors.has('payment_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('payment_id') }}</div>
    </div>
</div>


<div class="form-group row align-items-center" v-if="form.payment_id != 'Cash'" :class="{'has-danger': errors.has('payment_ref_no'), 'has-success': fields.payment_ref_no && fields.payment_ref_no.valid }">
    <label for="payment_ref_no" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Payment Reference Number</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" type="text" v-model="form.payment_ref_no" v-validate="'required' " @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('payment_ref_no'), 'form-control-success': fields.payment_ref_no && fields.payment_ref_no.valid}" id="payment_ref_no" name="payment_ref_no" placeholder="Payment Reference Number">
        <div v-if="errors.has('payment_ref_no')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('payment_ref_no') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" v-if="form.payment_id != 'Cash'" :class="{'has-danger': errors.has('payment_account_name'), 'has-success': fields.payment_account_name && fields.payment_account_name.valid }">
    <label for="payment_account_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">Payment Account Name</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" type="text" v-model="form.payment_account_name" v-validate="'required' " @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('payment_account_name'), 'form-control-success': fields.payment_account_name && fields.payment_account_name.valid}" id="payment_account_name" name="payment_account_name" placeholder="Payment Account Name">
        <div v-if="errors.has('payment_account_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('payment_account_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('customer_category'), 'has-success': fields.customer_category && fields.customer_category.valid }">
    <label for="customer_category" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.'.$transactionType.'.columns.customer_category') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div class="input-group ">
            <multiselect :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" v-model="form.customer_category"  placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" :options="['Direct', 'Walk-in', 'Trader']" :multiple="false" :preselect-first="true" open-direction="bottom"></multiselect>
        </div>
        <div v-if="errors.has('customer_category')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('customer_category') }}</div>
    </div>
</div>


<div class="form-group row align-items-center" :class="{'has-danger': errors.has('customer'), 'has-success': fields.customer && fields.customer.valid }">
    <label for="item" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.'.$transactionType.'.columns.customer') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <multiselect :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" v-model="form.customer" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" label="name" track-by="id" :options="{{ $customers->toJson() }}" :multiple="false" open-direction="bottom"></multiselect>
        <div v-if="errors.has('customer')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('customer') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" v-if="form.customer">
    <label for="traders" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.customer.columns.agent_ids') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input disabled type="text" v-model="form.customer.trader_names" class="form-control" >
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('sale_type'), 'has-success': fields.sale_type && fields.sale_type.valid }" >
    <label for="sale_type" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.transaction-detail.columns.sale_type') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div class="input-group">
            <multiselect :disabled="form.status >= 1 || {{ $branch_id }} != {{ app('user_branch_id') }}" v-model="form.sale_type" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" :options="['Retail', 'Wholesale']" :multiple="false" :preselect-first="true" open-direction="bottom"></multiselect>
        </div>
        <div v-if="errors.has('sale_type')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('sale_type') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" v-if="form.status == 1">
    <label for="payment_total" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ __("Total Amount") }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input disabled type="text" v-model="form.total_cost" class="form-control" >
    </div>
</div>

<div class="form-group row align-items-center" v-if="form.status == 1">
    <label for="payment_total" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ __("Total Amount Paid") }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input disabled type="text" v-model="form.total_payments" class="form-control" >
    </div>
</div>

<div class="form-group row align-items-center" v-if="form.status == 1">
    <label for="payment_total" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ __("Outstanding Balance") }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <div class="input-group">
            <input disabled type="text" v-model="form.balance" class="form-control" >
            <div class="input-group-append">
                <button type="button" class="btn btn-block" :class="form.is_paid == 1 ? 'btn-success' : 'btn-primary'" @click="openPaymentModal()">
                    <i class="fa" :class="form.is_paid == 1 ? 'fa-check' : 'fa-money'"></i>
                    Payment Details
                </button>
            </div>
          </div>
    </div>
</div>
