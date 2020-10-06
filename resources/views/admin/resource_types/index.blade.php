@extends('admin.partials.master')

@section('title')
    Loại nguồn lực
@endsection

@section('content')
    <resource-types-list></resource-types-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/resource-types.js') }}"></script>
@endsection
