@extends('admin.partials.master')

@section('title')
    Tạo Trình Duyệt Hồ Sơ Nhà Thầu Phụ
@endsection

@section('content')
    @php $types = json_encode(\App\Enums\CensorSubContractorType::toSelectArray()) @endphp
    <censor-sub-form :types="{{ $types }}"
                     :log_id="{{ $log_id }}"
                     :is_show="{{ true }}"></censor-sub-form>
@endsection

@section('script')
    <script src="{{ asset('js/modules/censor-sub.js') }}"></script>
@endsection
