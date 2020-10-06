<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hợp Đồng Nhà Thầu Phụ</title>
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
                    <td> {{ $dataExport['subcontractor']['name'] }}</td>        
                </tr>
                <tr>
                    <th>Hạng mục thi công</th> 
                    <td> {{ $dataExport['construction_items'] }}</td>        
                </tr>
                <tr>
                    <th>Dự án</th> 
                    <td> {{ $dataExport['project']['name'] }}</td>        
                </tr>
                <tr>
                    <th>Ngày ký hợp đồng</th> 
                    <td> {{ $dataExport['contract_sign_date'] }}</td>        
                </tr>
                <tr>
                    <th>Tiến độ</th> 
                    <td> {{ $dataExport['process'] }}</td>        
                </tr>
                <tr>
                    <th>Hình thức hợp đồng</th> 
                    <td> {{ $dataExport['contract_form'] }}</td>        
                </tr>
                <tr>
                    <th>Số hợp đồng</th> 
                    <td> {{ $dataExport['contract_number'] }}</td>        
                </tr>
                <tr>
                    <th>Giá trị phụ lục hợp đồng</th> 
                    <td> {{ $dataExport['contract_annex_value'] }}</td>        
                </tr>
                <tr>
                    <th>Giá trị hợp đồng (chưa VAT) (VND)</th> 
                    <td> {{ $dataExport['contract_value'] }}</td>        
                </tr>
                <tr>
                    <th>Giá trị hợp đồng (có VAT) (VND)</th> 
                    <td> {{ $dataExport['contract_value_vat'] }}</td>        
                </tr>
                <tr>
                    <th>Giá Trị tạm giữ bảo hành</th> 
                    <td> {{ $dataExport['value_custody_warranty'] }}</td>        
                </tr>
                <tr>
                    <th>Nội dung hợp đồng</th> 
                    <td style="max-width: 200px;word-wrap: break-word;"> {{ $dataExport['content'] }}</td>        
                </tr>
                <tr>
                    <th>Thời Gian Bảo Hành</th> 
                    <td> {{ $dataExport['date_warranty'] }}</td>        
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
