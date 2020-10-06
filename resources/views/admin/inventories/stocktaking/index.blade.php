@extends('admin.partials.master')

@section('title')
    Kiểm kê kho
@endsection

@section('content')
    <stocktaking-list></stocktaking-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/inventory.js') }}"></script>
@endsection
