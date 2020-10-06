@extends('admin.partials.master')

@section('title')
    Lịch sử kiểm kê
@endsection

@section('content')
    <stocktaking-tracking-detail :id="{{ $id ?? null }}"
                               :log_id="{{ $log_id }}"
                               :tracking-route="'api.requests.supplies.tracking'"
    >
    </stocktaking-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/inventory.js') }}"></script>
@endsection
