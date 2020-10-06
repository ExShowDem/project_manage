@extends('root.partials.master')

@section('title')
    My Profile
@endsection

@section('content-title')
    My Profile
@endsection

@section('content')
    <div class="page-content">
        <div class="container">
            @include('admin.partials.status')
            @include('admin.profile.update_information')
            @include('admin.profile.change_password')
        </div>
    </div>
@endsection
