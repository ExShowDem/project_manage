@extends('admin.partials.master')

@section('title')
    Lịch sử đề xuất mua vật tư
@endsection

@section('content')
    <offer-buy-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}">
    </offer-buy-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/offer-buy.js') }}"></script>
@endsection
