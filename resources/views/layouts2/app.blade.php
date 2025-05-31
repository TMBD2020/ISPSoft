<!DOCTYPE html>
<html lang="en">
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <meta name="author" content="TechMakersBD"/>
    <title>@yield("title") - ISP Easy Soft  </title>
    <link rel="apple-touch-icon" href=""/>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(Settings::settings()["company_logo"]) }}"/>
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/vendors.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/charts/chartist.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/charts/chartist-plugin-tooltip.css")}}">

    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/extensions/toastr.css")}}">

    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/pickers/daterange/daterangepicker.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/pickers/pickadate/default.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/pickers/pickadate/default.date.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/pickers/pickadate/default.time.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/pickers/daterange/daterange.min.css")}}">
    <!-- END: Vendor CSS-->

    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/tables/datatable/datatables.min.css")}}">
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/vendors/css/forms/selects/select2.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/bootstrap.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/bootstrap-extended.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/colors.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/components.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/extensions/toastr.min.css")}}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/menu/menu-types/vertical-menu.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/colors/palette-gradient.min.cs")}}s">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/core/colors/palette-gradient.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/forms/wizard.min.css")}}">

    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/plugins/animate/animate.min.css")}}">
    <!-- END: Page CSS-->


</head>
<!-- END: Head-->
<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu 2-columns fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="2-columns">

    @include("layouts.header")


    @if(Auth::user()->user_type=="client")
        @include("layouts.client_sidebar")
    @elseif(Auth::user()->user_type=="super")
        @include("layouts.super_sidebar")
    @else
        @include("layouts.admin_sidebar")
    @endif

    <div id="loader" class="lds-dual-ring hidden overlay"><img src="{{ asset("app-assets/images/loading.gif")}}"></div>
    @yield("content")
    @include("layouts.footer")

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset("app-assets/vendors/js/vendors.min.js")}}" type="text/javascript"></script>
    <!-- BEGIN Vendor JS-->
    <!-- BEGIN: Theme JS-->
    <script src="{{ asset("app-assets/js/core/app-menu.min.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/js/core/app.min.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/js/scripts/customizer.min.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/vendors/js/jquery.sharrre.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/vendors/js/extensions/toastr.min.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/vendors/js/forms/select/select2.full.min.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/vendors/js/extensions/jquery.steps.min.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/vendors/js/forms/validation/jquery.validate.min.js")}}" type="text/javascript"></script>
    <!-- END: Theme JS-->



    <script src="{{ asset("app-assets/vendors/js/tables/datatable/datatables.min.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/js/scripts/forms/select/form-select2.min.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/js/scripts/jscolor.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/vendors/js/animation/jquery.appear.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/js/scripts/extensions/block-ui.js")}}" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset("app-assets/css/bootstrap-datetimepicker.min.css")}}"/>
    <script src="{{ asset("app-assets/js/bootstrap-datetimepicker.min.js")}}" type="text/javascript"></script>
    <script src="{{ asset("app-assets/js/scripts/animation/animation.js")}}" type="text/javascript"></script>
<!-- BEGIN: Page JS-->
    <script type="text/javascript">

        $(window).scroll(function () {
            if($(window).scrollTop() > 50) {
                $(".headTitle").show().text($("#tabOption").text());
            } else {
                $(".headTitle").hide();
            }
        });

        $(document).ready(function () {

            $(document).on('click', '.mySMSBal', function () {
                $("#sms_balance_modal").modal("show")
            });

            $(".datepicker_startdate").datetimepicker({
                format: "dd/mm/yyyy",
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0,
                startDate: new Date()
            });
            $("body").delegate(".datepicker", "focusin", function(){
//                $(this).datepicker({
//                    format: "dd/mm/yyyy",
//                    autoclose: true,
//                });

                $('.datetimepicker').datetimepicker({
                   // format: "dd/mm/yyyy",
                    weekStart: 1,
                    todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    forceParse: 0,
                    showMeridian: 1
                });
                $(this).datetimepicker({
                    format: "dd/mm/yyyy",
                    weekStart: 1,
                    todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    minView: 2,
                    forceParse: 0
                });

                $('.form_time').datetimepicker({

                    weekStart: 1,
                    todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 1,
                    minView: 0,
                    maxView: 1,
                    forceParse: 0
                });
            });

            $("body").delegate(".datetimepicker", "focusin", function(){
                $(this).datetimepicker({
                    format: "dd/mm/yyyy hh:ii",
                    weekStart: 1,
                    todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    forceParse: 0,
                    showMeridian: 1
                });
            });

            $("input").attr("autocomplete","off");

            $(document).on('change', '#clr1', function () {
                var element = $(this);
                var clr1 = element.val();
                var clr2 = $('#clr2').val();

                customColor(clr1,clr2);

            });

            $(document).on('change', '#clr2', function () {
                var element = $(this);
                var clr2 = element.val();
                var clr1 = $('#clr1').val();

                customColor(clr1,clr2);

            });
        });
        function customColor(clr1,clr2){
            //clr1="9f78ff",clr2="32cafe"
            $(".content-wrapper-before").css({
                "background-image": "-webkit-gradient(linear,left top,right top,from(#"+clr1+"),to(#"+clr2+"))",
                "background-image": "-webkit-linear-gradient(left,#"+clr1+",#"+clr2+")",
                "background-image": "-moz-linear-gradient(left,#"+clr1+",#"+clr2+")",
                "background-image": "-o-linear-gradient(left,#"+clr1+",#"+clr2+")",
                "background-image": "linear-gradient(to right,#"+clr1+",#"+clr2+")",
                "background-repeat": "repeat-x"
            })
            $(".navbar-container").css({
                "background-image": "-webkit-gradient(linear,left top,right top,from(#"+clr1+"),to(#"+clr2+"))",
                "background-image": "-webkit-linear-gradient(left,#"+clr1+",#"+clr2+")",
                "background-image": "-moz-linear-gradient(left,#"+clr1+",#"+clr2+")",
                "background-image": "-o-linear-gradient(left,#"+clr1+",#"+clr2+")",
                "background-image": "linear-gradient(to right,#"+clr1+",#"+clr2+")",
                "background-repeat": "repeat-x"
            })
        }

        function blockLoad(){
            $.blockUI({
                message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        }
        function unblockLoad(){
            $.unblockUI();
        }
        </script>
    @yield("dashboard_script")
    @yield("page_script")
<style>
    /*Hidden class for adding and removing*/
    .lds-dual-ring.hidden {
        display: none;
    }

    /*Add an overlay to the entire page blocking any further presses to buttons or other elements.*/
    .overlay {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100vh;
        z-index: 9999999;
        opacity: 1;
        transition: all 0.5s;
    }

    /*Spinner Styles*/
    .lds-dual-ring {
        display: inline-block;
        width: 80px;
        height: 80px;
    }
    @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    .content-wrapper-before, body.vertical-layout[data-color=bg-gradient-x-purple-blue] .navbar-container  {
        background-image: -webkit-gradient(linear,left top,right top,from(#{{ Settings::theme()["header_bg_color_1"] }}),to(#{{ Settings::theme()["header_bg_color_2"] }}));
        background-image: -webkit-linear-gradient(left,#{{ Settings::theme()["header_bg_color_1"] }},#{{ Settings::theme()["header_bg_color_2"] }});
        background-image: -moz-linear-gradient(left,#{{ Settings::theme()["header_bg_color_1"] }},#{{ Settings::theme()["header_bg_color_2"] }});
        background-image: -o-linear-gradient(left,#{{ Settings::theme()["header_bg_color_1"] }},#{{ Settings::theme()["header_bg_color_2"] }});
        background-image: linear-gradient(to right,#{{ Settings::theme()["header_bg_color_1"] }},#{{ Settings::theme()["header_bg_color_2"] }});
        background-repeat: repeat-x;
    }


    .dataTable,.dataTables_scrollHeadInner{
width: 100%;
    }
    .nav-tabs .nav-link:not(.active){
        border: 0px solid #6967ce;
        color: #fff;
        background: #42a7c5;
        border-radius: .25rem;
        border-right: 1px solid #6967ce;
    }
    div.dataTables_wrapper div.dataTables_processing{
        top:10% !important;
    }
</style>
  </body>
  <!-- END: Body-->
</html>
