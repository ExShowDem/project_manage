@extends('admin.partials.master')

@section('title')
    Danh sách nhà thầu phụ
@endsection

@section('content')
    <sub-contractor-list />
@endsection

@section('script')
    <script src="{{ asset('js/modules/sub-contractor.js') }}"></script>
@endsection
