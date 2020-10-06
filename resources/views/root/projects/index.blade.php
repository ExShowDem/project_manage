@extends('root.partials.master')

@section('title')
    Danh sách dự án
@endsection

@section('content')
    <project-list></project-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/project.js') }}"></script>
@endsection
