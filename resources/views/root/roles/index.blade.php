@extends($layout . '.partials.master')

@section('title')
    Vai trò/Chức vụ
@endsection

@section('content')
    <role-list></role-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/role.js') }}"></script>
@endsection
