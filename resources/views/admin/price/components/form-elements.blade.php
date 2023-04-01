<div class="form-group row align-items-center" :class="{'has-danger': errors.has('item'), 'has-success': fields.item && fields.item.valid }">
    <label for="item" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.price.columns.item_id') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <multiselect v-model="form.item" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" :custom-label="({ brand_name, name }) => `${brand_name} - ${name}`" track-by="id" :options="{{ $items->toJson() }}" :multiple="false" open-direction="bottom"></multiselect>
        <div v-if="errors.has('item')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('item') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('branch'), 'has-success': fields.branch && fields.branch.valid }">
    <label for="item" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.receiving.columns.branch_id') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <multiselect  :disabled="true" v-model="form.branch" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" label="name" track-by="id" :options="{{ $branches->toJson() }}" :multiple="false" open-direction="bottom"></multiselect>
        <div v-if="errors.has('branch')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('branch') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('box_amount'), 'has-success': fields.box_amount && fields.box_amount.valid }">
    <label for="box_amount" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.price.columns.box_amount') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.box_amount" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('box_amount'), 'form-control-success': fields.box_amount && fields.box_amount.valid}" id="box_amount" name="box_amount" placeholder="{{ trans('admin.price.columns.box_amount') }}">
        <div v-if="errors.has('box_amount')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('box_amount') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('cut_amount'), 'has-success': fields.cut_amount && fields.cut_amount.valid }">
    <label for="cut_amount" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.price.columns.cut_amount') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.cut_amount" v-validate="'required|decimal'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('cut_amount'), 'form-control-success': fields.cut_amount && fields.cut_amount.valid}" id="cut_amount" name="cut_amount" placeholder="{{ trans('admin.price.columns.cut_amount') }}">
        <div v-if="errors.has('cut_amount')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('cut_amount') }}</div>
    </div>
</div>


