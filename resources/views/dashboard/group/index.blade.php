<x-app-layout>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item">{{ __('Groups') }}</li>
                        </ol>
                    </div>

                    <h4 class="page-title">{{ __('Groups') }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                @can('create',[\App\Models\Group::class])
                    <div class="text-end col-xs-12 mb-3">
                        <a href="{{ route('group.create') }}" class=" btn btn-primary width-lg">{{ __('Create') }}</a>
                    </div>
                @endcan

                <div class="table-responsive float-center">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Created at') }}</th>
                            <th>{{ __('Level') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($groups as $group)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->created_at->diffForHumans() }}</td>
                                <td>{{ $group->level }}</td>
                                <td>
                                    <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                        <div class="btn-group btn-group-sm " style="float: none;">
                                            @can('update',[\App\Models\Group::class, $group])
                                                <a href="{{ route('group.edit', $group->id) }}" class="border-0 tabledit-edit-button btn btn-primary pt-1" style="float: none;">
                                                    <span class="mdi mdi-pencil"></span>
                                                </a>
                                            @endcan

                                            @can('view',[\App\Models\Permission::class,$group])
                                                <a href="{{ route('permission.index',$group->id) }}" class="border-0 tabledit-edit-button btn btn-primary pt-1" style="float: none;">
                                                    <span class="mdi mdi-account-lock"></span>
                                                </a>
                                            @endcan

                                            @can('delete',[\App\Models\Group::class, $group])

                                                <form action="{{ route('group.destroy', $group->id)}}" method="post">
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
                                <td colspan="5" class="footable-visible">
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
                                    {{ $groups->links() }}
                                </div>
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div> <!-- end col-->
        </div>
</x-app-layout>

