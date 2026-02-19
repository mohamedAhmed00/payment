<x-app-layout>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{route('group.index')}}">{{ __('Groups') }}</a></li>
                            <li class="breadcrumb-item">{{ __('Assign Permission to group') }}</li>
                        </ol>
                    </div>

                    <h4 class="page-title">{{ __('Assign Permission to group') }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">

                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif
                                @can('create', \App\Models\Permission::class)
                                    <div class="text-end col-xs-12 mb-3">
                                        <a href="{{ route('permission.seed') }}" class=" btn btn-primary width-lg">{{ __('Refresh permissions') }}</a>
                                    </div>
                                @endcan


                                    <form action="{{ route('permission.update',$group->id) }}" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <div class="mb-3">
                                        @foreach($permissions as $permission)
                                            <div class="form-check">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission['key'] }}" {{ in_array($permission['key'],$group_permissions)? 'checked' : ''  }} class="form-check-input" id="customCheck{{ $permission['key'] }}">
                                                <label class="form-check-label" for="customCheck{{ $permission['key'] }}">{{trans('permissions.'.$permission['key'])}}</label>
                                            </div>
                                        @endforeach
                                        </div>
                                        @can('update',[\App\Models\Permission::class,$group])
                                            <button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('Save') }}</button>
                                        @endcan


                                </form>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div> <!-- end card-body -->
                </div>
            </div> <!-- end col -->
        </div>
</x-app-layout>
