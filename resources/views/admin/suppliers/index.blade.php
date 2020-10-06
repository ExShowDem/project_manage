@extends('admin.partials.master')

@section('title')
    Danh sách {{$type === 'supplier' ? 'nhà cung cấp' : 'khách hàng'}}
@endsection

@section('content')
    <supplier-list type="{{$type}}"></supplier-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/supplier.js') }}"></script>
@endsection
