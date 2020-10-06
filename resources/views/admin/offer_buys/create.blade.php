@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} đề xuất mua vật tư
    @else
        Tạo đề xuất mua vật tư
    @endif
@endsection

@section('content')
    @if (isset($id))
        <offer-buy-form :can_approve="{{ $canApprove }}" :id="{{ $id ?? null }}"
                        :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
                        :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></offer-buy-form>
    @elseif ($supplyEachRequestIds)
        <offer-buy-form :can_approve="{{ $canApprove }}" code="{{ $code }}"
                             :supply-each-request-ids="{{ json_encode($supplyEachRequestIds) }}"
                             :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></offer-buy-form>
    @elseif ($offerSupplyIds)
        <offer-buy-form :can_approve="{{ $canApprove }}" code="{{ $code }}"
                             :offer-supply-ids="{{ json_encode($offerSupplyIds) }}"
                             :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></offer-buy-form>
    @else
        <offer-buy-form :can_approve="{{ $canApprove }}" code="{{ $code }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></offer-buy-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/offer-buy.js') }}"></script>
@endsection
