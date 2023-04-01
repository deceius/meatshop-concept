<div class="form-group row align-items-center" :class="{'has-danger': errors.has('tier_id'), 'has-success': fields.tier_id && fields.tier_id.valid }">
    <label for="tier_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.access-tier.columns.tier_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
            <multiselect v-model="form.tier_id" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}"  :options="[1, 2, 3, 4] " :multiple="false" open-direction="bottom"></multiselect>
        <div v-if="errors.has('tier_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('tier_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('user'), 'has-success': fields.user && fields.user.valid }">
    <label for="user" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.access-tier.columns.user_id') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <multiselect v-model="form.user" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" label="email" track-by="id" :options="{{ $users->toJson() }}" :multiple="false" open-direction="bottom"></multiselect>
        <div v-if="errors.has('user')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('user') }}</div>
    </div>
</div>
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('branch'), 'has-success': fields.branch && fields.branch.valid }">
    <label for="branch" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.access-tier.columns.branch_id') }}</label>
    <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <multiselect v-model="form.branch" placeholder="{{ trans('brackets/admin-ui::admin.forms.select_options') }}" label="name" track-by="id" :options="{{ $branches->toJson() }}" :multiple="false" open-direction="bottom"></multiselect>
        <div v-if="errors.has('branch')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('branch') }}</div>
    </div>
</div>
