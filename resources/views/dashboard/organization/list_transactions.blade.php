<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{route('organization.index')}}">{{ __('Organizations') }}</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('organization.show', $organization_id) }}">{{__('Organization')}}</a>
                        </li>
                        <li class="breadcrumb-item">{{ __('Transactions') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('Transactions') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive float-center">
                <table class="table table-bordered table-centered mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>{{ __('User Name') }}</th>
                        <th>{{ __('User Email') }}</th>
                        <th>{{ __('Customer Name') }}</th>
                        <th>{{ __('Customer Email') }}</th>
                        <th>{{ __('Payment Type') }}</th>
                        <th>{{ __('Payment Method') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Currency') }}</th>
                        <th>{{ __('Action') }}</th>
                        <th>{{ __('Rate') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->user->name }}</td>
                            <td>{{ $transaction->user->email }}</td>
                            <td>{{json_decode($transaction->customer,true)['name']}}</td>
                            <td>{{json_decode($transaction->customer,true)['email']}}</td>
                            <td>{{$transaction->paymentType->name}}</td>
                            <td>{{$transaction?->paymentMethod?->name}}</td>
                            <td>{{$transaction->amount}}</td>
                            <td>{{$transaction->currency}}</td>
                            <td>{{$transaction->action}}</td>
                            <td>{{$transaction->rate}}</td>
                            <td><x-status-badge status="{{$transaction->statuses->first()?->name}}"></x-status-badge></td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="active">
                        <td colspan="7" class="footable-visible border-0">
                            <div class="text-end">
                                {{ $transactions->links() }}
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
