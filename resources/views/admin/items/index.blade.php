@extends('admin.partials.master')

@section('title')
    Danh sách hạng mục
@endsection

@section('content')
    <item-list />
@endsection

@section('script')
    <script src="{{ asset('js/modules/item.js') }}"></script>
@endsection
