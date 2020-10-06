@extends('admin.partials.master')

@section('title')
    Danh sách kế hoạch vật tư
@endsection

@section('content')
    <plan-supplies-list></plan-supplies-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/plan.js') }}"></script>
@endsection
