@extends('admin.partials.master')

@section('title')
    Lịch sử kế hoạch vật tư
@endsection

@section('content')
    <plan-supplies-tracking :id="{{ $id ?? null }}" :tracking-route="'api.plans.supplies.tracking'">
    </plan-supplies-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/plan.js') }}"></script>
@endsection
