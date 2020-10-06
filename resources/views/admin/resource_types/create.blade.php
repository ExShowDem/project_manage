@extends('admin.partials.master')

@section('title')
    Tạo loại nguồn lực
@endsection

@section('content')
    <resource-types-form :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></resource-types-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/resource-types.js') }}"></script>
@endsection
