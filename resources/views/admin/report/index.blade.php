@extends('admin.partials.master')

@section('title')
    Báo cáo
@endsection
<style>




    select {
        background-color: #f9f9f9;
        color: #8c8c8c;
        line-height: 1.3em;
        -webkit-appearance: none;
        outline: none;
        resize: none;
        padding: 0.45em 4.14em;
        border:none;

        transition: all ease .3s;
        border-radius: 0;
        font-family: inherit;
        font-size: 1em;
        margin: 0;
        vertical-align: baseline;
    }
    [aria-labelledby="id-66-title"]{
        display: none;
    }
    .sc_property_search_content{ text-align:center;

        margin: auto;
        margin-bottom: 40px;
        background: #fff;
        border: 1px solid #e3e4e9;
    }
    .sc_property_search_content input, .sc_property_search_content .select2-selection{
        border:none !important;
    }
    .media-body p{
        margin:0px;
    }

    .knob{
        float: left;
    }
    aside {
        border-top-color: #efefef;
        margin-bottom: 2em;
    }
    .widget_bg {
        background-color: #fff;
        position: relative;
        width: 100%;
        overflow: hidden;
        -moz-box-shadow: 0 3px 4.7px 0.3px rgba(0, 0, 0, 0.24);
        box-shadow: 0 3px 4.7px 0.3px rgba(0, 0, 0, 0.24);
        background: #fff;
    }
    .widget_bg>h5 {
        padding: 0.7em 2.188em 0.7em;
        margin: 0;
        border-bottom: 1px solid #efefef;
        color: #343434;
    }
    .overflow-hidden>h5{
        margin:0px;
    }
    .media>.align-self-center>.tag-color{
        margin-top:8px;
    }
    .flickr_images {
        overflow: hidden;
        padding: 15px;
    }
    .page-content{
        background-color: #f9f9f9 !important;
    }
    .sc_icon.icon5e {
        border-color: #fff;
        background-color: #fff;
        color: #46b6d8;
        font-size: 1.4em;
        width: 3.4em;
        height: 3.4em;
        line-height: 3.4em;
        padding: 0;
        -webkit-box-shadow: 0px 3px 4.7px 0.3px rgba(0, 0, 0, 0.24);
        -moz-box-shadow: 0px 3px 4.7px 0.3px rgba(0, 0, 0, 0.24);
        box-shadow: 0px 3px 4.7px 0.3px rgba(0, 0, 0, 0.24);
        margin-right: 1em;
        float: left;
        font-weight:bold;
    }
    .sc_icon_shape_square, .sc_icon_shape_round {
        display: inline-block;
        padding: 4px;
        text-align: center;
        width: 1.2em;
        height: 1.2em;
        line-height: 1.2em;
        border: 0.05em solid #f4f7f9;
        border-radius: 50% !important;

    }
    h5{
        font-family: "PT Serif", serif;
        font-size: 1.2em !important; ;
        line-height: normal;
        font-weight: 400 !important;
        margin-top: 0em;
        color:#343434;
    }
    .hung{
        margin:0px;
    }
    .tag {

        font-size: 11px;
        border-radius: 2px !important;
        margin: 2px 4px 0 0;
        padding: 3px 1px;
    }
    .tag-color {
        width: 8px;
        height: 8px;
        margin-right: 8px;
        border-radius: 50% !important;
    }
    .margin-bottom{
        margin-bottom:100px;
    }
    .simplebar-wrapper {
        overflow: hidden;
        width: inherit;
        height: inherit;
        max-width: inherit;
        max-height: inherit;
    }
    .simplebar-height-auto-observer-wrapper {
        -webkit-box-sizing: inherit!important;
        box-sizing: inherit!important;
        height: 100%;
        width: 100%;
        max-width: 1px;
        position: relative;
        float: left;
        max-height: 1px;
        overflow: hidden;
        z-index: -1;
        padding: 0;
        margin: 0;
        pointer-events: none;
        -webkit-box-flex: inherit;
        -ms-flex-positive: inherit;
        flex-grow: inherit;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        -ms-flex-preferred-size: 0;
        flex-basis: 0;
    }
    .simplebar-height-auto-observer {
        -webkit-box-sizing: inherit;
        box-sizing: inherit;
        display: block;
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
        height: 1000%;
        width: 1000%;
        min-height: 1px;
        min-width: 1px;
        overflow: hidden;
        pointer-events: none;
        z-index: -1;
    }
    .simplebar-mask {
        direction: inherit;
        position: absolute;
        overflow: hidden;
        padding: 0;
        margin: 0;
        left: 0;
        top: 0;
        bottom: 0;
        right: 0;
        width: auto!important;
        height: auto!important;
        z-index: 0;
    }
    .simplebar-offset {
        direction: inherit!important;
        -webkit-box-sizing: inherit!important;
        box-sizing: inherit!important;
        resize: none!important;
        position: absolute;
        top: 0;
        left: 0!important;
        bottom: 0;
        right: 0!important;
        padding: 0;
        margin: 0;
        -webkit-overflow-scrolling: touch;
    }
    .simplebar-content-wrapper {
        direction: inherit;
        -webkit-box-sizing: border-box!important;
        box-sizing: border-box!important;
        position: relative;
        display: block;
        height: 100%;
        width: auto;
        visibility: visible;
        overflow: auto;
        max-width: 100%;
        max-height: 100%;
        scrollbar-width: none;
        padding: 0!important;
        height: auto;
        overflow: hidden scroll;
    }
    .chat-list li a {
        display: block;
        padding: 14px 16px;
        color: #74788d;
        -webkit-transition: all .4s;
        transition: all .4s;
        border-bottom: 1px solid #eff2f7;
        border-radius: 4px;
    }
    .media {
        display: flex;
        -webkit-box-align: start;
        align-items: flex-start;
    }
    .mr-3, .mx-3 {
        margin-right: 1rem!important;
    }
    .font-size-10 {
        font-size: 10px!important;
    }
    .text-success {
        color: #34c38f!important;
    }
    .mdi-circle::before {
        content: "\F0765";
    }
    .mdi-set, .mdi:before {
        display: inline-block;
        font: normal normal normal 24px/1 "Material Design Icons";
        font-size: inherit;
        text-rendering: auto;
        line-height: inherit;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    .avatar-xs {
        height: 2rem;
        width: 2rem;
    }
    .rounded-circle {
        border-radius: 50%!important;
    }
    img {
        vertical-align: middle;
        border-style: none;
    }
    .overflow-hidden {
        overflow: hidden!important;
    }
    .text-truncate {
        font-size: 12px!important;
    }
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .font-size-11{
        font-size:11px;
    }
    .mb-1, .my-1 {
        margin-bottom: .25rem!important;
    }
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .mb-0, .my-0 {
        margin-bottom: 0!important;
    }
    .flickr_images a:hover{
        text-decoration: none;
    }
    .margin-top{
        margin-top:10px;}
    .canvasjs-chart-credit{
        display: none;
    }
    #chartdiv {
        width: 100%;
        height: 240px;
    }

</style>


@section('content')

    <div class="row">
        <div class="col-md-12 margin-bottom margin-top">
            @if(!$currentProjectName)
            <h3 style="margin: 0px; text-align: center;margin-bottom: 30px;">Báo cáo thống kê</h3>
                @else
                <h3 style="margin: 0px; text-align: center;margin-bottom: 30px;">Báo cáo thống kê - {{ $currentProjectName }}</h3>
            @endif
            <report-list></report-list>




            <div class="row margin-top">
                <div class="col-md-8">
                    <aside id="bestdeals_widget_flickr-2" class="widget_number_5 widget widget_flickr">
                        <div class="widget_number_5 widget_bg"><h5 class="widget_title_">Hợp đồng nhà thầu phụ</h5>
                            <div class="flickr_images">
                                {{--
                                                                <object id="donutChart" role="svidget" data="{{ asset('js/donutChart.svg') }}" type="image/svg+xml" width="300" height="300">

                                                                </object>--}}
                                <?php

                                /* $dataPoints = array(
                                     array("label"=>"Oxygen", "symbol" => "O","y"=>46.6),
                                     array("label"=>"Silicon", "symbol" => "Si","y"=>27.7),
                                     array("label"=>"Aluminium", "symbol" => "Al","y"=>13.9),
                                     array("label"=>"Iron", "symbol" => "Fe","y"=>5),
                                     array("label"=>"Calcium", "symbol" => "Ca","y"=>3.6),

                                 );*/

                                $dataPoints = array();
                                foreach ($statistic['contract_subcontractor'] as $k=>$item) {
                                    if($item->status == 0){
                                        $dataPoints[$k]['country'] = 'Đang tạo';
                                        $dataPoints[$k]['litres'] = ($item->total*100)/$statistic['totalConSub'];
                                    }
                                    if($item->status == 1){
                                        $dataPoints[$k]['country'] = 'Đã tạo';
                                        $dataPoints[$k]['litres'] = ($item->total*100)/$statistic['totalConSub'];
                                    }
                                    if($item->status == 2){
                                        $dataPoints[$k]['country'] = 'Chuyển tiếp';
                                        $dataPoints[$k]['litres'] = ($item->total*100)/$statistic['totalConSub'];
                                    }
                                    if($item->status == 3){
                                        $dataPoints[$k]['country'] = 'Đã duyệt';
                                        $dataPoints[$k]['litres'] = ($item->total*100)/$statistic['totalConSub'];
                                    }
                                    if($item->status == 4){
                                        $dataPoints[$k]['country'] = 'Đã từ chối';
                                        $dataPoints[$k]['litres'] = ($item->total*100)/$statistic['totalConSub'];
                                    }
                                    if($item->status == 5){
                                        $dataPoints[$k]['country'] = 'Đã hủy';
                                        $dataPoints[$k]['litres'] = ($item->total*100)/$statistic['totalConSub'];
                                    }

                                }

                                //dd($object);
                                ?>

                                {{-- <div id="chartContainer" style="height: 370px; width: 100%;"></div>--}}
                                <div id="chartdiv"></div>


                            </div>

                        </div></aside>
                </div>
                <div class="col-md-4">
                    <aside id="bestdeals_widget_flickr-2" class="widget_number_5 widget widget_flickr"><div class="widget_number_5 widget_bg"><h5 class="widget_title_">Trạng thái công việc</h5>
                            <div class="flickr_images">
                                <div style="float:left;">
                                    <input class="knob" data-width="100" data-height="100" data-thickness=".2" data-fgColor="#ff0000" data-min="0" data-displayPrevious=true value="{{$statistic['percentTask']}}">
                                </div>

                                <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
                                    <div class="tag-color" style="background-color:#ff0000;"></div>
                                    <div class="tag-label"><span style ="color:#ff0000;font-size: 28px;">{{$statistic['date_have_due']}}</span> quá hạn</div></div>
                                <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">

                                    <div class="tag-label">Trên <span style="color:#cd1060; font-size: 16px;">{{$statistic['work_in_progress']}}</span> công việc đang thực hiện</div></div>

                            </div>
                            <div class="flickr_images">
                                <div style="float:left;">
                                    <input class="knob" data-width="100" data-height="100" data-thickness=".2" data-fgColor="#6BBF19" data-min="0" data-displayPrevious=true value="{{$statistic['percent_task_done']}}">
                                </div>

                                <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
                                    <div class="tag-color" style="background-color:#6BBF19;"></div>
                                    <div class="tag-label"><span style ="color:#6BBF19;font-size: 28px;">{{$statistic['count_work_done']}}</span> đã hoàn thành</div></div>
                                <div fxlayout="row" fxlayoutalign="start center" class="tag ng-star-inserted" style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center flex-start; align-items: center;">
                                    <div class="tag-label">Trên <span style="color:#cd1060; font-size: 16px;">{{$statistic['work_in_progress']}}</span> công việc đang thực hiện</div></div>

                            </div>

                        </div></aside>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <aside id="bestdeals_widget_flickr-2" class="widget_number_5 widget widget_flickr">
                        <div class="widget_number_5 widget_bg"><h5 class="widget_title_">Thành viên xuất sắc (Trên 75%)</h5>
                            <div class="flickr_images">



                                <ul class="list-unstyled chat-list" data-simplebar="init" style="max-height: 450px;">
                                    <?php

                                    foreach ($userExcelent as $user) {
                                    list($taskUsers,$taskUsersDone,$percentDone,$userDue) = get_task_by_user($user->id,$statistic['id_project']);
                                    if($percentDone >= 75){
                                    ?>

                                    <li>
                                        <a href="#">
                                            <div class="media" >
                                                <div class="align-self-center mr-3">
                                                    <div class="tag-color" style="background-color:#9be012;"></div>
                                                </div>
                                                <div class="align-self-center mr-3">
                                                    <img src="<?php echo ($user->image != null)? e(asset("storage/images/avatars/".$user->image)): e(asset('assets/admin/images/avatar-2.jpg')); ?>" class="rounded-circle avatar-xs" alt="">
                                                </div>

                                                <div class="media-body overflow-hidden " style="width:70%;">
                                                    <h5 class="text-truncate font-size-14 mb-1" style="color:rgb(107, 191, 25)"><?php echo $user->name;?></h5>
                                                    <p class="text-truncate mb-0"><?php echo $taskUsersDone.'/'.$taskUsers;?> công việc đã hoàn thành</p>
                                                </div>
                                                <div class="font-size-11" style="width:20%;">
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $percentDone;?>"
                                                             aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentDone;?>%">
                                                            <?php echo $percentDone;?>%
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <?php }}?>
                                </ul>


                            </div>

                        </div></aside>
                </div>

                <div class="col-md-4">
                    <aside id="bestdeals_widget_flickr-2" class="widget_number_5 widget widget_flickr">
                        <div class="widget_number_5 widget_bg"><h5 class="widget_title_">Còn nhiều việc nhất</h5>
                            <div class="flickr_images">



                                <ul class="list-unstyled chat-list" data-simplebar="init" style="max-height: 450px;">
                                    <?php

                                    foreach ($userExcelent as $user) {
                                    list($taskUsers,$taskUsersDone,$percentDone,$userDue) = get_task_by_user($user->id,$statistic['id_project']);
                                    if($taskUsersDone < $taskUsers){
                                    ?>

                                    <li>
                                        <a href="#">
                                            <div class="media" >
                                                <div class="align-self-center mr-3">
                                                    <div class="tag-color" style="background-color:#009999;"></div>
                                                </div>
                                                <div class="align-self-center mr-3">
                                                    <img src="<?php echo ($user->image != null)? e(asset("storage/images/avatars/".$user->image)): e(asset('assets/admin/images/avatar-2.jpg')); ?>" class="rounded-circle avatar-xs" alt="">
                                                </div>

                                                <div class="media-body overflow-hidden " style="width:70%;">
                                                    <h5 class="text-truncate font-size-14 mb-1" style="color:#009999"><?php echo $user->name;?></h5>
                                                    <p class="text-truncate mb-0"><?php echo $taskUsersDone.'/'.$taskUsers;?> công việc đang thực hiện</p>
                                                </div>

                                            </div>
                                        </a>
                                    </li>

                                    <?php }}?>
                                </ul>


                            </div>

                        </div></aside>
                </div>

                <div class="col-md-4">
                    <aside id="bestdeals_widget_flickr-2" class="widget_number_5 widget widget_flickr">
                        <div class="widget_number_5 widget_bg"><h5 class="widget_title_">Thành viên có công việc quá hạn</h5>
                            <div class="flickr_images">



                                <ul class="list-unstyled chat-list" data-simplebar="init" style="max-height: 450px;">
                                    <?php

                                    foreach ($userExcelent as $user) {
                                    list($taskUsers,$taskUsersDone,$percentDone,$userDue) = get_task_by_user($user->id,$statistic['id_project']);
                                    if($userDue > 0){
                                    ?>

                                    <li>
                                        <a href="#">
                                            <div class="media" >
                                                <div class="align-self-center mr-3">
                                                    <div class="tag-color" style="background-color:#ff0000;"></div>
                                                </div>
                                                <div class="align-self-center mr-3">
                                                    <img src="<?php echo ($user->image != null)? e(asset("storage/images/avatars/".$user->image)): e(asset('assets/admin/images/avatar-2.jpg')); ?>" class="rounded-circle avatar-xs" alt="">
                                                </div>

                                                <div class="media-body overflow-hidden " style="width:70%;">
                                                    <h5 class="text-truncate font-size-14 mb-1" style="color:#ff0000"><?php echo $user->name;?></h5>
                                                    <p class="text-truncate mb-0"><?php echo $userDue;?> công việc đã quá hạn</p>
                                                </div>

                                            </div>
                                        </a>
                                    </li>

                                    <?php }}?>
                                </ul>


                            </div>

                        </div></aside>
                </div>


            </div>

            <div class="row">
                <div class="col-md-4">
                    <aside id="bestdeals_widget_flickr-2" class="widget_number_5 widget widget_flickr">
                        <div class="widget_number_5 widget_bg"><h5 class="widget_title_">Những nhà thầu phụ có giá trị hợp đồng cao nhất </h5>
                            <div class="flickr_images">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tên nhà thầu phụ</th>
                                            <th>Giá trị hợp đồng</th>
                                            <th>Giá trị</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($statistic['top_subcontractors'] as $key => $subcontractor)
                                        <tr>
                                            <td>{{$subcontractor->name}}</td>
                                            <td>{{$subcontractor->sum_contract_value}}</td>
                                            <td>{{$subcontractor->sum_settlement_value}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>

            <div class="clearfix"></div>




        </div>
    </div>

@endsection



@section('script')
    <script src="{{ asset('js/modules/report.js') }}"></script>
    {{-- <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>--}}
    <!-- Resources -->
    <script src="https://www.amcharts.com/lib/4/core.js"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/kelly.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://themesbrand.com/veltrix/layouts/vertical/assets/libs/jquery-knob/jquery.knob.min.js"></script>


    <script>


        am4core.ready(function() {

// Themes begin
            am4core.useTheme(am4themes_kelly);
            am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
            var chart = am4core.create("chartdiv", am4charts.PieChart);

// Add data
            chart.data = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;

// Set inner radius
            chart.innerRadius = am4core.percent(50);

// Add and configure Series
            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "litres";
            pieSeries.dataFields.category = "country";
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 2;
            pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
            pieSeries.hiddenState.properties.opacity = 1;
            pieSeries.hiddenState.properties.endAngle = -90;
            pieSeries.hiddenState.properties.startAngle = -90;

        }); // end am4core.ready()

        // doughnut chart

        /*window.onload = function() {

         CanvasJS.addColorSet("greenShades",
         [//colorSet Array

         "#6BBF19",
         "#009999",
         "#ff7400",
         "#ff0000",
         "#ffaa00",
         "#1240ab"
         ]);

         var chart = new CanvasJS.Chart("chartContainer", {
         theme: "light1",
         animationEnabled: true,
         colorSet:  "greenShades",
         title: {
         text: ""
         },
         data: [{
         type: "doughnut",
         indexLabel: "{symbol} - {y}",
         yValueFormatString: "#,##0.0\"%\"",
         showInLegend: false,
         legendText: "{label} : {y}",
         dataPoints: <?php //echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
         }]
         });
         chart.render();

         }*/
        //knop chart
        $(function($) {

            $(".knob").knob({
                readOnly: true,
                change : function (value) {
                    //console.log("change : " + value);
                },
                format : function (value) {
                    return value + '%';
                },
                release : function (value) {
                    //console.log(this.$.attr('value'));
                    console.log("release : " + value);
                },
                cancel : function () {
                    console.log("cancel : ", this);
                },
                /*format : function (value) {
                 return value + '%';
                 },*/
                draw : function () {

                    // "tron" case
                    if(this.$.data('skin') == 'tron') {

                        this.cursorExt = 0.3;

                        var a = this.arc(this.cv)  // Arc
                            , pa                   // Previous arc
                            , r = 1;

                        this.g.lineWidth = this.lineWidth;

                        if (this.o.displayPrevious) {
                            pa = this.arc(this.v);
                            this.g.beginPath();
                            this.g.strokeStyle = this.pColor;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, pa.s, pa.e, pa.d);
                            this.g.stroke();
                        }

                        this.g.beginPath();
                        this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
                        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, a.s, a.e, a.d);
                        this.g.stroke();

                        this.g.lineWidth = 2;
                        this.g.beginPath();
                        this.g.strokeStyle = this.o.fgColor;
                        this.g.arc( this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                        this.g.stroke();

                        return false;
                    }
                }
            });

            // Example of infinite knob, iPod click wheel
            var v, up=0,down=0,i=0
                ,$idir = $("div.idir")
                ,$ival = $("div.ival")
                ,incr = function() { i++; $idir.show().html("+").fadeOut(); $ival.html(i); }
                ,decr = function() { i--; $idir.show().html("-").fadeOut(); $ival.html(i); };
            $("input.infinite").knob(
                {
                    min : 0
                    , max : 20
                    , stopper : false
                    , change : function () {
                    if(v > this.cv){
                        if(up){
                            decr();
                            up=0;
                        }else{up=1;down=0;}
                    } else {
                        if(v < this.cv){
                            if(down){
                                incr();
                                down=0;
                            }else{down=1;up=0;}
                        }
                    }
                    v = this.cv;
                }
                });
        });








    </script>

    @endsection

