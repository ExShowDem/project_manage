<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dự trù thiết bị tháng</title>
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
                        <p><b>Công trường:</b> {{ $project }}</p>
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
                        <h2>DỰ TRÙ MÁY MÓC THIẾT BỊ THÁNG:_</h2>
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
                    <th width="3%">Tên thiết bị</th>
                    <th width="2%">ĐVT</th>
                    <th width="3%">Cấp/Trả</th>
                    <th width="5%">SL dự trù tổng</th>
                    <th width="5%">SL lũy kế</th>
                    <th width="5%">Số lượng</th>
                    <th width="5%">ĐỢT 1 Ngày: {{ $batches[1] }} </th>
                    <th width="5%">ĐỢT 2 Ngày: {{ $batches[2] }} </th>
                    <th width="5%">ĐỢT 3 Ngày: {{ $batches[3] }} </th>
                    <th width="5%">ĐỢT 4 Ngày: {{ $batches[4] }} </th>
                    <th width="5%">ĐỢT 5 Ngày: {{ $batches[5] }} </th>
                    <th width="5%">ĐỢT 6 Ngày: {{ $batches[6] }} </th>
                </tr>
                </thead>
                <tbody>
                @foreach($dataExport as $data)
                    <tr>
                        <td> {{ $data['stt'] }}</td>
                        <td> {{ $data['name'] }}</td>
                        <td> {{ $data['unit'] }}</td>
                        <td> {{ $data['type'] }}</td>
                        <td> {{ $data['total_quantity'] }}</td>
                        <td> {{ $data['accumulated_quantity'] }}</td>
                        <td> {{ $data['quantity'] }}</td>
                        <td> {{ $data['quantity1'] }}</td>
                        <td> {{ $data['quantity2'] }}</td>
                        <td> {{ $data['quantity3'] }}</td>
                        <td> {{ $data['quantity4'] }}</td>
                        <td> {{ $data['quantity5'] }}</td>
                        <td> {{ $data['quantity6'] }}</td>
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
                    <td width="25%"><b>CHỈ HUY TRƯỞNG</b></td>
                    <td width="25%"><b>TRƯỞNG P.THIẾT BỊ</b></td>
                    <td width="25%"><b>GIÁM SÁT THIẾT BỊ CÔNG TRƯỜNG</b></td>
                    <td width="25%"><b>NHÂN VIÊN PHÒNG THIẾT BỊ</b></td>
                </tr>
            </table>
        </div>
    </div>

</div>
</body>
</html>
