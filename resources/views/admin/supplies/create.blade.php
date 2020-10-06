@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        Sửa vật tư
    @else
        Tạo vật tư
    @endif
@endsection

@section('content')
    @if (isset($id))
        <supplies-form :id="{{ $id ?? null }}"></supplies-form>
    @else
        <supplies-form></supplies-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/supplies.js') }}"></script>
@endsection
