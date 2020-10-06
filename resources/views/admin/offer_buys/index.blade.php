@extends('admin.partials.master')

@section('title')
    Danh sách đề xuất mua vật tư
@endsection

@section('content')
    <offer-buy-list></offer-buy-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/offer-buy.js') }}"></script>
@endsection
