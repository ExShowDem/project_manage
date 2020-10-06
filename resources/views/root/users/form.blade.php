@extends($layout . '.partials.master')

@section('title')
    @if (isset($id))
        Sửa tài khoản
    @else
        Tạo tài khoản
    @endif
@endsection

@section('content')
    @if (isset($id))
        <user-form :id="{{ $id ?? null }}"></user-form>
    @else
        <user-form></user-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/user.js') }}"></script>
@endsection
