<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đề Nghị Thanh Toán Nhà Thầu Phụ</title>
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
            <img src="{{ env('PDF_URL') }}assets/admin/images/logo-excel.jpg" alt="Bcons">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h1>PHIẾU YÊU CẦU THANH TOÁN</h1>
            <p>Số: ______ BCST    ngày __ tháng __ năm ____</p>
            <p>Ngày thanh toán: {{ $paymentOrder->payment_date }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table">
                <tr>
                    <th>Họ tên:</th>
                    <td>Đặng Thị Hoàng Yến</td>        
                </tr>
                <tr>
                    <th>Đơn vị:</th>
                    <td>Khối đấu thầu</td>        
                </tr>
                <tr>
                    <th>Nội dung thanh toán:</th>
                    <td> {{ $contractSub->content }} </td>        
                </tr>
                <tr>
                    <th>Đơn vị thụ hưởng:</th>
                    <td> {{ $subcontractor->name }} </td>        
                </tr>
                <tr>
                    <th>Số tài khoản:</th>
                    <td> {{ $subcontractor->account_name.'-'.$subcontractor->bank }} </td>        
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h4>I.PHẦN ĐỀ NGHỊ THANH TOÁN:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>NỘI DUNG</th>
                        <th>Ngày HĐ</th>
                        <th>Số HĐ</th>
                        <th>Số Tiền</th>
                        <th>Hạch Toán</th>
                    </tr>                    
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ $contractSub->content }}</td>
                        <td>{{ $contractSub->contract_sign_date }}</td>
                        <td>{{ $contractSub->contract_number }}</td>
                        <td>{{ $paymentOrder->settlement_value }}</td>
                        <td>{{ $paymentOrder->project->name }}</td>
                    </tr>   
                    <tr>
                        <td colspan="4"><b>CỘNG</b></td>
                        <td><b>{{ $paymentOrder->settlement_value }}</b></td>
                        <td> </td>
                    </tr>                    
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h4>Hồ sơ gốc hợp lệ đính kèm:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên</th>
                        <th>Ngày hiệu lực</th>
                        <th>SL</th>
                        <th>Ghi chú</th>
                    </tr>                    
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td> </td>
                        <td>Hồ sơ thanh toán</td>
                        <td>1</td>
                        <td> </td>
                    </tr>                    
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h4>II.TÌNH HÌNH THANH TOÁN(đính kèm bản photo chứng từ thanh toán):</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Số HĐ / Đợt thanh toán</th>
                        <th>Giá trị</th>
                        <th>Chứng từ</th>
                        <th>Ghi chú</th>
                    </tr>                    
                </thead>
                <tbody>
                    <tr>
                        <td> {{ $dataExport[0]['stt'] }} </td>
                        <td> {{ $dataExport[0]['content'] }} </td>
                        <td> {{ $dataExport[0]['value'] }} </td>
                        <td rowspan="2"> {{ $dataExport[0]['code'] }} </td>
                        <td rowspan="3"> </td>
                    </tr>   
                    <tr>
                        <td> {{ $dataExport[1]['stt'] }} </td>
                        <td> {{ $dataExport[1]['content'] }} </td>
                        <td> {{ $dataExport[1]['value'] }} </td>
                    </tr>   
                    <tr>
                        <td> {{ $dataExport[2]['stt'] }} </td>
                        <td> {{ $dataExport[2]['content'] }} </td>
                        <td> {{ $dataExport[2]['value'] }} </td>
                        <td> {{ $dataExport[2]['code'] }} </td>
                    </tr> 
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table" style="width:100%;">
                <tr>
                    <td width="50%">Trưởng đơn vị/CHT Công trình</td>
                    <td width="50%">Người đề nghị</td>
                </tr>
                <tr>
                    <td width="50%">Cái Minh Giác</td>
                    <td width="50%">Đặng Thị Hoàng Yến</td>
                </tr>
                <tr>
                    <td width="100%">PHẦN KIỂM TRA, XEM XÉT CỦA CÔNG TY</td>
                </tr>
                <tr>
                    <td width="33%">Tổng giám đốc</td>
                    <td width="33%">Kế toán trưởng</td>
                    <td width="33%">Kế toán viên</td>
                </tr>
            </table>
        </div>
    </div>

</div>
</body>
</html>
