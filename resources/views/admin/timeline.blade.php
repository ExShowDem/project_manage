@extends('admin.partials.master')

@section('title')
    Timeline
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold font-green uppercase">Theo dõi</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-badge">
                                <div style="width: 130px; height: 80px; background-color: #337ab7; padding-top: 14px; text-align: center">
                                    <div style="font-size: x-large; color: white">9:10</div>
                                    <div style="color: white">11/12/2013</div>
                                </div>
                            </div>
                            <div class="timeline-body" style="margin-left: 150px !important;">
                                <div class="timeline-body-arrow"></div>
                                <div class="timeline-body-head">
                                    <div class="timeline-body-head-caption">
                                        <span class="timeline-body-alerttitle font-green-haze">Xử lý hoá đơn mua vật tư</span>
                                    </div>
                                </div>
                                <div class="timeline-body-content">
                                    <div class="font-grey-cascade margin-bottom-10">
                                        Người thực hiện:
                                        <img src="{{ asset('assets/admin/images/avatar3_small.jpg')}}">
                                        An
                                    </div>
                                    <div class="font-grey-cascade margin-bottom-10">
                                        Người theo dõi:
                                        <img src="{{ asset('assets/admin/images/avatar3_small.jpg')}}">
                                        An
                                    </div>
                                    <div class="font-grey-cascade">
                                        Trạng thái: <label for="" class="label label-success">ĐÃ DUYỆT</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
