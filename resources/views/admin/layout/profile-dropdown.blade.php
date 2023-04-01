<div class="dropdown-menu dropdown-menu-right">
    <div class="dropdown-header text-center"><strong>{{ trans('brackets/admin-ui::admin.profile_dropdown.account') }}</strong></div>
    {{-- <a href="{{ url('app/profile') }}" class="dropdown-item"><i class="fa fa-user"></i>  {{ trans('brackets/admin-auth::admin.profile_dropdown.profile') }}</a> --}}
    <a href="{{ url('app/password') }}" class="dropdown-item"><i class="fa fa-key"></i>  {{ trans('brackets/admin-auth::admin.profile_dropdown.password') }}</a>
    {{-- Do not delete me :) I'm used for auto-generation menu items --}}
    <a href="{{ url('app/logout') }}" class="dropdown-item"><i class="fa fa-lock"></i> {{ trans('brackets/admin-auth::admin.profile_dropdown.logout') }}</a>
</div>
