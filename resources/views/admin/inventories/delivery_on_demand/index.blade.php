@extends('admin.partials.master')

@section('title')
    Xuất kho theo yêu cầu
@endsection

@section('content')
    <delivery-on-demand-list></delivery-on-demand-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/inventory.js') }}"></script>
@endsection
