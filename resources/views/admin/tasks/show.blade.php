@extends('admin.partials.master')

@section('title')
    Công việc của tôi
@endsection

@section('content')
    <detail-task id="{{ $id }}"></detail-task>
@endsection

@section('script')
    <script src="{{ asset('js/modules/task.js') }}"></script>
@endsection
