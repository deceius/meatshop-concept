<div class="form-group row align-items-center" :class="{'has-danger': errors.has('trader_name'), 'has-success': fields.trader_name && fields.trader_name.valid }">
    <label for="trader_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.trader.columns.trader_name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.trader_name" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('trader_name'), 'form-control-success': fields.trader_name && fields.trader_name.valid}" id="trader_name" name="trader_name" placeholder="{{ trans('admin.trader.columns.trader_name') }}">
        <div v-if="errors.has('trader_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('trader_name') }}</div>
    </div>
</div>



