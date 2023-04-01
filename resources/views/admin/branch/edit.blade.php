@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.branch.actions.edit', ['name' => $branch->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <branch-form
                :action="'{{ $branch->resource_url }}'"
                :data="{{ $branch->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.branch.actions.edit', ['name' => $branch->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.branch.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </branch-form>

        </div>
    
</div>

@endsection