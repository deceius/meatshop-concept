<div class="form-group row align-items-center" :class="{'has-danger': errors.has('pullout_transaction_id'), 'has-success': fields.pullout_transaction_id && fields.pullout_transaction_id.valid }">
    <label for="pullout_transaction_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.transfer.columns.pullout_transaction_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.pullout_transaction_id" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('pullout_transaction_id'), 'form-control-success': fields.pullout_transaction_id && fields.pullout_transaction_id.valid}" id="pullout_transaction_id" name="pullout_transaction_id" placeholder="{{ trans('admin.transfer.columns.pullout_transaction_id') }}">
        <div v-if="errors.has('pullout_transaction_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('pullout_transaction_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('delivery_transaction_id'), 'has-success': fields.delivery_transaction_id && fields.delivery_transaction_id.valid }">
    <label for="delivery_transaction_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.transfer.columns.delivery_transaction_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.delivery_transaction_id" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('delivery_transaction_id'), 'form-control-success': fields.delivery_transaction_id && fields.delivery_transaction_id.valid}" id="delivery_transaction_id" name="delivery_transaction_id" placeholder="{{ trans('admin.transfer.columns.delivery_transaction_id') }}">
        <div v-if="errors.has('delivery_transaction_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('delivery_transaction_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('created_by'), 'has-success': fields.created_by && fields.created_by.valid }">
    <label for="created_by" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.transfer.columns.created_by') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.created_by" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('created_by'), 'form-control-success': fields.created_by && fields.created_by.valid}" id="created_by" name="created_by" placeholder="{{ trans('admin.transfer.columns.created_by') }}">
        <div v-if="errors.has('created_by')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('created_by') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('updated_by'), 'has-success': fields.updated_by && fields.updated_by.valid }">
    <label for="updated_by" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.transfer.columns.updated_by') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.updated_by" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('updated_by'), 'form-control-success': fields.updated_by && fields.updated_by.valid}" id="updated_by" name="updated_by" placeholder="{{ trans('admin.transfer.columns.updated_by') }}">
        <div v-if="errors.has('updated_by')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('updated_by') }}</div>
    </div>
</div>


