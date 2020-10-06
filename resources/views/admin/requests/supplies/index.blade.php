@extends('admin.partials.master')

@section('title')
    Danh sách yêu cầu vật tư
@endsection

@section('content')
    @if (isset($companyId))
        <request-supplies-list :company_id="{{ $companyId }}"></request-supplies-list>
    @else
        <request-supplies-list></request-supplies-list>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/request.js') }}"></script>
@endsection
