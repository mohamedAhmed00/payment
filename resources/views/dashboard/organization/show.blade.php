@php use App\Models\User; @endphp
<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{route('organization.index')}}">{{ __('Organizations') }}</a></li>
                        <li class="breadcrumb-item">{{ __('organization') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('Organization') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ps-xl-3 mt-3 mt-xl-0">
                                <h4 class="mb-3">{{ __('Organization name') }} : {{ $organization->name }}</h4>
                                <h5 class="mb-3">{{ __('Phone') }} : {{ $organization->phone }}</h5>
                                <h5 class="mb-3">{{ __('Tax number') }} : {{ $organization->tax_number }}</h5>
                                <h5 class="mb-3">{{ __('Address') }} : {{ $organization->address }}</h5>
                                <h5 class="mb-3">{{ __('Email') }} : {{ $organization->email }}</h5>
                                <h5 class="mb-3">{{ __('status') }} :
                                    @if($organization->status)
                                        <span class="badge label-table bg-success p-1">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge label-table bg-danger p-1">{{ __('In Active') }}</span>
                                    @endif
                                </h5>
                                <h5> {{ __('Payment types') }} :
                                    @foreach($organization->paymentTypes as $type)
                                        {{ $type->name }}
                                        @if(!$loop->last)
                                            -
                                        @endif

                                    @endforeach
                                </h5>
                                <h5> {{ __('Payment suppliers') }} :
                                    @foreach($organization->suppliers as $supplier)
                                        {{ $supplier->name }}
                                        @if(!$loop->last)
                                            -
                                        @endif
                                    @endforeach
                                </h5>
                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                    @can('viewAny',[User::class])

                        <div class="table-responsive mt-4">
                            <h5>{{ __('Users') }}</h5>
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Group') }}</th>
                                    <th>{{ __('Payment Settings') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($organization->users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->group->name }}</td>
                                        <td>dfg</td>
                                        <td>
                                            <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                <div class="btn-group btn-group-sm " style="float: none;">
                                                    <div class="tabledit-toolbar btn-toolbar mx-1"
                                                         style="text-align: left;">
                                                        <div class="btn-group btn-group-sm" style="float: none;">
                                                            @can('paymentSettings',[$user])
                                                                <a href="{{ route('user.payment_settings', $user->id) }}"
                                                                   class="tabledit-edit-button btn btn-success"
                                                                   style="float: none;">
                                                                    <span class="mdi mdi-eye"></span>
                                                                </a>

                                                            @endcan
                                                            @can('update', $user)
                                                                <a href="{{ route('user.edit', $user->id) }}"
                                                                   class="tabledit-edit-button btn btn-primary"
                                                                   style="float: none;">
                                                                    <span class="mdi mdi-pencil"></span>
                                                                </a>
                                                            @endcan
                                                            @can('delete',[User::class, $user])

                                                                <form action="{{ route('user.destroy', $user->id)}}"
                                                                      method="post">
                                                                    <button type="submit"
                                                                            class="border-0 tabledit-edit-button btn btn-danger mx-1">
                                                                        <i class="mdi mdi-close"></i>
                                                                    </button>
                                                                    @method('delete')
                                                                    @csrf
                                                                </form>
                                                            @endcan
                                                            @can('listAuthTransactions', [User::class, $user])
                                                                    <a
                                                                        href="{{ route('user.transactions', $user->id) }}"
                                                                        class="tabledit-edit-button btn btn-info"
                                                                        style="float: none;">
                                                                        <span class="mdi mdi-eye"></span>
                                                                        Transactions
                                                                    </a>

                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
