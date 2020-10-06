@extends('admin.partials.master')

@section('title')
    Xem kiểm kê thiết bị
@endsection

@section('content')
    <device-inventory-form :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></device-inventory-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/device-inventory.js') }}"></script>
@endsection
