<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dự trù thiết bị tổng</title>
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
                        <b>P/B/BP/DA:</b>
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
                        <h2>DỰ TRÙ MÁY MÓC THIẾT BỊ ĐẦU CÔNG TRƯỜNG</h2>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p><b>Địa điểm xây dựng:</b> {{ $project }}</p>
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
                    <th width="2%">Stt</th>
                    <th width="5%">Tên thiết bị</th>
                    <th width="5%">ĐVT</th>
                    <th width="5%">Kích thước/Quy cách</th>
                    <th width="5%">Khối lượng dự trù</th>
                    <th width="5%">BP.TB cung cấp</th>
                    <th width="5%">Đơn giá</th>
                    <th width="5%">Thuê ngoài</th>
                    <th width="5%">Đơn giá thuê</th>
                    <th width="5%">Khối lượng đầu tư</th>
                    <th width="5%">Đơn giá đầu tư</th>
                    <th width="5%">Ngày dự trù cấp</th>
                    <th width="5%">Ngày dự trù trả</th>
                    <th width="2%">Tổng ngày sử dụng</th>
                    <th width="5%">Thành tiền</th>
                    <th width="5%">Ghi chú</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dataExport as $data)
                    <tr>
                        <td> {{ $data['stt'] }}</td>
                        <td> {{ $data['name'] }}</td>
                        <td> {{ $data['unit'] }}</td>
                        <td> {{ $data['size'] }}</td>
                        <td> {{ $data['mass'] }}</td>
                        <td> {{ $data['mass1'] }}</td>
                        <td> {{ $data['price'] }}</td>
                        <td> {{ $data['rent'] }}</td>
                        <td> {{ $data['rent_price'] }}</td>
                        <td> {{ $data['mass2'] }}</td>
                        <td> {{ $data['estimated_unit_price'] }}</td>
                        <td> {{ $data['input_time'] }}</td>
                        <td> {{ $data['return_time'] }}</td>
                        <td> {{ $data['days_used'] }}</td>
                        <td> {{ $data['total_price'] }}</td>
                        <td> {{ $data['note'] }}</td>
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
                    <td>Ngày...Tháng...Năm... ... </td>
                    <td>Ngày...Tháng...Năm... ... </td>
                    <td>Ngày...Tháng...Năm... ... </td>
                    <td>Ngày...Tháng...Năm... ... </td>
                    <td>Ngày...Tháng...Năm... ... </td>
                </tr>
                <tr>
                    <td width="20%"><b>CHỈ HUY TRƯỞNG</b></td>
                    <td width="20%"><b>TRƯỞNG P.THIẾT BỊ</b></td>
                    <td width="20%"><b>GIÁM ĐỐC KHỐI ĐẦU THẦU</b></td>
                    <td width="20%"><b>KHỐI TÀI CHÍNH KẾ TOÁN</b></td>
                    <td width="20%"><b>BAN GIÁM ĐỐC</b></td>
                </tr>
            </table>
        </div>
    </div>

</div>
</body>
</html>
