@extends('admin.partials.master')

@section('title')
    Chi tiết kho
@endsection

@section('content')
    <inventory-detail-list></inventory-detail-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/inventory.js') }}"></script>
@endsection
