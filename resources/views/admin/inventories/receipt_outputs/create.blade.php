@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} xuất kho
    @else
        Tạo xuất kho
    @endif
@endsection

@section('content')
    @if (isset($id))
        <receipt-output-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}"
                             :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
                             :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></receipt-output-form>
    @elseif ($supplyEachRequestIds)
        <receipt-output-form :can_approve="{{ $canApprove }}" code="{{ $code }}"
                             :supply-each-request-ids="{{ json_encode($supplyEachRequestIds) }}"
                             :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></receipt-output-form>
    @elseif ($offerSupplyIds)
        <receipt-output-form :can_approve="{{ $canApprove }}" code="{{ $code }}"
                             :offer-supply-ids="{{ json_encode($offerSupplyIds) }}"
                             :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></receipt-output-form>
    @else
        <receipt-output-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></receipt-output-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/receipt-output.js') }}"></script>
@endsection
