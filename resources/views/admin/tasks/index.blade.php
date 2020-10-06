@extends('admin.partials.master')

@section('title')
    Công việc cần xử lý
@endsection

@section('content')
    <my-tasks-list></my-tasks-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/task.js') }}"></script>
@endsection
