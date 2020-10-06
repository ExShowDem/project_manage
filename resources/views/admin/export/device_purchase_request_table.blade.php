<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yêu cầu mua mới thiết bị</title>
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
        <div class="col-6">
            <img src="{{ env('PDF_URL') }}assets/admin/images/bcons.jpg" alt="Bcons">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered" id="laravel_crud">
                <thead>
                <tr>
                    <th width="10%">ID</th>
                    <th width="15%">Mã thiết bị</th>
                    <th width="15%">Tên thiết bị</th>
                    <th width="10%">ĐVT</th>
                    <th width="10%">SL Lũy kế nhập</th>
                    <th width="10%">SL Dự trù</th>
                    <th width="10%">Số lượng</th>
                    <th width="10%">Ngày cần về</th>
                    <th width="15%">Ghi chú</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dataExport as $data)
                    <tr>
                        <td> {{ $data['id'] }}</td>
                        <td> {{ $data['code'] }}</td>
                        <td> {{ $data['name'] }}</td>
                        <td> {{ $data['unit'] }}</td>
                        <td> {{ $data['input_cumulative'] }}</td>
                        <td> {{ $data['estimated_quantity'] }}</td>
                        <td> {{ $data['quantity'] }}</td>
                        <td> {{ $data['required_return_date'] }}</td>
                        <td> {{ $data['note'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
