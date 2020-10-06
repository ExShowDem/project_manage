@extends('admin.partials.master')

@section('title')
    Xem loại nguồn lực
@endsection

@section('content')
    <resource-types-form :id="{{ $id ?? null }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></resource-types-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/resource-types.js') }}"></script>
@endsection
