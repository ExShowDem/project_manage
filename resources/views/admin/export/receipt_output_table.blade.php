<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Xuất kho</title>
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
                    <th width="10%">Mã Vật Tư</th>
                    <th width="10%">Tên Vật Tư</th>
                    <th width="10%">ĐVT</th>
                    <th width="10%">SL tồn kho</th>
                    <th width="10%">SL lũy kế</th>
                    <th width="10%">SL cần xuất</th>
                    <th width="10%">SL thực xuất</th>
                    <th width="10%">Hạng mục</th>
                    <th width="10%">Đơn Giá</th>
                    <th width="10%">Thành Tiền</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dataExport as $data)
                    <tr>
                        <td> {{ $data['id'] }}</td>
                        <td> {{ $data['code'] }}</td>
                        <td> {{ $data['name'] }}</td>
                        <td> {{ $data['unit'] }}</td>
                        <td> {{ $data['quantity_in_stock'] }}</td>
                        <td> {{ $data['accumulated_quantity'] }}</td>
                        <td> {{ $data['needed'] }}</td>
                        <td> {{ $data['quantity'] }}</td>
                        <td> {{ $data['item_name'] }}</td>
                        <td> {{ $data['unit_price'] }}</td>
                        <td> {{ $data['total'] }} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
