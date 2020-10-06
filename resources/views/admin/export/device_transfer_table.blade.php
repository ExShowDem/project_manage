<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kế hoạch điều chuyển thiết bị</title>
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
                        <h2>KẾ HOẠCH ĐIỀU CHUYỂN MÁY MÓC THIẾT BỊ</h2>
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
                        <th rowspan="2">ĐVT</th>
                        <th rowspan="2">SL đề nghị</th>
                        <th rowspan="2">Số lượng</th>
                        <th rowspan="2">Loại xe</th>
                        <th rowspan="2">Số xe</th>
                        <th rowspan="2">Đơn vị vận chuyển</th>
                        <th colspan="3">Kho BCONS</th>
                        <th rowspan="2">Công trình</th>
                        <th colspan="2">Thời gian dự kiến</th>
                        <th rowspan="2">Công trình</th>
                    </tr>
                    <tr>
                        <th>Xuất</th>
                        <th>Nhập</th>
                        <th>Luân chuyển</th>
                        <th>Nơi đi</th>
                        <th>Nơi đến</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($dataExport as $data)
                    <tr>
                        <td> {{ $data['stt'] }}</td>
                        <td> {{ $data['name'] }}</td>
                        <td> {{ $data['unit'] }}</td>
                        <td> {{ $data['issued_quantity'] }}</td>
                        <td> {{ $data['quantity'] }}</td>
                        <td> {{ $data['carrier_type'] }}</td>
                        <td> {{ $data['carrier_number'] }}</td>
                        <td> {{ $data['transfer_unit'] }}</td>
                        <td> {{ $data['in'] }}</td>
                        <td> {{ $data['ex'] }}</td>
                        <td> {{ $data['trans'] }}</td>
                        <td> {{ $data['from_project'] }}</td>
                        <td> {{ $data['sent'] }}</td>
                        <td> {{ $data['arrived'] }}</td>
                        <td> {{ $data['to_project'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <p>
                <b>Chú ý:</b>
            </p>
            <ol>
                <li>
                    <b>Thời gian xe vận chuyển vào khu vực nội thành như sau:</b>

                    <ul>
                        <li>
                            <font>Xe thùng 6m, 4.5m, 3m, ba gác: <b>Sau 8h00 đến trước 16h00, sau 20h00 đến trước 6h00</b></font>
                        </li>
                        <li>
                            <font>Xe cẩu thùng, đầu kéo các loại xe tải nặng khác: <b>Sau 0h00 đến trước 6h00</b></font>
                        </li>
                        <li>
                            <font>BCH công trường hoàn tất việc xuất nhập để xe di chuyển ra khỏi khu vực nội thành trước thời gian cấm <br><b>(trước 6h00 và 16h00)</b></font>
                        </li>
                    </ul>
                </li>

                <li>
                    <b><font>BCH công trình thực hiện sắp xếp mặt bằng, phân loại vật tư thiết bị trước khi gửi yêu cầu cấp/trả.</font></b>
                </li>
                <li>
                    <b><font>Tính thêm chi phí nếu BCH công trình không thực hiện công tác xuất/ nhập thiết bị sau khi xe vận chuyển đến công trình được 01 tiếng đồng hồ.</font></b>

                    <ul>
                        <li>
                            <font>Thời gian chờ:<b> 1h00 (phạt 1/3 chi phí cước xe, 2h00 (phạt 1/2 chi phí cước xe), sau 2h00 rút xe khỏi công trình (phạt 1/2 chi phí cước xe)</b></font>
                        </li>
                    </ul>
                </li>
            </ol>

            <p style="text-align: right;">
                <div style="text-align: right;"><span style="text-align: left; white-space: pre;">Ngày tháng năm __</span></div>
                <div style="text-align: right;"><b style="text-align: left; white-space: pre;">Trưởng Phòng Thiết Bị</b></div>
            </p>

        </div>
    </div>

</div>
</body>
</html>
