@extends($layout . '.partials.master')

@section('title')
    Tài khoản
@endsection

@section('content')
    <user-list></user-list>
@endsection

@section('script')
    <script src="{{ asset('js/modules/user.js') }}"></script>
@endsection
