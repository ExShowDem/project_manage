@extends('admin.partials.master')

@section('title')
    Xuất kho
@endsection

@section('content')
    @if (isset($id))
        <delivery-on-demand-form :id="{{ $id ?? null }}"></delivery-on-demand-form>
    @else
        <delivery-on-demand-form></delivery-on-demand-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/inventory.js') }}"></script>
@endsection
