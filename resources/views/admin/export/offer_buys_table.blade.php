<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đề xuất vật tư</title>
    <style>
        body {
            font-family: DejaVu Sans;
            padding: 39px 41px 39px 42px;
            color: #5B6060;
            font-size: 12px
        }

        .container {
            width: 500px;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
            position: relative;
            width: 100%;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead td, .table-bordered thead th {
            border-bottom-width: 2px;
            padding: 15px;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody td {
            padding: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered" id="laravel_crud">
                <thead>
                <tr>
                    <th width="10%">ID</th>
                    <th width="10%">Mã Vật Tư</th>
                    <th width="30%">Tên Vật Tư</th>
                    <th width="10%">ĐVT</th>
                    <th width="10%">Số Lượng</th>
                    <th width="10%">Đơn Giá</th>
                    <th width="10%">Ngày Cần Về</th>
                    <th width="15%">Thành Tiền</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dataExport as $data)
                    <tr>
                        <td> {{ $data['id'] }}</td>
                        <td> {{ $data['code'] }}</td>
                        <td> {{ $data['name'] }}</td>
                        <td> {{ $data['unit']['name'] }}</td>
                        <td> {{ $data['quantity'] }}</td>
                        <td> {{ $data['unit_price'] }}</td>
                        <td> {{ $data['date_arrival'] }}</td>
                        <td>
                            <div class="pull-right">
                                {{ $data['quantity'] * $data['unit_price'] }}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
