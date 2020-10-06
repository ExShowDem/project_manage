@extends('admin.partials.master')

@section('title')
    Admin Dashboard
@endsection


@section('content')



        <div class="alert-text" style="text-align: center;
    font-size: 40px; color:#ffc10e">
            <code>Phần mềm quản lý BCONS</code>
          </div>

@endsection
@section('script')
    <script src="{{ asset('js/modules/dashboard.js') }}"></script>
   {{-- <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>--}}
    <!-- Resources -->

@endsection

