@extends('admin.partials.master')

@section('title')
    Lịch sử kế hoạch vật tư
@endsection

@section('content')
    <plan-supplies-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.plans.supplies.tracking'">
    </plan-supplies-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/plan.js') }}"></script>
@endsection
