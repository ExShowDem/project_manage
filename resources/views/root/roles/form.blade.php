@extends($layout . '.partials.master')

@section('title')
    @if (isset($id))
        Sửa Vai trò/Chức vụ
    @else
        Thêm Vai trò/Chức vụ
    @endif
@endsection

@section('content')
    @if (isset($id))
        <role-form :id="{{ $id ?? null }}" :permissions="{{ json_encode($permissions) }}"></role-form>
    @else
        <role-form :permissions="{{ json_encode($permissions) }}"></role-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/role.js') }}"></script>
@endsection
