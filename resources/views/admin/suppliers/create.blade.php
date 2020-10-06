@extends('admin.partials.master')

@section('title')
    @php
        $typeLabel = $type === 'supplier' ? 'nhà cung cấp' : 'khách hàng';
        $title = (isset($id) ? 'Sửa ' : 'Tạo ') . $typeLabel;
    @endphp
    {{$title}}
@endsection

@section('content')
    @if (isset($id))
        <supplier-form title="{{$title}}" type="{{$type}}" :id="{{ $id ?? null }}"></supplier-form>
    @else
        <supplier-form title="{{$title}}" type="{{$type}}"></supplier-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/supplier.js') }}"></script>
@endsection
