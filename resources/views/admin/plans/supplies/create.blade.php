@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} kế hoạch vật tư
    @else
        Tạo kế hoạch vật tư
    @endif
@endsection

@section('content')
    @if (isset($id))
        <plan-supplies-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}"
                            :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
                            :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></plan-supplies-form>
    @else
        <plan-supplies-form :can_approve="{{ $canApprove }}" code="{{ $code }}"
                            :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></plan-supplies-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/plan.js') }}"></script>
@endsection
