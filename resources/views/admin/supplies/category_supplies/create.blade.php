@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        Sửa nhóm vật tư
    @else
        Tạo nhóm vật tư
    @endif
@endsection

@section('content')
    @if (isset($id))
        <category-supplies-form :id="{{ $id ?? null }}"></category-supplies-form>
    @else
        <category-supplies-form></category-supplies-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/category-supplies.js') }}"></script>
@endsection
