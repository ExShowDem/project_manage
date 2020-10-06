@extends('admin.partials.master')

@section('title')
    Tạo kiểm kê thiết bị
@endsection

@section('content')
    <device-inventory-form code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-inventory-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-inventory.js') }}"></script>
@endsection
