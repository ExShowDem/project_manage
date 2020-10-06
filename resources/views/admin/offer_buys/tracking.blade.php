@extends('admin.partials.master')

@section('title')
    Lịch sử đề xuất mua vật tư
@endsection

@section('content')
    <offer-buy-tracking :id="{{ $id ?? null }}" :tracking-route="'api.offer-buys.tracking'">
    </offer-buy-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/offer-buy.js') }}"></script>
@endsection
