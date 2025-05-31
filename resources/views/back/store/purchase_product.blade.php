@extends('layouts.app')

@section('title', 'Purchase Product')

@section('content')


        <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title" id="tabOption">@yield("title")</h3>
            </div>
            <div class="content-header-right col-md-8 col-12"></div>
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
                                                        <label for="store_date">Date <code>*</code></label>
                                                        <input type="text" class="form-control datepicker" value="{{ date("d/m/Y") }}" name="store_date" id="store_date" required>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="voucher_no">Voucher No <code>*</code></label>
                                                        <input type="text" min="0" class="form-control text-center"  name="voucher_no" id="voucher_no" required>
                                                    </div>

                                                    <div  class="col-md-12"><br></div>
                                                    <div class="col-md-3">
                                                        <label for="purchaser_id">Purchaser name <code>*</code></label>
                                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly name="" id="" required>
                                                        <input type="hidden" value="{{ Auth::user()->id }}" name="purchaser_id" id="purchaser_id">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="vendor_id">Vendor name <code>*</code></label>
                                                        <select class="form-control select2" name="vendor_id" id="vendor_id" required>
                                                            @foreach($vendors as $vendor)
                                                                <option value="{{ $vendor->id }}">{{ $vendor->vendor_name  }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="vendor_memo_no">Vendor Slip/Memo No <code>*</code></label>
                                                        <input type="text" class="form-control text-center"  name="vendor_memo_no" id="vendor_memo_no" required>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="product_status">Status <code>*</code></label>
                                                        <select class="form-control" name="product_status" id="product_status" required>
                                                            <option value="Brand New">Brand New</option>
                                                            <option value="Like New">Like New</option>
                                                            <option value="Used">Used</option>
                                                        </select>
                                                    </div>
                                                    <div  class="col-md-12"><br></div>
                                                    <div class="col-md-3">
                                                        <label for="product_id">Product name <code>*</code></label>
                                                        <div class="input-group">
                                                            <select class="form-control select2-duel-group" name="" id="product_id">
                                                                <option value=""></option>
                                                            </select>
                                                            <div class="input-group-addon btn-primary ft-plus addItem "  style="cursor: pointer" title="Add Brand"></div>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="product_qty">Qty <code>*</code></label>
                                                        <input type="number" min="1" class="form-control text-center"  name="" id="product_qty">
                                                    </div>


                                                    <div class="col-md-2">
                                                        <label for="product_fixed_price">Fixed Price <input type="checkbox" checked id="active_fixed_price"></label>
                                                        <input type="number"  min="1" class="form-control text-center"  name="" id="product_fixed_price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="product_unit_price">Unit Price <input type="checkbox" id="active_unit_price"></label>
                                                        <input type="number"  min="1" readonly class="form-control text-center"  name="" id="product_unit_price">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="serial_no">Serial/MAC</label>
                                                        <input type="text" class="form-control" name="" id="serial_no">
                                                    </div>

                                                    <div class="col-md-12">

                                                        <button type="button" class="btn btn-primary mt-1 mb-0 add pull-right" style="    margin-top: 25px !important">Add</button>
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
                                                        <th class="text-center">Serial No</th>
                                                        <th class="text-center">Qty</th>
                                                        <th class="text-center">Fixed Price</th>
                                                        <th class="text-center">#</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="product_list"></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th class="text-right" colspan="3">Sub Total: </th>
                                                            <th class="text-center subQty"></th>
                                                            <th class="text-center subTotal"></th>
                                                            <th class="text-center"></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="other_expense">Other Expense </label>
                                                        <input type="number"  min="0" class="form-control text-right" value="0" name="other_expense" id="other_expense">
                                                    </div>
                                                    <div class="col-md-9">
                                                        <label for="remarks">Remarks </label>
                                                        <textarea name="remarks" id="remarks" class="form-control" rows="1" style="resize: vertical;"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row" style="font-size: 18px">
                                                    <div class="col-md-12">
                                                        <div class="pull-right">
                                                            <b class="text-info">Grand Total : </b> <b class="text-primary allGtotal"></b>
                                                        </div>
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


<div class="modal fade text-left" id="ProductAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel8">Add Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="ProductAddForm" method="post">

                <input type="hidden" id="id" name="id">
                <input type="hidden" id="action" name="action" value="1">

                <div class="modal-body">
                    @csrf
                    <div class="col-md-12">
                        <div class="row">
                            <label for="product_name" class="col-sm-4">Product Name<code>*</code></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name="product_name" id="product_name" required autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <label for="brand_id" class="col-sm-4">Brand</label>
                            <div class="col-sm-8">

                                <div class="input-group">
                                    <select class="form-control select2-single-group" required name="brand_id" id="brand_id">
                                        <option></option>
                                    </select>

                                    <div class="input-group-addon btn-primary ft-plus addBrand"  style="cursor: pointer" title="Add Brand"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="AddProductBrand" tabindex="-1" role="dialog" aria-labelledby="myModalLabel8" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel8">Add Brand</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="AddBrandForm" method="post">

                <input type="hidden" id="id" name="id">
                <input type="hidden" id="action" name="action" value="1">

                <div class="modal-body">
                    @csrf
                    <div class="col-md-12">
                        <div class="row">
                            <label for="brand_name" class="col-sm-4">Brand Name<code>*</code></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name="brand_name" id="brand_name" required autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <label for="brand_description" class="col-sm-4">Description</label>
                            <div class="col-sm-8">
                                <textarea class="form-control"  name="brand_description" id="brand_description" placeholder="optional"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
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

        var totalQty=0;
        var subTotal=0;
        var myproduct =[];
        $(document).ready(function () {
            var myclick = 0;
            $(document).on('click', '.add', function () {

                var id           = Number($("#product_id").val());
                var qty          = Number($("#product_qty").val());
                var fixed_price  = Number($("#product_fixed_price").val());
                var unit_price   = Number($("#product_unit_price").val());
                var serial_no    = $("#serial_no").val();

                var price = "";
                var txt = "";
                var elementId = "";
                if ($('#active_fixed_price').prop('checked') == true ) {
                    price=fixed_price;
                    txt="Fixed";
                    elementId = "product_fixed_price";
                }
                if ($('#active_unit_price').prop('checked') == true ) {
                    price=unit_price;
                    txt="Unit";
                    elementId = "product_unit_price";
                }

                if(id!=""){
                    if(qty!=""){
                        if(price!=""){
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
                                "<input type='hidden' name='fixed_price[]' value='"+fixed_price+"'>" +
                                "<input type='hidden' name='unit_price[]' value='"+unit_price+"'>" +
                                "<input type='hidden' name='serial_no[]' value='"+serial_no+"'>" +
                                "</td>" ;

                                html +="<td class='text-center'>" +serial_no+"</td>" +
                                        "<td class='text-center'>"+qty+"</td>" +
                                        "<td class='text-center'>" + fixed_price +"</td>" ;

                                        html +="<td class='text-center text-danger' style='font-size: 17px;cursor:pointer'>" +
                                                "<i class='ft-trash remove' id='"+id+"' qty='"+qty+"' total='"+fixed_price+"'></i></td>" ;

                                    html +="</tr>";
                                $(".product_list").append(html);
                                subTotal+=fixed_price;
                                totalQty+=qty;
                                if(myclick>0){
                                    $(".click"+(myclick-1)).remove();
                                }
                                var expense =  Number($("#other_expense").val());
                                $(".subTotal").text(subTotal);
                                $(".subQty").text(totalQty);
                                $(".allGtotal").text(subTotal+expense);

                                //$('#product_id option[value="'+id+'"]').remove();
                                $('#product_id').val("").trigger("change");
                                $("#product_qty").val("");
                                $("#product_unit_price").val("");
                            }else{
                                alert("This Product Already Added!");
                            }
                        }else{
                            alert(txt+" Price cannot blank left!");
                            $("#"+elementId).focus();
                            return false;
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
                var total =  Number($(this).attr("total"));
                var expense =  Number($("#other_expense").val());
                $(".rows"+id).remove();
                var sub_qty = Number($(".subQty").text());
                var sub_total = Number($(".subTotal").text());
                var final_q = sub_qty-qty;
                var final_t = sub_total-total;
                totalQty-=qty;
                subTotal-=total;
                $(".subQty").text(final_q);
                $(".subTotal").text(final_t);
                $(".allGtotal").text(final_t+expense);
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

            $(document).on('input keyup', '#other_expense', function () {

                var expense = Number($(this).val());
                var grand_total = Number($(".allGtotal").text());
                $(".allGtotal").text(grand_total+expense);


            });

            $(document).on('change', '#active_unit_price', function () {
                if($(this).prop("checked") == true){
                    $("#product_unit_price").removeAttr("readonly","readonly").val("").focus();
                    $("#active_fixed_price").prop("checked",false);
                    $("#product_fixed_price").val("").attr("readonly","readonly");
                }else{
                    $("#product_fixed_price").removeAttr("readonly","readonly").val("").focus();
                    $("#active_fixed_price").prop("checked",true);
                    $("#product_unit_price").val("").attr("readonly","readonly");
                }
            });

            $(document).on('change', '#active_fixed_price', function () {
                if($(this).prop("checked") == true){
                    $("#product_fixed_price").removeAttr("readonly","readonly").val("").focus();
                    $("#active_unit_price").prop("checked",false);
                    $("#product_unit_price").val("").attr("readonly","readonly");
                }else{
                    $("#product_unit_price").removeAttr("readonly","readonly").val("").focus();
                    $("#active_unit_price").prop("checked",true);
                    $("#product_fixed_price").val("").attr("readonly","readonly");
                }
            });

            $(document).on('input keyup', '#product_unit_price', function () {
                var price = Number($(this).val());
                var qty = Number($("#product_qty").val());
                if(price){
                    $("#product_fixed_price").val((price*qty).toFixed(2));
                }else{
                    $("#product_fixed_price").val("");
                }

            });

            $(document).on('input keyup', '#product_fixed_price', function () {
                var price = Number($(this).val());
                var qty = Number($("#product_qty").val());
                if(price){
                    $("#product_unit_price").val((price/qty).toFixed(2));
                }else{
                    $("#product_unit_price").val("");
                }

            });

            $(document).on('input keyup', '#product_qty', function () {
                var qty = Number($(this).val());
                var price = 0;
                if ( $('#active_fixed_price').prop('checked') == true ) {
                    price = Number($("#product_fixed_price").val());
                    if(price){
                        $("#product_unit_price").val((price/qty).toFixed(2));
                    }else{
                        $("#product_unit_price").val("");
                    }

                }else{
                    price = Number($("#product_unit_price").val());
                    if(price){
                        $("#product_fixed_price").val((price*qty).toFixed(2));
                    }else{
                        $("#product_fixed_price").val("");
                    }
                }

            });

            $(document).on('submit', "#purchaseForm", function (e) {
                e.preventDefault();

                $(".save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('new_product_purchase') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        console.log(response);
                        if (response) {
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

            $(document).on('submit', "#ProductAddForm", function (e) {
                e.preventDefault();

                $("#ProductAddForm .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_product') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        //console.log(response);
                        if (response) {
                            $("#ProductAdd").modal("hide");
                            $("#ProductAddForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            productSelectList();
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

            $(document).on('click', '.addItem', function () {

                $("#ProductAdd").modal("show");
                var _this = "#ProductAddForm ";
                $(_this+" #action").val(1);
                $(_this+" #id").val("");
                $(_this).trigger("reset");
            });

            $(document).on('click', '.addBrand', function () {
                $("#AddProductBrand").modal("show");
                var _this = "#AddProductBrand ";
                $(_this+" #action").val(1);
                $(_this+" #id").val("");
                $(_this).trigger("reset");
            });

            $(document).on('submit', "#AddBrandForm", function (e) {
                e.preventDefault();
                var _this = "#AddPersonForm ";
                $(_this +" .save").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_product_brand') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        if (response == 1) {
                            $("#AddProductBrand").modal("hide");

                            $("#AddBrandForm #action").val(1);
                            $("#AddBrandForm").trigger("reset");
                            toastr.success('Data Saved Successfully!','Success');
                            brandSelectList();
                        }
                        else {
                            toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                        }
                        $(_this +" .save").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        //console.log(request.responseText);
                        toastr.warning( 'Server Error. Try aging!', 'Warning');
                        $(_this +" .save").text("Save").prop("disabled", false);
                    }
                });
            });


        });

        function productSelectList(){
            $.ajax({
                type: "POST",
                url: "{{route('product_list_show')}}",
                data: "_token={{csrf_token()}}",
                success: function (response) {
                    var element = $("#product_id");
                    element.empty();

                    $.each(response,function(key,value){
                        element.append("<option value='"+value.id+"'>"+value.product_name+"</option>");
                    });
                }
            });
        }

        function brandSelectList(){
            $.ajax({
                type: "POST",
                url: "{{route('product_brand_show')}}",
                data: "_token={{csrf_token()}}",
                success: function (response) {
                    var element = $("#brand_id");
                    element.empty();
                    $.each(response,function(key,value){
                        element.append("<option value='"+value.id+"'>"+value.brand_name+"</option>");
                    });
                }
            });
        }


    </script>

@endsection
