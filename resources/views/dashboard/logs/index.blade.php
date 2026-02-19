<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item">{{ __('Activity Logs') }}</li>
                    </ol>
                </div>

                <h4 class="page-title">{{ __('Activity Logs') }}</h4>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive float-center">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Route name') }}</th>
                                <th>{{ __('IP') }}</th>
                                <th>{{ __('Organization') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $loop?->iteration }}</td>
                                <td>{{ $log?->subject }}</td>
                                <td><a href="{{$log->url }}" class="text-blue-500"> {{$log?->route_name}} </a></td>
                                <td>{{$log?->ip }}</td>
                                <td>{{ $log?->organization?->name }}</td>
                                <td>{{ $log?->user?->name }}</td>
                                <td>{{ $log?->created_at->diffForHumans() }}</td>
                                <td>{{ $log?->agent }}</td>
                            </tr>
                        @empty
                            <tr class="active">
                                <td colspan="8" class="footable-visible">
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
                                    {{ $logs->links() }}
                                </div>
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div> <!-- end col-->
        </div>
</x-app-layout>
