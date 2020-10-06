@extends('admin.partials.master')

@section('title')
    Lịch sử kiểm kê
@endsection

@section('content')
    <stocktaking-tracking :id="{{ $id ?? null }}"
                               :tracking-route="'api.inventories.stocktaking.tracking'"
    >
    </stocktaking-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/inventory.js') }}"></script>
@endsection
