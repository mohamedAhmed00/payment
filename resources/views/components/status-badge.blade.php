@props(['status'])

@if($status == 'fraud' || $status == 'failed')
    <span class="badge bg-soft-danger text-danger p-2" style="font-weight: bold; font-size: 12px">{{ucfirst($status)}}</span>
@elseif($status == 'paid' || $status == 'refunded')
    <span class="badge bg-soft-success text-success p-2" style="font-weight: bold; font-size: 12px">{{ucfirst($status)}}</span>
@else
    <span class="badge bg-soft-warning text-warning p-2" style="font-weight: bold; font-size: 12px">{{ucfirst($status)}}</span>
@endif
