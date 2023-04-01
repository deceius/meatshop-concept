<div class="form-group row align-items-center" :class="{'has-danger': errors.has('qr_code'), 'has-success': fields.qr_code && fields.qr_code.valid }">
    <label for="qr_code" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.transaction-detail.columns.qr_code') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" readonly v-model="form.qr_code"   class="form-control" :class="{'form-control-danger': errors.has('qr_code'), 'form-control-success': fields.qr_code && fields.qr_code.valid}" id="qr_code" name="qr_code" placeholder="{{ trans('admin.transaction-detail.columns.qr_code') }}">
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('item'), 'has-success': fields.item && fields.item.valid }">
    <label for="item" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.transaction-detail.columns.item_id') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <multiselect v-model="form.item" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" :custom-label="({ brand_name, name }) => `${brand_name} - ${name}`"  track-by="id" :options="{{ $items->toJson() }}" :multiple="false" open-direction="bottom"></multiselect>
        <div v-if="errors.has('item')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('item') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('quantity'), 'has-success': fields.quantity && fields.quantity.valid }">
    <label for="quantity" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.transaction-detail.columns.quantity') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.quantity" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('quantity'), 'form-control-success': fields.quantity && fields.quantity.valid}" id="quantity" name="quantity" placeholder="{{ trans('admin.transaction-detail.columns.quantity') }}">
        <div v-if="errors.has('quantity')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('quantity') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('amount'), 'has-success': fields.amount && fields.amount.valid }">
    <label for="amount" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.transaction-detail.columns.amount') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.amount" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('amount'), 'form-control-success': fields.amount && fields.amount.valid}" id="amount" name="amount" placeholder="{{ trans('admin.transaction-detail.columns.amount') }}">
        <div v-if="errors.has('amount')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('amount') }}</div>
    </div>
</div>




@section('bottom-scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
@endsection
