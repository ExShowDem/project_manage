@extends('admin.partials.master')

@section('title')
    Nhóm vật tư
@endsection

@section('content')
    <category-supplies-list></category-supplies-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/category-supplies.js') }}"></script>
@endsection
