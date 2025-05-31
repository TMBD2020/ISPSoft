
<link rel="stylesheet" type="text/css" href="app-assets/css/pages/error.min.css">
<div class="app-content content ">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
        </div>
        <div class="content-body ">

            <section id="configuration">
                <div class="row">
                    <div class="col-12">
                        <div class="card  bg-gradient-directional-danger" style="margin:0">
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">


                                <section class="flexbox-container bg-hexagons-danger">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="col-lg-6 col-md-6 col-10 p-0">
                        <div class="card-header bg-transparent border-0">
                            <h2 class="error-code text-center mb-2 white">403</h2>
                            <h3 class="text-uppercase text-center white">Access Denied/Forbidden !</h3>
                        </div>

                    </div>
                </div>
            </section>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@section("page_script")
<script>
    $(".bg-gradient-directional-danger").attr("style", "height:"+(Number($(".app-content").height())-20)+"px")
</script>
    @endsection