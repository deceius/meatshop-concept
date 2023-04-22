<div class="sidebar sidebar-show">
    <nav class="sidebar-nav">
        <ul class="nav">

           {{-- <li class="nav-item"><a class="nav-link" href="{{ url('app/users') }}"><i class="nav-icon icon-umbrella"></i> {{ trans('admin.user.title') }}</a></li>
           <li class="nav-item"><a class="nav-link" href="{{ url('app/permissions') }}"><i class="nav-icon icon-umbrella"></i> {{ trans('admin.permission.title') }}</a></li>
           <li class="nav-item"><a class="nav-link" href="{{ url('app/roles') }}"><i class="nav-icon icon-compass"></i> {{ trans('admin.role.title') }}</a></li> --}}
           {{-- <li class="nav-item"><a class="nav-link" href="{{ url('app/transaction-details') }}"><i class="nav-icon icon-globe"></i> {{ trans('admin.transaction-detail.title') }}</a></li> --}}

           @if (in_array(app('user_tier_id'), app('employee_access')))
           <li class="nav-title">Main</li>
            <li class="nav-item"><a class="nav-link" href="{{ url('app/transaction-details') }}"><i class="nav-icon icon-layers"></i> Inventory</a></li>
            <li class="nav-dropdown nav-item">
                <a class="nav-link nav-dropdown-toggle"
                href="#">
                <i class="nav-icon icon-grid"></i>
                Transactions
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item"><a class="nav-link" href="{{ url('app/transaction-headers').'/1' }}"><i class="nav-icon icon-note"></i> {{ trans('admin.receiving.title') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('app/transaction-headers').'/2' }}"><i class="nav-icon icon-basket"></i> {{ trans('admin.sales.title') }}</a></li>
                </ul>
            </li>
            @endif
            @if (in_array(app('user_tier_id'), app('manager_access')))
            <li class="nav-title">Branch Management</li>

            <li class="nav-item"><a class="nav-link" href="{{ url('app/transfers') }}"><i class="nav-icon icon-share-alt"></i> Transfer Module </a></li>
            <li class="nav-dropdown nav-item">
                <a class="nav-link nav-dropdown-toggle"
                href="#">
                <i class="nav-icon icon-speech"></i>
                Customer Data
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item"><a class="nav-link" href="{{ url('app/customers') }}"><i class="nav-icon icon-people"></i> Customers </a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('app/traders') }}"><i class="nav-icon icon-user"></i> {{ trans('admin.trader.title') }}</a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ url('app/prices') }}"><i class="nav-icon icon-tag"></i> {{ trans('admin.price.title') }}</a></li>
           <li class="nav-item"><a class="nav-link" href="{{ url('app/expenses') }}"><i class="nav-icon icon-wallet"></i> {{ trans('admin.expense.title') }}</a></li>

           <li class="nav-title">Reports</li>
           <li class="nav-dropdown nav-item">
                <a class="nav-link nav-dropdown-toggle"
                href="#">
                <i class="nav-icon icon-briefcase"></i>
                Sales
                </a>
                <ul class="nav-dropdown-items">
                       <li class="nav-item"><a class="nav-link" href="{{ url('app/transaction-details/sales-report') }}"><i class="nav-icon icon-chart"></i>Item Sales</a></li>
                       <li class="nav-item"><a class="nav-link" href="{{ url('app/transaction-details/sales-forecasting') }}"><i class="nav-icon icon-chart"></i>Forecasting</a></li>
                </ul>
            </li>
           <li class="nav-item"><a class="nav-link" href="{{ url('app/expenses/expense-report') }}"><i class="nav-icon icon-chart"></i>Sales & Expenses</a></li>
            @endif
            @if (in_array(app('user_tier_id'), app('admin_access')))
            <li class="nav-title">{{ trans('admin.terms.item_master_data') }}</li>
            <li class="nav-dropdown nav-item">
                <a class="nav-link nav-dropdown-toggle"
                href="#">
                <i class="nav-icon icon-folder-alt"></i>
                Master Data
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item"><a class="nav-link" href="{{ url('app/items') }}"><i class="nav-icon icon-docs"></i> {{ trans('admin.item.title') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('app/types') }}"><i class="nav-icon icon-docs"></i> {{ trans('admin.type.title') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('app/brands') }}"><i class="nav-icon icon-docs"></i> {{ trans('admin.brand.title') }}</a></li>
                </ul>
            </li>


            <li class="nav-title">{{ trans('brackets/admin-ui::admin.sidebar.settings') }}</li>

            <li class="nav-dropdown nav-item">
                <a class="nav-link nav-dropdown-toggle"
                href="#">
                <i class="nav-icon icon-settings"></i>
                Settings
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item"><a class="nav-link" href="{{ url('app/access-tiers') }}"><i class="nav-icon icon-lock"></i> {{ trans('admin.access-tier.title') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('app/branches') }}"><i class="nav-icon icon-location-pin"></i> {{ trans('admin.branch.title') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('app/admin-users') }}"><i class="nav-icon icon-user-following"></i> {{ __('User Management') }}</a></li>
                </ul>
            </li>

            @endif
           {{-- Do not delete me :) I'm used for auto-generation menu items --}}
            {{-- <li class="nav-item"><a class="nav-link" href="{{ url('app/translations') }}"><i class="nav-icon icon-location-pin"></i> {{ __('Translations') }}</a></li> --}}
            {{-- Do not delete me :) I'm also used for auto-generation menu items --}}
            {{-- <li class="nav-item"><a class="nav-link" href="{{ url('app/configuration') }}"><i class="nav-icon icon-settings"></i> {{ __('Configuration') }}</a></li> --}}
        </ul>
    </nav>

    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
