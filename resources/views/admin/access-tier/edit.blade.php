@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.access-tier.actions.edit', ['name' => $accessTier->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <access-tier-form
                :action="'{{ $accessTier->resource_url }}'"
                :data="{{ $accessTier->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.access-tier.actions.edit', ['name' => $accessTier->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.access-tier.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </access-tier-form>

        </div>
    
</div>

@endsection