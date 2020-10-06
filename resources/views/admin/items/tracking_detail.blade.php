@extends('admin.partials.master')

@section('title')
    Lịch sử hạng mục vật tư
@endsection

@section('content')
    <item-tracking-detail :id="{{ $id ?? null }}" :log_id="{{ $log_id }}" :tracking-route="'api.items.tracking'">
    </item-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/item.js') }}"></script>
@endsection
