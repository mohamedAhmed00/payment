<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item">{{ __('Users') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('Users') }}</h4>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-xs-12">
                @can('create', \App\Models\User::class)
                    <div class="text-end col-xs-12 mb-3">
                        <a href="{{ route('user.create') }}" class=" btn btn-primary width-lg">{{ __('Create') }}</a>
                    </div>
                @endcan
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <p>{{ optional(session('status'))['message'] }}</p>
                    </div>
                @endif
                <div class="table-responsive float-center">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Group') }}</th>
                            <th>{{ __('Organization') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $user?->name }}</td>
                                <td>{{ $user?->email }}</td>
                                <td>{{ $user?->group?->name }}</td>
                                <td>{{ $user?->organization?->name }}</td>
                                <td>
                                    <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                        <div class="btn-group btn-group-sm " style="float: none;">
                                            @can('update', $user)
                                                <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                    <div class="btn-group btn-group-sm" style="float: none;">
                                                        <a href="{{ route('user.edit', $user?->id) }}" class="tabledit-edit-button btn btn-primary" style="float: none;">
                                                            <span class="mdi mdi-pencil"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endcan
                                            @can('delete',[\App\Models\User::class, $user])

                                                <form action="{{ route('user.destroy', $user?->id)}}" method="post">
                                                    <button type="submit" class="border-0 tabledit-edit-button btn btn-danger ">
                                                        <i class="mdi mdi-close"></i>
                                                    </button>
                                                    @method('delete')
                                                    @csrf
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="active">
                                <td colspan="7" class="footable-visible">
                                    <div class="text-center">
                                        {{ __('No rows found') }}
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr class="active">
                            <td colspan="7" class="footable-visible border-0">
                                <div class="text-end">
                                    {{ $users->links() }}
                                </div>
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div> <!-- end col-->
        </div>
</x-app-layout>

