@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} yêu cầu vật tư
    @else
        Tạo yêu cầu vật tư
    @endif
@endsection

@section('content')
    @if (isset($id))
        <request-supplies-form :can_approve="{{ $canApprove }}" target="{{ $target }}" :project_id="{{ $projectId }}"
                               project_name="{{ $projectName }}" :id="{{ $id ?? null }}"
                               :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
                               :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></request-supplies-form>
    @else
        <request-supplies-form :can_approve="{{ $canApprove }}" target="{{ $target }}" :project_id="{{ $projectId }}"
                               project_name="{{ $projectName }}" code="{{ $code }}"
                               :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></request-supplies-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/request.js') }}"></script>
@endsection
