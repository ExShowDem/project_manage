@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        Sửa nguồn nhân lực
    @else
        Tạo nguồn nhân lực
    @endif
@endsection

@section('content')
    @if (isset($id))
        <resource-form :id="{{ $id ?? null }}"></resource-form>
    @else
        <resource-form></resource-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/resource.js') }}"></script>
@endsection
