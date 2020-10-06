@extends('admin.partials.master')

@section('title')
    Danh sách nguồn nhân lực
@endsection

@section('content')
    <resource-list></resource-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/resource.js') }}"></script>
@endsection
