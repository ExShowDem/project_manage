@extends('admin.partials.master')

@section('title')
    Danh mục vật tư
@endsection

@section('content')
    <supplies-list></supplies-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/supplies.js') }}"></script>
@endsection
