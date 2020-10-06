@extends('admin.partials.master')

@section('title')
    @if (isset($id))
        {{ isset($isShow) ? 'Xem' : 'Sửa' }} Trình Duyệt Hồ Sơ Nhà Thầu Phụ
    @else
        Tạo Trình Duyệt Hồ Sơ Nhà Thầu Phụ
    @endif
@endsection

@section('content')
    @php $types = json_encode(\App\Enums\CensorSubContractorType::toSelectArray()) @endphp
    @if (isset($id))
        <censor-sub-form :id="{{ $id ?? null }}" :types="{{ $types }}"
                         :is_show="{{ isset($isShow) ? 'true' : 'false' }}"
                         :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></censor-sub-form>
    @else
        <censor-sub-form :types="{{ $types }}" :is_admin="{{ Auth::user()->hasRole('admin') ? 'true' : 'false' }}"></censor-sub-form>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/modules/censor-sub.js') }}"></script>
@endsection
