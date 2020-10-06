@extends('admin.partials.master')

@section('title')
    List Users
@endsection

@section('content-title')
    Users
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/datatables.css') }}">
@endsection

@section('scripts')
    @routes
    <script type='text/javascript' src={{ asset('assets/admin/js/datatables.js') }}></script>
    <script type='text/javascript' src={{ asset('assets/admin/js/users/list.js') }}></script>
    <script src="{{ asset('assets/admin/js/swal.js') }}"></script>
@endsection

@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <span class="caption-subject bold uppercase">List</span>
            </div>
            <div style="float: right">
                <a href="{{ route('users.create') }}" class="btn btn-success">
                    Create
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <table class="table table-bordered border-bottom-none" id="tbl-users">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
