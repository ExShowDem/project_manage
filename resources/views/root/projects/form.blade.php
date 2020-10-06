@extends('root.partials.master')

@section('title')
    Tạo dự án
@endsection

@section('content')
    @if (isset($id))
        <project-form :id="{{ $id ?? null }}"></project-form>
    @else
        <project-form></project-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/project.js') }}"></script>
@endsection
