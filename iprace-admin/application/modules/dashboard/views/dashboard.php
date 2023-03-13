<script src="<?= base_url() ?>assets/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?= base_url() ?>assets/js/pages/dashboard.js"></script>
<script src="<?= base_url() ?>assets/js/highcharts.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.flot.min.js"></script>
<section id="content">
    <div class="wrapper">
        <div class="crumb">
        </div>
        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-dashboard"></i> Dashboard</h1>
            </div>


            <div class="row">
                <!------------- MEMBER STATISTIC START  ------------------>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="icon"><i class="icon20 i-stats"></i></div>
                            <h4>Member Statistic </h4>
                            <a href="#" class="minimize"></a>
                        </div>
                        <!-- End .panel-heading -->

                        <div class="panel-body">
                            <div class="campaign-stats center" style="border-top:none;">
                                <div class="items">
                                    <div class="percentage" data-percent="100"><span>100</span></div>
                                    <div class="txt">Total <?php echo $total_member; ?></div>
                                </div>
                                <div class="items">
                                    <div class="percentage-green" data-percent="101">
                                        <span>101</span>%
                                    </div>
                                    <div class="txt">Active 101</div>
                                </div>

                                <div class="items">
                                    <div class="percentage" data-percent="102">
                                        <span>102</span>%
                                    </div>
                                    <div class="txt">Suspended 102</div>
                                </div>
                                <div class="items">
                                    <div class="percentage" data-percent="103">
                                        <span>103</span>%
                                    </div>
                                    <div class="txt">Inactive 103</div>
                                </div>


                            </div>

                            <div class="clearfix"></div>

                        </div>
                        <!-- End .panel-body -->
                    </div>
                    <!-- End .widget -->
                </div>
                <!------- MEMBER STATISTIC END ---------------------------->

                <!-------------PAID / FREE MEMBERSHIP STATISTIC START  ------------------>

                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="icon"><i class="icon20 i-stats"></i></div>
                            <h4>Membership Statistic </h4>
                            <a href="#" class="minimize"></a>
                        </div>
                        <!-- End .panel-heading -->

                        <div class="panel-body">
                            <div class="campaign-stats center" style="border-top:none;">

                                <div class="items">
                                    <div class="percentage-green" data-percent="104">
                                        <span>104</span>%
                                    </div>
                                    <div class="txt">Free 104</div>
                                </div>

                                <div class="items">
                                    <div class="percentage" data-percent="105">
                                        <span>105</span>%
                                    </div>
                                    <div class="txt">Silver 105</div>
                                </div>
                                <div class="items">
                                    <div class="percentage" data-percent="106">
                                        <span>106</span>%
                                    </div>
                                    <div class="txt">Gold 106</div>
                                </div>
                                <div class="items">
                                    <div class="percentage" data-percent="107">
                                        <span>107</span>%
                                    </div>
                                    <div class="txt">Platinum 107</div>
                                </div>


                            </div>

                            <div class="clearfix"></div>

                        </div>
                        <!-- End .panel-body -->
                    </div>
                    <!-- End .widget -->
                </div>
                <!-------------PAID / FREE MEMBERSHIP STATISTIC END  ------------------>
            </div>

            <div class="row">
                <!--------------------------  REGISTRATION STATISTIC START ------------------------------>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="icon"><i class="icon20 i-cube"></i></div>
                            <h4>Registration Statistic</h4>
                            <a href="#" class="minimize"></a>
                        </div>
                        <!-- End .panel-heading -->

                        <div class="panel-body noPadding">
                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active">
                                    <a href="#home1" data-toggle="tab"><?= date('F Y', strtotime('-2 month')) ?></a>
                                </li>
                                <li>
                                    <a href="#profile1" data-toggle="tab"><?= date('F Y', strtotime('last month')) ?></a>
                                </li>
                                <li><a href="#profile2" data-toggle="tab"><?= date('F Y') ?></a></li>

                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home1">
                                    <div class="">
                                        <div class="stats-buttons">
                                            <ul class="list-unstyled">
                                                <li>
                                                    <a href="#" class="clearfix">
                                                        <span class="icon green"><i class="icon24 i-file-8"></i></span>
                                                        <span class="number"><?php echo 0; ?></span>
                                                        <span class="txt">Total User</span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#" class="clearfix">
                                                        <span class="icon"><i class="icon24 i-coin"></i></span>
                                                        <span class="number"><?php echo 0; ?></span>
                                                        <span class="txt">Paid User</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="clearfix">
                                                        <span class="icon gray"><i class="icon24 i-coin"></i></span>
                                                        <span class="number"><?php echo 0; ?></span>
                                                        <span class="txt">Free User</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- End .stats-buttons  -->
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile1">
                                    <div class="">
                                        <div class="stats-buttons">
                                            <ul class="list-unstyled">
                                                <li>
                                                    <a href="#" class="clearfix">
                                                        <span class="icon green"><i class="icon24 i-file-8"></i></span>
                                                        <span class="number"><?php echo 0; ?></span>
                                                        <span class="txt">Total Users</span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#" class="clearfix">
                                                        <span class="icon"><i class="icon24 i-coin"></i></span>
                                                        <span class="number"><?php echo 0; ?></span>
                                                        <span class="txt">Paid Users</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="clearfix">
                                                        <span class="icon gray"><i class="icon24 i-coin"></i></span>
                                                        <span class="number"><?php echo 0; ?></span>
                                                        <span class="txt">Free Users</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- End .stats-buttons  -->
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="profile2">
                                    <div class="">
                                        <div class="stats-buttons">
                                            <ul class="list-unstyled">
                                                <li>
                                                    <a href="#" class="clearfix">
                                                        <span class="icon green"><i class="icon24 i-file-8"></i></span>
                                                        <span class="number"><?php echo 0; ?></span>
                                                        <span class="txt">Total Users</span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#" class="clearfix">
                                                        <span class="icon"><i class="icon24 i-coin"></i></span>
                                                        <span class="number"><?php echo 0; ?></span>
                                                        <span class="txt">Paid Users</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="clearfix">
                                                        <span class="icon gray"><i class="icon24 i-coin"></i></span>
                                                        <span class="number"><?php echo 0; ?></span>
                                                        <span class="txt">Free Users</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- End .stats-buttons  -->
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End .panel-body -->
                    </div>
                    <!-- End .widget -->
                </div>
                <!---------------------------  REGISTRATION STATISTIC END ----------------------------->

            </div>


            <!-------- MEMBER GRAPH START ------->


            <div class="row">
                <div class="col-lg-8" style="width: 98.667%;">
                    <!-- End .widget -->
                    <div class="panel panel-default">


                        <div class="panel-heading">

                            <div class="icon"><i class="icon20 file"></i></div>

                            <h4>Member Graph Chart</h4>
                            <a class="minimize" href="#"></a>

                        </div>
                        <!-- End .panel-heading -->
                        <div class="panel-body">

                            <div class="campaign-stats center" style="border-top:none;">
                                <style>
                                    #tooltip {
                                        background: #000;
                                        color: #fff;
                                        padding: 5px 10px;
                                        font-family: inherit;
                                        font-size: 9px;
                                    }

                                    .tooltip {
                                        position: absolute;
                                        z-index: 1030;
                                        display: block;
                                        visibility: visible;
                                        font-size: 11px;
                                        line-height: 1.4;
                                        opacity: 0;
                                        filter: alpha(opacity=0);
                                    }

                                    .tooltip.in {
                                        opacity: 0.8;
                                        filter: alpha(opacity=80);
                                    }

                                    .tooltip.top {
                                        margin-top: -3px;
                                        padding: 5px 0;
                                    }

                                    .tooltip.right {
                                        margin-left: 3px;
                                        padding: 0 5px;
                                    }

                                    .tooltip.bottom {
                                        margin-top: 3px;
                                        padding: 5px 0;
                                    }

                                    .tooltip.left {
                                        margin-left: -3px;
                                        padding: 0 5px;
                                    }

                                    .tooltip-inner {
                                        max-width: 200px;
                                        padding: 8px;
                                        color: #ffffff;
                                        text-align: center;
                                        text-decoration: none;
                                        background-color: #000000;
                                        -webkit-border-radius: 0;
                                        -moz-border-radius: 0;
                                        border-radius: 0;
                                    }

                                    .tooltip-arrow {
                                        position: absolute;
                                        width: 0;
                                        height: 0;
                                        border-color: transparent;
                                        border-style: solid;
                                    }

                                    .tooltip.top .tooltip-arrow {
                                        bottom: 0;
                                        left: 50%;
                                        margin-left: -5px;
                                        border-width: 5px 5px 0;
                                        border-top-color: #000000;
                                    }

                                    .tooltip.right .tooltip-arrow {
                                        top: 50%;
                                        left: 0;
                                        margin-top: -5px;
                                        border-width: 5px 5px 5px 0;
                                        border-right-color: #000000;
                                    }

                                    .tooltip.left .tooltip-arrow {
                                        top: 50%;
                                        right: 0;
                                        margin-top: -5px;
                                        border-width: 5px 0 5px 5px;
                                        border-left-color: #000000;
                                    }

                                    .tooltip.bottom .tooltip-arrow {
                                        top: 0;
                                        left: 50%;
                                        margin-left: -5px;
                                        border-width: 0 5px 5px;
                                        border-bottom-color: #000000;
                                    }
                                </style>

                                <table width="100%" align="center" border="0" cellpadding="4" cellspacing="0">
                                    <tr class="lnk" bgcolor="#ffffff">
                                        <td align="left" width="100%">
                                            <div id="chartplace" style="height:300px;"></div>
                                            <script type="text/javascript">
                                                jQuery(document).ready(function () {

                                                    // simple chart
                                                    var active = [];
                                                    var inactive = [];
                                                    var suspended = [];

                                                    function showTooltip(x, y, contents) {
                                                        jQuery('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css({
                                                            position: 'absolute',
                                                            display: 'none',
                                                            top: y + 5,
                                                            left: x + 5
                                                        }).appendTo("body").fadeIn(200);
                                                    }

                                                    var plot = jQuery.plot(jQuery("#chartplace"),
                                                        [{data: active, label: "Active Member", color: "#6fad04"},
                                                            {data: inactive, label: "Inactive Member", color: "#f00"},
                                                            {
                                                                data: suspended,
                                                                label: "Suspended Member",
                                                                color: "#06c"
                                                            }],

                                                        {
                                                            series: {
                                                                lines: {
                                                                    show: true,
                                                                    fill: true,
                                                                    fillColor: {colors: [{opacity: 0.05}, {opacity: 0.15}]}
                                                                },
                                                                points: {show: true}
                                                            },
                                                            legend: {position: 'nw'},
                                                            grid: {
                                                                hoverable: true,
                                                                clickable: true,
                                                                borderColor: '#666',
                                                                borderWidth: 2,
                                                                labelMargin: 10
                                                            },
                                                            xaxis: {
                                                                min: <?=date('Y')-5?>, max: <?=date('Y')?>,
                                                                tickFormatter: function suffixFormatter(val, axis) {
                                                                    return (val.toFixed(0));
                                                                }
                                                            }
                                                        });

                                                    var previousPoint = null;
                                                    jQuery("#chartplace").bind("plothover", function (event, pos, item) {
                                                        jQuery("#x").text(pos.x);
                                                        jQuery("#y").text(pos.y);

                                                        if (item) {
                                                            if (previousPoint != item.dataIndex) {
                                                                previousPoint = item.dataIndex;

                                                                jQuery("#tooltip").remove();
                                                                var x = item.datapoint[0];
                                                                var y = item.datapoint[1];

                                                                showTooltip(item.pageX, item.pageY,
                                                                    item.series.label + " " + parseInt(y) + " on " + parseInt(x));
                                                            }

                                                        } else {
                                                            jQuery("#tooltip").remove();
                                                            previousPoint = null;
                                                        }

                                                    });

                                                    jQuery("#chartplace").bind("plotclick", function (event, pos, item) {
                                                        if (item) {
                                                            jQuery("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
                                                            plot.highlight(item.series, item.datapoint);
                                                        }
                                                    });
                                                });
                                            </script>
                                        </td>


                                    </tr>
                                </table>
                            </div>


                            <div class="clearfix"></div>


                        </div>
                        <!-- End .panel-body -->


                        <div class="clearfix"></div>


                    </div>
                </div>
                <!-- End .col-lg-6  -->
            </div>


            <!-------- MEMBER GRAPH END --------->

            <div class="row">
                <div class="col-lg-8" style="width: 98.667%;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="icon"><i class="icon20 i-stats"></i></div>
                            <h4>Project Statistic</h4>
                            <a href="#" class="minimize"></a>
                        </div>
                        <!-- End .panel-heading -->

                        <div class="panel-body">
                            <div class="campaign-stats center" style="border-top:none;">
                                <div class="items">
                                    <div class="percentage" data-percent="100"><span>100</span></div>
                                    <div class="txt">Total Project <?php echo $total_project; ?></div>
                                </div>
                                <div class="items">
                                    <div class="percentage-green" data-percent="0">
                                        <span>0</span>%
                                    </div>
                                    <div class="txt">Open Projects 0</div>
                                </div>

                                <div class="items">
                                    <div class="percentage" data-percent="1">
                                        <span>1</span>%
                                    </div>
                                    <div class="txt">Frozen Projects 1</div>
                                </div>
                                <div class="items">
                                    <div class="percentage" data-percent="2">
                                        <span>2</span>%
                                    </div>
                                    <div class="txt">Working Projects 2</div>
                                </div>
                                <div class="items">
                                    <div class="percentage" data-percent="3">
                                        <span>3</span>%
                                    </div>
                                    <div class="txt">Complete Projects 3</div>
                                </div>
                                <div class="items">
                                    <div class="percentage-red" data-percent="4">
                                        <span>4</span>%
                                    </div>
                                    <div class="txt">Expire Project 4</div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                        </div>
                        <!-- End .panel-body -->
                    </div>
                    <!-- End .widget -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8" style="width: 98.667%;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="icon"><i class="icon20 i-stats"></i></div>
                            <h4>Project Graph Chart</h4>
                            <a href="#" class="minimize"></a>
                        </div>
                        <!-- End .panel-heading -->

                        <div class="panel-body">
                            <div class="campaign-stats center" style="border-top:none;">
                                <style>
                                    #tooltip {
                                        background: #000;
                                        color: #fff;
                                        padding: 5px 10px;
                                        font-family: inherit;
                                        font-size: 9px;
                                    }

                                    .tooltip {
                                        position: absolute;
                                        z-index: 1030;
                                        display: block;
                                        visibility: visible;
                                        font-size: 11px;
                                        line-height: 1.4;
                                        opacity: 0;
                                        filter: alpha(opacity=0);
                                    }

                                    .tooltip.in {
                                        opacity: 0.8;
                                        filter: alpha(opacity=80);
                                    }

                                    .tooltip.top {
                                        margin-top: -3px;
                                        padding: 5px 0;
                                    }

                                    .tooltip.right {
                                        margin-left: 3px;
                                        padding: 0 5px;
                                    }

                                    .tooltip.bottom {
                                        margin-top: 3px;
                                        padding: 5px 0;
                                    }

                                    .tooltip.left {
                                        margin-left: -3px;
                                        padding: 0 5px;
                                    }

                                    .tooltip-inner {
                                        max-width: 200px;
                                        padding: 8px;
                                        color: #ffffff;
                                        text-align: center;
                                        text-decoration: none;
                                        background-color: #000000;
                                        -webkit-border-radius: 0;
                                        -moz-border-radius: 0;
                                        border-radius: 0;
                                    }

                                    .tooltip-arrow {
                                        position: absolute;
                                        width: 0;
                                        height: 0;
                                        border-color: transparent;
                                        border-style: solid;
                                    }

                                    .tooltip.top .tooltip-arrow {
                                        bottom: 0;
                                        left: 50%;
                                        margin-left: -5px;
                                        border-width: 5px 5px 0;
                                        border-top-color: #000000;
                                    }

                                    .tooltip.right .tooltip-arrow {
                                        top: 50%;
                                        left: 0;
                                        margin-top: -5px;
                                        border-width: 5px 5px 5px 0;
                                        border-right-color: #000000;
                                    }

                                    .tooltip.left .tooltip-arrow {
                                        top: 50%;
                                        right: 0;
                                        margin-top: -5px;
                                        border-width: 5px 0 5px 5px;
                                        border-left-color: #000000;
                                    }

                                    .tooltip.bottom .tooltip-arrow {
                                        top: 0;
                                        left: 50%;
                                        margin-left: -5px;
                                        border-width: 0 5px 5px;
                                        border-bottom-color: #000000;
                                    }
                                </style>

                                <table width="100%" align="center" border="0" cellpadding="4" cellspacing="0">
                                    <tr class="lnk" bgcolor="#ffffff">

                                        <td align="left" width="100%">
                                            <div id="chartplace1" style="height:300px;"></div>
                                            <script type="text/javascript">
                                                jQuery(document).ready(function () {

                                                    // simple chart
                                                    var openp = [
                                                        
                                                    ];
                                                    var progress = [
                                                       
                                                    ];
                                                    var complete = [
                                                        
                                                    ];
                                                    var expire = [
                                                       
                                                    ];
                                                    var frozen = [
                                                        
                                                    ];


                                                    function showTooltip(x, y, contents) {
                                                        jQuery('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css({
                                                            position: 'absolute',
                                                            display: 'none',
                                                            top: y + 5,
                                                            left: x + 5
                                                        }).appendTo("body").fadeIn(200);
                                                    }


                                                    var plot = jQuery.plot(jQuery("#chartplace1"),
                                                        [{data: openp, label: "Open Project", color: "#6fad04"},
                                                            {
                                                                data: progress,
                                                                label: "On Progress Project",
                                                                color: "#06c"
                                                            },
                                                            {data: complete, label: "Completed Project", color: "#d6c"},
                                                            {data: frozen, label: "Frozen Project", color: "#dfc"},
                                                            {data: close, label: "Close Project", color: "#EB7D00"},
                                                            {data: expire, label: "Expired Project", color: "#f00"}], {
                                                            series: {
                                                                lines: {
                                                                    show: true,
                                                                    fill: true,
                                                                    fillColor: {colors: [{opacity: 0.05}, {opacity: 0.15}]}
                                                                },
                                                                points: {show: true}
                                                            },
                                                            legend: {position: 'nw'},
                                                            grid: {
                                                                hoverable: true,
                                                                clickable: true,
                                                                borderColor: '#666',
                                                                borderWidth: 2,
                                                                labelMargin: 10
                                                            },
                                                            xaxis: {
                                                                min: <?=date('Y')-5?>, max: <?=date('Y')?>,
                                                                tickFormatter: function suffixFormatter(val, axis) {
                                                                    return (val.toFixed(0));
                                                                }
                                                            }
                                                        });

                                                    var previousPoint = null;
                                                    jQuery("#chartplace1").bind("plothover", function (event, pos, item) {
                                                        jQuery("#x").text(pos.x);
                                                        jQuery("#y").text(pos.y);

                                                        if (item) {
                                                            if (previousPoint != item.dataIndex) {
                                                                previousPoint = item.dataIndex;

                                                                jQuery("#tooltip").remove();
                                                                var x = item.datapoint[0].toFixed(0),
                                                                    y = item.datapoint[1].toFixed(1);

                                                                showTooltip(item.pageX, item.pageY,
                                                                    item.series.label + " of " + x + " = " + y);
                                                            }

                                                        } else {
                                                            jQuery("#tooltip").remove();
                                                            previousPoint = null;
                                                        }

                                                    });

                                                    jQuery("#chartplace1").bind("plotclick", function (event, pos, item) {
                                                        if (item) {
                                                            jQuery("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
                                                            plot.highlight(item.series, item.datapoint);
                                                        }
                                                    });
                                                });
                                            </script>
                                        </td>

                                    </tr>
                                </table>
                            </div>
                            <div class="clearfix"></div>

                        </div>
                        <!-- End .panel-body -->
                    </div>
                    <!-- End .widget -->
                </div>
                <?php /*?><div class="col-lg-8" style="width: 48.667%;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="icon"><i class="icon20 i-pie-5"></i></div> 
                                    <h4>Verification Status</h4>
                                    <a href="#" class="minimize"></a>
                                </div><!-- End .panel-heading -->
                            
                                <div class="panel-body">
                                    <div class="chart-pie-social" style="width: 100%; height:250px;"></div>
                                </div><!-- End .panel-body -->
                            </div><!-- End .widget -->
                        </div><?php */ ?><!-- End .col-lg-6  -->
            </div>
            <!-------------FINANCIAL STATISTICS START ---------------->
            <div class="row">
                <div class="col-lg-8" style="width: 98.667%;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="icon"><i class="icon20 i-stats"></i></div>
                            <h4>Financial Graph Chart</h4>
                            <a href="#" class="minimize"></a>
                        </div>
                        <!-- End .panel-heading -->

                        <div class="panel-body">
                            <div class="campaign-stats center" style="border-top:none;">
                                <style>
                                    #tooltip {
                                        background: #000;
                                        color: #fff;
                                        padding: 5px 10px;
                                        font-family: inherit;
                                        font-size: 9px;
                                    }

                                    .tooltip {
                                        position: absolute;
                                        z-index: 1030;
                                        display: block;
                                        visibility: visible;
                                        font-size: 11px;
                                        line-height: 1.4;
                                        opacity: 0;
                                        filter: alpha(opacity=0);
                                    }

                                    .tooltip.in {
                                        opacity: 0.8;
                                        filter: alpha(opacity=80);
                                    }

                                    .tooltip.top {
                                        margin-top: -3px;
                                        padding: 5px 0;
                                    }

                                    .tooltip.right {
                                        margin-left: 3px;
                                        padding: 0 5px;
                                    }

                                    .tooltip.bottom {
                                        margin-top: 3px;
                                        padding: 5px 0;
                                    }

                                    .tooltip.left {
                                        margin-left: -3px;
                                        padding: 0 5px;
                                    }

                                    .tooltip-inner {
                                        max-width: 200px;
                                        padding: 8px;
                                        color: #ffffff;
                                        text-align: center;
                                        text-decoration: none;
                                        background-color: #000000;
                                        -webkit-border-radius: 0;
                                        -moz-border-radius: 0;
                                        border-radius: 0;
                                    }

                                    .tooltip-arrow {
                                        position: absolute;
                                        width: 0;
                                        height: 0;
                                        border-color: transparent;
                                        border-style: solid;
                                    }

                                    .tooltip.top .tooltip-arrow {
                                        bottom: 0;
                                        left: 50%;
                                        margin-left: -5px;
                                        border-width: 5px 5px 0;
                                        border-top-color: #000000;
                                    }

                                    .tooltip.right .tooltip-arrow {
                                        top: 50%;
                                        left: 0;
                                        margin-top: -5px;
                                        border-width: 5px 5px 5px 0;
                                        border-right-color: #000000;
                                    }

                                    .tooltip.left .tooltip-arrow {
                                        top: 50%;
                                        right: 0;
                                        margin-top: -5px;
                                        border-width: 5px 0 5px 5px;
                                        border-left-color: #000000;
                                    }

                                    .tooltip.bottom .tooltip-arrow {
                                        top: 0;
                                        left: 50%;
                                        margin-left: -5px;
                                        border-width: 0 5px 5px;
                                        border-bottom-color: #000000;
                                    }
                                </style>

                                <table width="100%" align="center" border="0" cellpadding="4" cellspacing="0">
                                    <tr class="lnk" bgcolor="#ffffff">

                                        <td align="left" width="100%">
                                            <div id="chartplace2" style="height:300px;"></div>
                                            <script type="text/javascript">
                                                jQuery(document).ready(function () {

                                                    // simple chart
                                                    var credit = [];
                                                    var debit = [ ];
                                                    var profit = [ ];


                                                    function showTooltip(x, y, contents) {
                                                        jQuery('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css({
                                                            position: 'absolute',
                                                            display: 'none',
                                                            top: y + 5,
                                                            left: x + 5
                                                        }).appendTo("body").fadeIn(200);
                                                    }


                                                    var plot = jQuery.plot(jQuery("#chartplace2"),
                                                        [{
                                                            data: credit,
                                                            label: "Total Credited Amount",
                                                            color: "#6fad04"
                                                        },
                                                            {data: debit, label: "Total Debited Amount", color: "#06c"},
                                                            {data: profit, label: "Net Profit", color: "#d6c"}], {
                                                            series: {
                                                                lines: {
                                                                    show: true,
                                                                    fill: true,
                                                                    fillColor: {colors: [{opacity: 0.05}, {opacity: 0.15}]}
                                                                },
                                                                points: {show: true}
                                                            },
                                                            legend: {position: 'nw'},
                                                            grid: {
                                                                hoverable: true,
                                                                clickable: true,
                                                                borderColor: '#666',
                                                                borderWidth: 2,
                                                                labelMargin: 10
                                                            },
                                                            xaxis: {
                                                                min: <?=date('Y')-5?>, max: <?=date('Y')?>,
                                                                tickFormatter: function suffixFormatter(val, axis) {
                                                                    return (val.toFixed(0));
                                                                }
                                                            }
                                                        });

                                                    var previousPoint = null;
                                                    jQuery("#chartplace2").bind("plothover", function (event, pos, item) {
                                                        jQuery("#x").text(pos.x);
                                                        jQuery("#y").text(pos.y);

                                                        if (item) {
                                                            if (previousPoint != item.dataIndex) {
                                                                previousPoint = item.dataIndex;

                                                                jQuery("#tooltip").remove();
                                                                var x = item.datapoint[0].toFixed(0),
                                                                    y = item.datapoint[1].toFixed(1);

                                                                showTooltip(item.pageX, item.pageY,
                                                                    item.series.label + " of " + x + " = " + y);
                                                            }

                                                        } else {
                                                            jQuery("#tooltip").remove();
                                                            previousPoint = null;
                                                        }

                                                    });

                                                    jQuery("#chartplace2").bind("plotclick", function (event, pos, item) {
                                                        if (item) {
                                                            jQuery("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
                                                            plot.highlight(item.series, item.datapoint);
                                                        }
                                                    });
                                                });
                                            </script>
                                        </td>

                                    </tr>
                                </table>
                            </div>
                            <div class="clearfix"></div>

                        </div>
                        <!-- End .panel-body -->
                    </div>
                    <!-- End .widget -->
                </div>
            </div>


        </div>
        <!-- End .container-fluid  -->
    </div>
    <!-- End .wrapper  -->
</section>
   