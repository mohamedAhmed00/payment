<x-app-layout>

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('group.index')}}">{{ __('Groups') }}</a></li>
                        <li class="breadcrumb-item">
                        @isset($group)
                            {{ __('Update group') }}
                        @else
                            {{ __('Create group') }}
                        @endisset
                        </li>
                    </ol>
                </div>

                <h4 class="page-title">
                    @isset($group)
                        {{ __('Update group') }}
                    @else
                        {{ __('Create group') }}
                    @endisset
                </h4>
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

                                <form action="{{ !empty($group)? route('group.update',$group->id) : route('group.store') }}" method="post">
                                    @csrf
                                    @isset($group)
                                        @method('PATCH')
                                    @endisset
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('Name') }}</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', !empty($group) ? $group->name : '' ) }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="level" class="form-label">{{ __('Level') }}</label>
                                        <input type="number" name="level" id="level" value="{{ old('level', !empty($group) ? $group->level : '' ) }}" class="form-control">
                                    </div>

                                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('Save') }}</button>

                                </form>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div> <!-- end card-body -->
                </div>
            </div> <!-- end col -->
        </div>


</x-app-layout>
