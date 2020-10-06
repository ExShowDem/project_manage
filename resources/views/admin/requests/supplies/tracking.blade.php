@extends('admin.partials.master')

@section('title')
    Lịch sử yêu cầu vật tư
@endsection

@section('content')
    <request-supplies-tracking :id="{{ $id ?? null }}"
                               :tracking-route="'api.requests.supplies.tracking'"
                               target="{{ $target }}" :project_id="{{ $projectId }}"
    >
    </request-supplies-tracking>
@endsection

@section('script')
    <script src="{{ asset('js/modules/request.js') }}"></script>
@endsection
