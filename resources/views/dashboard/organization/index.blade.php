@php use App\Models\Organization; @endphp
<x-app-layout>

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item">{{ __('Organizations') }}</li>
                    </ol>
                </div>

                <h4 class="page-title">{{ __('Organizations') }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            @can('create', Organization::class)
                <div class="text-end col-xs-12 mb-3">
                    <a href="{{ route('organization.create') }}"
                       class=" btn btn-primary width-lg">{{ __('Create') }}</a>
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
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Address') }}</th>
                        <th>{{ __('Tax number') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($organizations as $organization)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $organization->name }}</td>
                            <td>{{ $organization->phone }}</td>
                            <td>{{ $organization->address }}</td>
                            <td>{{ $organization->tax_number }}</td>
                            <td>
                                @if($organization->status)
                                    <span class="badge label-table bg-success p-1">{{ __('Active') }}</span>
                                @else
                                    <span class="badge label-table bg-danger p-1">{{ __('In Active') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                    <div class="btn-group btn-group-sm" style="float: none;">
                                        @can('update', $organization)
                                            <a href="{{ route('organization.edit', $organization->id) }}"
                                               class="tabledit-edit-button btn btn-primary mx-1" style="float: none;">
                                                <span class="mdi mdi-pencil"></span>
                                            </a>
                                        @endcan
                                        @can('view',[$organization])
                                            <a href="{{ route('organization.show', $organization->id) }}"
                                               class="tabledit-edit-button btn btn-success mx-1" style="float: none;">
                                                <span class="mdi mdi-eye"></span>
                                            </a>
                                            <a
                                                href="{{ route('organization.transaction', $organization->id) }}"
                                                class="tabledit-edit-button btn btn-info"
                                                style="float: none;">
                                                <span class="mdi mdi-eye"></span>
                                                Transactions
                                            </a>
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
                                {{ $organizations->links() }}
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div> <!-- end col-->
    </div>
</x-app-layout>
