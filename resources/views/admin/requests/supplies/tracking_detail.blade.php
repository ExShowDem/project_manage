@extends('admin.partials.master')

@section('title')
    Lịch sử yêu cầu vật tư
@endsection

@section('content')
    <request-supplies-tracking-detail :id="{{ $id ?? null }}"
                               :log_id="{{ $log_id }}"
                               :tracking-route="'api.requests.supplies.tracking'"
                               target="{{ $target }}" :project_id="{{ $projectId }}"
    >
    </request-supplies-tracking-detail>
@endsection

@section('script')
    <script src="{{ asset('js/modules/request.js') }}"></script>
@endsection
