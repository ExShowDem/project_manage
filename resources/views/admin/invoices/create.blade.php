@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} hoá đơn mua vật tư
    @else
        Tạo hoá đơn mua vật tư
    @endif
@endsection

@section('content')
    @if (isset($id))
        <invoice-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}"
                      :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
                      :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></invoice-form>
    @elseif (isset($offerBuyId))
        <invoice-form :can_approve="{{ $canApprove }}" :offer-buy-id="{{ $offerBuyId ?? null }}"
                      :offer-buy-name="{{ json_encode($offerBuyName) ?? null }}"
                      :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></invoice-form>
    @elseif ($supplyEachRequestIds)
        <invoice-form :can_approve="{{ $canApprove }}" code="{{ $code }}"
                      :supply-each-request-ids="{{ json_encode($supplyEachRequestIds) ?? null }}"
                      :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></invoice-form>
    @else
        <invoice-form code="{{ $code }}" :can_approve="{{ $canApprove }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></invoice-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/invoice.js') }}"></script>
@endsection
