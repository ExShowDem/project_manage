<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Phiếu đề nghị cấp thiết bị</title>
    <style>
        body {
            font-family: DejaVu Sans;
            color: #5B6060;
            font-size: 12px
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }
        .col-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            position: relative;
        }

        .table {
            background-color: transparent;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead td, .table-bordered thead th {
            padding: 5px;
        }

        .table thead th {
            vertical-align: bottom;
        }

        .table tbody td {
            padding: 5px;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="row">
        <div class="col-12">
            <table class="table">
                <tr>
                    <td rowspan="4">
                        <img style="display:block;" src="{{ env('PDF_URL') }}assets/admin/images/bcons.jpg" alt="Bcons" />
                    </td>
                    <td>
                        <b>P/B/BP/DA::</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Ngày:</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Số:</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2>ĐỀ NGHỊ CUNG CẤP / TRA MÁY MÓC THIẾT BỊ</h2>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-bordered" id="laravel_crud">
                <thead>
                    <tr>
                        <th rowspan="2">Stt</th>
                        <th rowspan="2">Nội dung</th>
                        <th colspan="8">Từ cổng trường: {{ $project }}</th>
                        <th colspan="2">Từ phòng thiết bị</th>
                        <th rowspan="2">Vượt dự trù</th>
                    </tr>
                    <tr>
                        <th>ĐVT</th>
                        <th>Quy cách</th>
                        <th>SL lũy kế</th>
                        <th>SL Dự trù tổng</th>
                        <th>SL Dự trù tháng</th>
                        <th>Số lượng</th>
                        <th>Ngày cung cấp</th>
                        <th>Ngày trả</th>
                        <th>Ngày cung cấp (P.TB)</th>
                        <th>Số lượng (P.TB)</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($dataExport as $data)
                    <tr>
                        <td> {{ $data['stt'] }}</td>
                        <td> {{ $data['name'] }}</td>
                        <td> {{ $data['unit'] }}</td>
                        <td> {{ $data['size'] }}</td>
                        <td> {{ $data['accumulated_quantity'] }}</td>
                        <td> {{ $data['total_quantity'] }}</td>
                        <td> {{ $data['monthly_estimated_quantity'] }}</td>
                        <td> {{ $data['quantity'] }}</td>
                        <td> {{ $data['supply_date'] }}</td>
                        <td> {{ $data['return_date'] }}</td>
                        <td> {{ $data['supply_date1'] }} </td>
                        <td> {{ $data['surpassed'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table" style="width:100%;">
                <tr>
                    <td width="50%"><b>MỤC ĐÍCH SỬ DỤNG:</b></td>
                    <td width="50%"><b>Ý KIẾN PHẢN HỒI:</b></td>
                </tr>
                <tr>
                    <td width="50%"><b>Ý KIẾN KHÁC:</b></td>
                    <td width="50%"><b>Ý KIẾN KHÁC:</b></td>
                </tr>
                <tr>
                    <td width="20%"><b>CHỈ HUY TRƯỞNG</b></td>
                    <td width="20%"><b>TRƯỞNG P.THIẾT BỊ</b></td>
                    <td width="20%"><b>GIÁM SÁT THIẾT BỊ CÔNG TRƯỜNG</b></td>
                    <td width="20%"><b>NHÂN VIÊN PHÒNG THIẾT BỊ</b></td>
                    <td width="20%"><b>KHÁC.....</b></td>
                </tr>
            </table>
        </div>
    </div>

</div>
</body>
</html>
