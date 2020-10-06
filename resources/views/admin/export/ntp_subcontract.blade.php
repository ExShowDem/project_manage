<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Danh Sách Nhà Thầu Phụ</title>
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
                <tr>
                    <th>Tên nhà thầu phụ</th> 
                    <td> {{ $dataExport['name'] }}</td>        
                </tr>
                <tr>
                    <th>Loại nhà thầu phụ</th> 
                    <td> {{ $dataExport['type'] }}</td>        
                </tr>
                <tr>
                    <th>Mã số nhà thầu</th> 
                    <td> {{ $dataExport['code'] }}</td>        
                </tr>
                <tr>
                    <th>Mã số thuế</th> 
                    <td> {{ $dataExport['tax_code'] }}</td>        
                </tr>
                <tr>
                    <th>Người đại diện</th> 
                    <td> {{ $dataExport['representative'] }}</td>        
                </tr>
                <tr>
                    <th>Ngân hàng</th> 
                    <td> {{ $dataExport['bank'] }}</td>        
                </tr>
                <tr>
                    <th>Số tài khoản</th> 
                    <td> {{ $dataExport['account_name'] }}</td>        
                </tr>
                <tr>
                    <th>Chủ tài khoản</th> 
                    <td> {{ $dataExport['account_owner'] }}</td>        
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
