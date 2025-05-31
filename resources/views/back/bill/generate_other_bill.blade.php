@extends('layouts.app')

@section('title', 'Generate Other Bill')

@section('content')


        <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title" id="tabOption">@yield("title")</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">


            </div>
        </div>
        <div class="content-body"><!-- Zero configuration table -->
            <section id="configuration">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">

                                    <div class="card">
                                        <div class="col-lg-12 col-xs-12  col-md-12 col-sm-12">
                                            <form id="purchaseForm" method="post">
                                                {{ csrf_field() }}
                                                <div class="row">
                                                    <input type="hidden" id="action" name="action">
                                                    <input type="hidden" id="id" name="id">

                                                    <div class="col-md-3">
                                                        <label for="store_date">Date <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control datepicker" value="{{ date("d/m/Y") }}" name="store_date" id="store_date" required>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="purchaser_id">Purchaser name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly name="" id="" required>
                                                        <input type="hidden" value="{{ Auth::user()->id }}" name="purchaser_id" id="purchaser_id">
                                                    </div>
                                                    <div  class="col-md-12"><br></div>

                                                    <div  class="col-md-12"><br></div>
                                                    <div class="col-md-5">
                                                        <label for="product_id">Product name <span class="text-danger">*</span></label>
                                                        <select class="form-control select2" name="" id="product_id">
                                                            <option value=""></option>
                                                            @foreach($stock_products as $product)
                                                                <option value="{{ $product->id }}">{{ $product->product_name  }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="available_qty">Available Qty </label>
                                                        <input type="number" class="form-control text-center" readonly name="" id="available_qty">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="product_qty">Requisition Qty <span class="text-danger">*</span></label>
                                                        <input type="number" min="1" class="form-control text-center"  name="" id="product_qty">
                                                    </div>


                                                    <div class="col-md-3">

                                                        <button type="button" class="btn btn-primary mt-1 mb-0 add" style="    margin-top: 25px !important">Add</button>
                                                    </div>
                                                </div>

                                                <div class="mytable" style="display: none;">
                                                    <br>
                                                    <br>

                                                    <table class="table table-bordered border-striped " >
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">SL</th>
                                                            <th class="text-center">Product Name</th>
                                                            <th class="text-center">Qty</th>
                                                            <th class="text-center">#</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="product_list"></tbody>
                                                    </table>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="remarks">Remarks </label>
                                                            <textarea name="remarks" id="remarks" class="form-control" rows="1" style="resize: vertical;"></textarea>
                                                        </div>
                                                    </div>

                                                    <button style="display: none;" type="submit" class="btn btn-primary mt-1 mb-0 save pull-right">Save</button>
                                                </div>
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


<style>
    form .row{
        margin-top:10px;
    }
</style>
@endsection
@section("page_script")
    <script type="text/javascript">

        productSelectList();
        brandSelectList();
        var brandTable;
        var sl =0;

        var availableQty=0;
        var totalQty=0;
        var subTotal=0;
        var myproduct =[];
        $(document).ready(function () {
            var myclick = 0;
            $(document).on('click', '.add', function () {

                var id           = Number($("#product_id").val());
                var qty          = Number($("#product_qty").val());

                if(id!=""){
                    if(qty!=""){
                        $("#purchaseForm .mytable").show();
                        $("#purchaseForm .save").show();
                        if (myproduct.indexOf(id) == -1)
                        {
                            myclick++;
                            var name          = $("#product_id :selected").text();
                            myproduct.push(id);

                            sl++;
                            var html = "<tr class='rows"+id+"'>"+
                                    "<td class='text-center'>" + sl +"</td>";

                            html +="<td>" + name  +
                                    "<input type='hidden' name='product_id[]' value='"+id+"'>" +
                                    "<input type='hidden' name='product_qty[]' value='"+qty+"'>" +
                                    "</td>" ;

                            html +="<td class='text-center'>"+qty+"</td>" ;

                            html +="<td class='text-center text-danger' style='font-size: 17px;cursor:pointer'>" +
                                    "<i class='ft-trash remove' id='"+id+"' qty='"+qty+"'></i></td>" ;

                            html +="</tr>";
                            $(".product_list").append(html);
                            totalQty+=qty;
                            if(myclick>0){
                                $(".click"+(myclick-1)).remove();
                            }
                            $(".subQty").text(totalQty);

                            $('#product_id').val("").trigger("change");
                            $("#product_qty").val("");
                        }else{
                            alert("This Product Already Added!");
                            $('#product_id').val("").trigger("change");
                            $("#product_qty").val("");
                        }
                    }else{
                        alert("Quantity cannot blank left!");
                        $("#product_qty").focus();
                        return false;
                    }
                }else{
                    alert("Product cannot blank left!");
                    $("#product_id").focus();
                    return false;
                }
            });

            $(document).on('click', '.remove', function () {
                var id = $(this).attr("id");
                var qty = Number($(this).attr("qty"));
                $(".rows"+id).remove();
                var sub_qty = Number($(".subQty").text());
                var final_q = sub_qty-qty;
                totalQty-=qty;
                $(".subQty").text(final_q);
                var index = myproduct.indexOf(id);
                myproduct.splice(index, 1);
                myclick--;
                sl--;
                if(myclick==0){
                    sl=0;
                    $(".click1").remove();
                    $("#purchaseForm .mytable").hide();
                    $("#purchaseForm .save").hide();
                }
            });

            $(document).on('submit', "#purchaseForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ url('requisition_product') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        console.log(response);
                        if (response == 1) {
                            myproduct =[];
                            $(".mytable").hide();
                            $("#purchaseForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                        }
                        else {
                            toastr.warning( 'Data Cannot Saved. Try again!', 'Warning');
                        }
                        $(".save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try again!', 'Warning');
                        $(".save").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('change', "#product_id", function () {
                var id = $(this).val();
                if(id){
                    if (myproduct.indexOf(id) == -1) {
                        $.ajax({
                            type: "POST",
                            url: "{{ url('get_available_product') }}",
                            data: "id=" + id + "&_token={{csrf_token()}}",
                            success: function (response) {
                                if (response >= 0) {
                                    availableQty = response;
                                    $("#available_qty").val(response);
                                    $("#product_qty").attr("max", response);
                                } else {
                                    $("#available_qty").val(0);
                                    $("#product_qty").attr("max", 0);
                                }
                            },
                            error: function (request, status, error) {
                                console.log(request.responseText);
                                toastr.warning('Server Error. Try again!', 'Warning');
                                $(".save").text("Save").prop("disabled", false);
                            }
                        });
                    }else{
                        alert("This Product Already Added!");
                        $('#product_id').val("").trigger("change");
                        $("#product_qty").val("");
                    }
                }
            });
            $(document).on('input keyup', "#product_qty", function () {
                var product_qty = Number($(this).val());
                availableQty = Number($("#available_qty").val());
                if(product_qty>availableQty){
                    alert("Quantity not available.");
                    $(this).val(availableQty).focus();
                }
            });


        });

        function productSelectList(){
            var info = "_token={{csrf_token()}}"
            $.ajax({
                type: "POST",
                url: "{{url('product_list_show')}}",
                data: info,
                success: function (response) {
                    if(response!=0){

                        var element = $("#product_id");
                        element.empty();
                        element.append("<option></option>");
                        var json = JSON.parse(response);
                        $.each(json,function(key,value){
                            element.append("<option value='"+value.id+"'>"+value.product_name+"</option>");
                        });

                    }else{
                        toastr.warning( 'Server Error. Try again!', 'Warning');
                    }
                }
            });
        }

        function brandSelectList(){
            var info = "_token={{csrf_token()}}"
            $.ajax({
                type: "POST",
                url: "{{url('product_brand_show')}}",
                data: info,
                success: function (response) {
                    if(response!=0){

                        var element = $("#brand_id");
                        element.empty();
                        var json = JSON.parse(response);
                        $.each(json,function(key,value){
                            element.append("<option value='"+value.id+"'>"+value.brand_name+"</option>");
                        });

                    }else{
                        toastr.warning( 'Server Error. Try again!', 'Warning');
                    }
                }
            });
        }


    </script>

@endsection