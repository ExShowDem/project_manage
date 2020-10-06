<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yêu cầu vật tư</title>
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
            <img src="{{ env('PDF_URL') }}assets/admin/images/logo-excel.jpg" alt="Bcons">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p><b>Biểu mẫu số: QT.VT.03/BM-03</b></p>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <p><b>Số: </b> {{ $requestSupply['code'] }} </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h2>PHIẾU YÊU CẦU VẬT TƯ</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p><b>Bộ phận: </b> {{ $requestSupply['project'] }} </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p><b>Nội dung đề xuất: </b></p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p><b>Khu vực thi công:</b> {{ $requestSupply['itemName'] }} </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered" id="laravel_crud">
                <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="10%">Tên vật tư</th>
                    <th width="5%">ĐVT</th>
                    <th width="10%">KL dự toán</th>
                    <th width="10%">KL lũy kế gồm đơn hàng này </th>
                    <th width="10%">KL đã nhập</th>
                    <th width="10%">KL yêu cầu đợt này</th>
                    <th width="10%">Thời gian cần cập đến công trình</th>
                    <th width="10%">Ghi chú</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dataExport as $data)
                    <tr>
                        <td> {{ $data['stt'] }}</td>
                        <td> {{ $data['name'] }}</td>
                        <td> {{ $data['unit'] }}</td>
                        <td> {{ $data['estimate_quantity'] }}</td>
                        <td> {{ $data['quantity_sum'] }}</td>
                        <td> {{ $data['input_cumulative'] }}</td>
                        <td> {{ $data['quantity'] }}</td>
                        <td> {{ $data['date_arrival_request'] }}</td>
                        <td> {{ $data['note_request'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td> <b>TỔNG CỘNG: </b> </td>
                    <td> </td>
                    <td> </td>
                    <td> {{ $sums['sumEstimateQuantity'] }}</td>
                    <td> {{ $sums['sumQuantitySum'] }}</td>
                    <td> {{ $sums['sumInputCumulative'] }}</td>
                    <td> {{ $sums['sumQuantity'] }}</td>
                    <td> </td>
                    <td> </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <p>Kính trình BGĐ xét duyệt: {{ $dataExport[0]['date_arrival_request'] }} </p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table" style="width:100%;">
                <thead>
                    <tr>
                        <th width="25%"><b>PHÊ DUYỆT CỦA BGĐ</b></th>
                        <th width="25%">KHỐI TÀI CHÍNH</th>
                        <th width="25%">KHỐI THI CÔNG</th>
                        <th width="25%">NGƯỜI ĐỀ XUẤT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i>(Ký ghi rõ họ tên)</i></td>
                        <td><i>(Ký ghi rõ họ tên)</i></td>
                        <td><i>(Ký ghi rõ họ tên)</i></td>
                        <td><i>(Ký ghi rõ họ tên)</i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
