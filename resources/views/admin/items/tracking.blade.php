@extends('admin.partials.master')

@section('title')
    Lịch sử hạng mục
@endsection

@section('content')
    <item-tracking :id="{{ $id ?? null }}" :tracking-route="'api.items.tracking'">
    </item-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/item.js') }}"></script>
@endsection
