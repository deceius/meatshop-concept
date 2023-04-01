@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.type.actions.edit', ['name' => $type->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <type-form
                :action="'{{ $type->resource_url }}'"
                :data="{{ $type->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.type.actions.edit', ['name' => $type->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.type.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </type-form>

        </div>
    
</div>

@endsection