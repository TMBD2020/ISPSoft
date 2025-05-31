@extends('layouts.app')

@section('title', 'CATV Station Import/Export')

@section('content')
        <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title">@yield("title")</h3>
            </div>
            <div class="content-header-right col-md-6 col-12">

            </div>
        </div>
        <div class="content-body"><!-- Zero configuration table -->
            <section id="configuration">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">

                                    <div class="card inner-card">
                                        <div class="card-body text-center" style="padding: 6px 10px;">

                                            @if(session("msg"))

                                                <div class="alert alert-success alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <strong>Success!</strong>  {{ session("msg") }}
                                                </div>
                                                @endif

                                            <h3>Import Excel File</h3>

                                                <a href="{{ asset("app-assets/files/catv_station.xlsx") }}" download="catv station"><i class="ft-download"></i> Excel Demo Download</a>
                                            <br>
                                            <br>
                                            <form action="{{ route('catv-station-import') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                                                    <img class="excel_img" style="display: none;width: 50px;float: left;" src="{{ asset("app-assets/images/excel-icon.png") }}">
                                                    <span class="fileName" style="float: left;"></span>

                                                    <div class="custom-file text-left">
                                                        <input  onchange="return Validate(this)" type="file" id="file" name="file" class="custom-file-input" required="" autocomplete="off">
                                                        <label  class="custom-file-label" for="customFile">Choose file</label>
                                                    </div>
                                                </div>

                                                <button class="btn btn-primary">Import data</button>
                                            </form>


                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Zero configuration table -->
        </div>
    </div>
</div>
<!-- END: Content-->


@endsection
@section("page_script")
    <script>
        var _validFileExtensions = [".xlsx", ".csv"];
        function Validate(oForm) {
            var arrInputs = $("#file");
            for (var i = 0; i < arrInputs.length; i++) {
                var oInput = arrInputs[i];
                if (oInput.type == "file") {
                    var sFileName = oInput.value;
                    if (sFileName.length > 0) {
                        var blnValid = false;
                        for (var j = 0; j < _validFileExtensions.length; j++) {
                            var sCurExtension = _validFileExtensions[j];
                            if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                                blnValid = true;
                                $(".excel_img").show();
                                $(".fileName").html(sFileName.split(/(\\|\/)/g).pop());
                                break;
                            }
                        }

                        if (!blnValid) {
                            arrInputs.val('');
                            $(".excel_img").hide();
                            $(".fileName").html('');
                            alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                            return false;
                        }
                    }
                }
            }

            if(arrInputs.val()==""){
                $(".excel_img").hide();
                $(".fileName").html('');
            }
            return true;
        }
    </script>
    @endsection