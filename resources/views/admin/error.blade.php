@extends('root.partials.master')

@section('title')
    ERROR
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12" style="text-align: center;
    padding: 50px;
    line-height: 70px;">
            <h1 style="color:red;">Bạn không có quyền truy cập trang này.</h1>
            <a href="{{ route('login') }}">Nhấn vào đây để trở lại.</a>
        </div>
    </div>
@endsection
