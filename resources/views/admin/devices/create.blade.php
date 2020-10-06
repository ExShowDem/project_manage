@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        Sửa thiết bị
    @else
        Tạo thiết bị
    @endif
@endsection

@section('content')
    @if (isset($id))
        <devices-form :id="{{ $id ?? null }}"></devices-form>
    @else
        <devices-form></devices-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/devices.js') }}"></script>
@endsection
