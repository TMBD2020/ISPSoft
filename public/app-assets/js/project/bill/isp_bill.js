/**
 * Created by Ali Akbar on 10/13/2022.
 */
var activeNav='';
function all_bill_table(d){
    blockLoad();
    table_all_bill = $('#all_bill_table').DataTable
    ({
        "destroy": true,
        "lengthMenu": [100, 200, 500, 1000],
                "pageLength": 200, 
        // "lengthChange": false,
        // "bPaginate": false,
        // "bLengthChange": false,
        // "bFilter": true,
        // "bInfo": false,
        "bProcessing": true,
        "serverSide": true,
        "responsive": false,
        "aaSorting": [[0, 'asc']],
        "scrollX": true,
        "scrollCollapse": true,
        "columnDefs": [ {
            "targets":[1,2,3,4,5,6,7],
            "orderable": false
        },{
            "targets":[0,2,3,4,7],
            className: "text-center"
        },{
            "targets":[6],
            className: "text-right"
        } ],
        "ajax": {
            url: baseUrl+'/client_all_bill',
            type: "post",
            "data":{
                _token: _token,
                date_filter: d==1?1:0,
                filter_type: $("#filter_type").val(),
                date_from: $("#date_from").val(),
                date_to: $("#date_to").val()
            },
            "aoColumnDefs": [{
                'bSortable': false
            }],

            "dataSrc": function (jsonData) {
                console.log(jsonData.q);
                unblockLoad();
                return jsonData.data;
            },
            error: function (request, status, error) {
                unblockLoad();
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
            },
            beforeSend: function () {
                blockLoad();
            },
            complete: function () {
                unblockLoad();
            }
        }
        
    });

//            table_all_bill.on( 'order.dt search.dt', function () {
//                table_all_bill.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
//                    cell.innerHTML = i+1;
//                } );
//            } ).draw();
}

function pendingBill(){
    table_unpaid_bill = $('#unpaid_bill_table').DataTable
    ({
        // "lengthChange": false,
        // "bPaginate": false,
        // "bLengthChange": false,
        // "bFilter": true,
        // "bInfo": false,
        "lengthMenu": [100, 200, 500, 1000],
        "pageLength": 200, 
        "destroy": true,
        "bAutoWidth": false,
        "bProcessing": true,
        "serverSide": true,
        "responsive": false,
        "aaSorting": [[0, 'asc']],
        "scrollX": true,
        "scrollCollapse": true,
        "columnDefs": [{
            "targets":[1,2,3,4,5],
            "orderable": false
        },{
            "targets":[0,3,2,7],
            className: "text-center"
        },{
            "targets":[5,6],
            className: "text-right"
        } ],
        "ajax": {
            url: baseUrl+'/client_unpaid_bill',
            type: "post",
            "data":{ _token: _token},
            "aoColumnDefs": [{
                'bSortable': false
            }],

            "dataSrc": function (jsonData) {
                return jsonData.data;
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
            }
        }
    });

//            table_unpaid_bill.on( 'order.dt search.dt', function () {
//                table_unpaid_bill.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
//                    cell.innerHTML = i+1;
//                } );
//            } ).draw();
}

function todayBillCollection(){
    table_today_collection = $('#ToCollist').DataTable
    ({
        "lengthChange": false,
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,

        "retrieve": true,
        "bAutoWidth": false,
        "bProcessing": true,
        "serverSide": true,
        "responsive": false,
        "aaSorting": [[1, 'desc']],
        "scrollX": true,
        "scrollCollapse": true,
        "columnDefs": [ {
            "targets":[0,2,3,4,5,6],
            "orderable": false
        },{
            "targets":[0,2,6],
            className: "text-center"
        },{
            "targets":[4,5],
            className: "text-right"
        } ],
        "ajax": {
            url: baseUrl+'/today_client_collection',
            type: "post",
            "data":{ _token: _token},
            "aoColumnDefs": [{
                'bSortable': false
            }],

            "dataSrc": function (jsonData) {
//                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
//                            jsonData.data[i][9] = '<div class="btn-group align-top" role="group">' +
//                                    '<button id="' + jsonData.data[i][0] + '" class="'+jsonData.data[i][0]+' btn btn-info btn-sm badge">' +
//                                    '<i class="la la-eye"></i></button>' +
//                                    '</div>';
//                        }
                return jsonData.data;
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
            }
        }
    });
    table_today_collection.on( 'order.dt search.dt', function () {
        table_today_collection.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
}

function allBillCollection(){

    table_all_collection = $('#all_collection_table').DataTable
    ({
        "retrieve": true,
        "lengthChange": false,
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "bProcessing": true,
        "serverSide": true,
        "responsive": false,
        "aaSorting": [[0, 'desc']],
        "scrollX": true,
        "scrollCollapse": true,
        "columnDefs": [ {
            "targets":[0,2,3,4,5,6],
            "orderable": false
        },{
            "targets":[0,2,6],
            className: "text-center"
        },{
            "targets":[4,5],
            className: "text-right"
        } ],
        "ajax": {
            url: baseUrl+'/client_all_collected_bill',
            type: "post",
            "data":{
                _token      : _token,
                date_from   : $("#all_collection_date_from").val(),
                date_to     : $("#all_collection_date_to").val()
            },
            "aoColumnDefs": [{
                'bSortable': false
            }],

            "dataSrc": function (jsonData) {
                return jsonData.data;
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
            }
        }
    });

    table_all_collection.on( 'order.dt search.dt', function () {
        table_all_collection.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
}

$(document).ready(function () {


    all_bill_table();

    $(document).on('submit', "#DataForm", function (e) {
        e.preventDefault();

        $(".save").text("Saving...").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: baseUrl+'/client_bill_collect',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            success: function (response)
            {
                console.log(response)
                $(".save").text("Save").prop("disabled", false);
                if (response == 1) {
                    $("#DataForm").trigger("reset");
                    toastr.success('Data Saved Successfully!','Success');
                    $("[href='#allBill']").tab("show");
                    table_unpaid_bill.ajax.reload();
                    table_today_collection.ajax.reload();
                    table_all_collection.ajax.reload();
                    table_all_bill.ajax.reload();
                }else if(response == 101){
                    toastr.warning( 'Bill already receive!', 'Warning');
                }
                else {
                    toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
                $(".save").text("Save").prop("disabled", false);
            }
        });
    });

    $(document).on('submit', "#updateMobileForm", function (e) {
        e.preventDefault();
        var element="#updateMobileForm ";
       var new_mobile= $("#new_mobile").val();
        $(element+".save").text("Saving...").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: baseUrl+'/client_bill_mobile_update',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            success: function (response)
            {
                console.log(response)
                $(element+".save").text("Save").prop("disabled", false);
                if (response) {
                    $("#updateMobileModal").modal("hide");
                    $("#updateMobileForm").trigger("reset");
                    $("#mobile_id").val("");
                    $("#mobile").val(new_mobile);
                    toastr.success('Data Saved Successfully!','Success');
                }
                else {
                    toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
                $(element+".save").text("Save").prop("disabled", false);
                $("#mobile_id").val("");
            }
        });
    });

    $(document).on('click', '.open', function () {
        activeNav=$('.nav-tabs').find('.active').attr('href');
        var element = $(this);
        var info = 'id=' + element.attr("id") +"&_token="+_token;
        //alert(info)
        $(element).html('<i class="ft-loader"></i>').prop("disabled",true);
        $.ajax({
            type: "POST",
            url: baseUrl+"/client_bill_details",
            data: info,
            success: function (response) {
               console.log(response)
                $(element).html('<i class="la la-money"></i>').prop("disabled",false);
                if(response!=0){
                    $("#tabOption").text("Bill Receive");
                    $(".operation_type").text("Update");
                    $("[href='#operation']").tab("show");
                    var json = (response);
                    $("#action").val(2);
                    $("#id").val(element.attr("id"));
                    $("#mobile_id").val(element.attr("id"));
                    $("#client").val(json.client_initial_id+" :: "+ json.client_name);
                    $("#payable,#receive").val(json.payable_amount);
                    $("#client_initial_id").val(json.client_initial_id);
                    $("#mobile").val(json.mobile);
                    $("#receive").attr("min",json.payable_amount);
                } else {
                    toastr.warning( 'Server Error. Try aging!', 'Warning');
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
                $(element).html('<i class="la la-money"></i>').prop("disabled",false);
            }
        });
    });

    $(document).on('click', '.takeUpdateMobile', function () {
        $("#updateMobileModal").modal("show");
        $("#NodeIDForm").trigger("reset");
    });

    $(document).on('click', '.backL', function () {
        $("[href='"+activeNav+"']").tab("show");
    });

    $(document).on('click', '.viewBill', function () {
        var element = $(this);
        var id = element.attr("id");
         activeNav=$('.nav-tabs').find('.active').attr('href');

        var info = 'id=' + id +"&_token="+_token;
        element.html('<i class="ft-loader"></i>').prop("disabled",true);
        $.ajax({
            type: "POST",
            url: baseUrl+"/isp_client_bill_history",
            data: info,
            success: function (response) {
                //console.log(response)
                element.html('<i class="la la-eye"></i>').prop("disabled",false);
                 //  if(response.length>0){
                    $("[href='#bill_view']").tab("show");
                    $(".clientBillHistory").html(response)

        //             var popup; 
        //             var width = 800;
        //             var height = 600;
        //             var left = (screen.width / 2) - (width / 2);
        //             var top = (screen.height / 2) - (height / 2);
        //             popup = window.open(
        //                 "", 
        //                 "newWindow", 
        //                 `width=${width},height=${height},top=${top},left=${left},status=no,toolbar=no,menubar=no,scrollbars=yes,resizable=yes`
        //             );
		// if(popup){
        //     popup.document.open();
		// 	popup.document.write(`<html><head><title>ISP Billing History</title>   <style></style></head><body>${response}</body></html>`);
        //     popup.document.close();
		// }	
              //  }else{
               //     toastr.warning( 'Server Error. Try aging!', 'Warning');
             //   }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
            }
        });
    });

    $(document).on('click', '.dueSMS', function () {

        $('#sms_view textarea').text('');
        var element = $(this);
        var id = element.attr("id");
        var info = 'id=' + id +"&_token="+_token;
        element.html('<i class="ft-loader"></i>').prop("disabled",true);
        $.ajax({
            type: "POST",
            url: baseUrl+"/isp_client_due_sms",
            data: info,
            success: function (response) {
                element.html('<i class="la la-envelope"></i>').prop("disabled",false);
                $("#sms_view").modal("show");
                var html='';
                var json = response;
                var name=element.closest("tr").find("td:nth-child(2)").html();
                $("#sms_view .clientName").html(name.replace("<br>"," :: "))
                $("#sms_view .sent_to").val(json.sent_to)
                $("#sms_view textarea").text(json.sms)

            },
            error: function (request, status, error) {
                element.html('<i class="la la-envelope"></i>').prop("disabled",false);
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
                //$(".save").text("Save").prop("disabled", false);
            }
        });
    });
    
  
        $(document).on('click', '.delmyb', function () {
           
            var element = $(this);
            var id = element.attr("id");
            var info = 'ids=' + id + "&_token="+_token;

            if (confirm("Are you sure to delete this?")) {
                $.ajax({
                    type: "POST",
                    url: baseUrl+"/isp_client_bill_era",
                    data: info,
                    success: function (response) {
                        //console.log(response)
                        if (response == 1) {
                            element.closest("tr").remove();
                            toastr.success('Successfully deleted', 'Success');
                        } else {
                            toastr.warning('Server Error. Try aging!', 'Warning');
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                        //$(".save").text("Save").prop("disabled", false);
                    }
                });
            }
            return false;
        });
    
    $(document).on('click', '.commitDate', function () {
        var element = $(this);
        var id = element.attr("id");
        var mobile = element.closest("tr").find("td:nth-child(3)").html();
        var name = element.closest("tr").find("td:nth-child(2)").html();
        name = name.split("<br>")[1]
        $("#commitmentDateModal #mobile").val(mobile);
        $("#commitmentDateModal #client_id").val(id);
        $("#commitmentDateModal #name").val(name);
        $("#commitmentDateModal").modal("show");

    });

    $(document).on('submit', "#DueSMSSent", function (e) {
        e.preventDefault();
        var element= this;
        $(".sent",element).text("Sending...").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: baseUrl+"/isp_client_due_sms_save",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            success: function (response)
            {
                $(".sent",element).text("Sent SMS").prop("disabled", false);
                if (response == 1) {
                    $("#sms_view").modal("hide");
                    toastr.success( 'SMS successully sent', 'Success');
                }
                else {
                    toastr.warning( 'SMS senting failed. Try aging!', 'Warning');
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
                $(".save").text("Save").prop("disabled", false);
            }
        });
    });

    $(document).on('submit', "#UpdateCommitmentDate", function (e) {
        e.preventDefault();

        var element= this;
        $(".sent",element).text("Updating...").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: baseUrl+"/isp_commitment_date_update",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            success: function (response)
            {
                console.log(response)
                $(".sent",element).text("Update").prop("disabled", false);
                if (response == 1) {
                    $("#commitmentDateModal").modal("hide");
                    toastr.success( 'Successfully Updated', 'Success');
                }
                else {
                    toastr.warning( 'Update failed. Try aging!', 'Warning');
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
                $(".save").text("Update").prop("disabled", false);
            }
        });
    });
    
   
    
    
    $(document).on('click', '.otherBill', function () {
        $("#OtherBillForm").trigger("reset");
        activeNav=$('.nav-tabs').find('.active').attr('href');
        var element = $(this);
       var client= element.closest('tr').find("td:nth-child(2)").find('font').html();
        client= client.split('<br>');
        $("#tabOption").text("Create Other Bill");
        $("[href='#createOtherBill']").tab("show");
        $("#action2").val(2);
        $("#id2").val(element.attr("id"));
        $("#client2").val(client[0]+" :: "+ client[1]);
        $("#client_initial_id2").val(client[0]);
    });
    
    
    $(document).on('submit', "#OtherBillForm", function (e) {
        e.preventDefault();
        var element= this;
        $(".save",element).text("Creating...").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: baseUrl+'/create_client_bill_other',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            success: function (response)
            {
                console.log(response)
                $(".save",element).text("Create").prop("disabled", false);
                if (response.status=="success") {
                    $("#OtherBillForm").trigger("reset");
                    toastr.success('Data Saved Successfully!','Success');
                    $("[href='#allBill']").tab("show");
                    table_unpaid_bill.ajax.reload();
                    table_today_collection.ajax.reload();
                    table_all_collection.ajax.reload();
                    table_all_bill.ajax.reload();
                }else if(response == 101){
                    toastr.warning( 'Bill already receive!', 'Warning');
                }
                else {
                    toastr.warning( 'Data Cannot Saved. Try aging!', 'Warning');
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
                $(".save",element).text("Create").prop("disabled", false);
            }
        });
    });

    $(document).on('click', '.responsiblep', function () {
        $(".client_id").val("");
        $(".client").val("");
        $("#billing_responsible").val("").trigger("change");
        var element = $(this);
        var id = element.attr("id");
        var info = 'id=' + id +"&_token="+_token;
        element.html('<i class="ft-loader"></i>').prop("disabled",true);
        $.ajax({
            type: "POST",
            url: baseUrl+"/isp_bill_responsible_person",
            data: info,
            success: function (response) {
                element.html('<i class="la la-user"></i>').prop("disabled",false);
                $("#responisblePersonModal").modal("show");
                $(".client_id").val(response.client_id);
                $(".client").html(response.client_name);
                $("#billing_responsible").val(response.responsible_person).trigger("change");
            },
            error: function (request, status, error) {
                element.html('<i class="la la-user"></i>').prop("disabled",false);
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
            }
        });
    });
    $(document).on('submit', "#responisblePersonForm", function (e) {
        e.preventDefault();

        var element= this;
        $("button",element).text("Saving...").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: baseUrl+"/isp_bill_responsible_person_update",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            success: function (response)
            {
                $("button",element).text("Save").prop("disabled", false);
                if (response == 1) {
                    $("#responisblePersonModal").modal("hide");
                    toastr.success( 'Successfully Updated', 'Success');
                }
                else {
                    toastr.warning( 'Update failed. Try aging!', 'Warning');
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
                $(".button").text("Save").prop("disabled", false);
            }
        });
    });


    
    $(document).on('click', '.print', function () {
        var element = $(this);
        var id = element.attr("id");
        var ttype = element.attr("ttype");
        element.html('<i class="ft-loader"></i>').prop("disabled",true);
        $.ajax({
            type: "POST",
            url: baseUrl+"/isp-bill-print",
            data: {
                id: id,
                ttype: ttype,
                _token: _token,
            },
            success: function (response) {
                //console.log(response)
               element.html('<i class="ft-printer"></i>').prop("disabled",false);
             

                    var popup; 
                    var width = 800;
                    var height = 600;
                    var left = (screen.width / 2) - (width / 2);
                    var top = (screen.height / 2) - (height / 2);
                    popup = window.open(
                        "", 
                        "newWindow", 
                        `width=${width},height=${height},top=${top},left=${left},status=no,toolbar=no,menubar=no,scrollbars=yes,resizable=yes`
                    );
		if(popup){
            popup.document.open();
			popup.document.write(`<html><head><title>Print</title>   <style></style></head><body>${response}</body></html>`);
            popup.document.close();
		}	
            },
            error: function (request, status, error) {
                element.html('<i class="ft-printer"></i>').prop("disabled",false);
                console.log(request.responseText);
                toastr.warning( 'Server Error. Try aging!', 'Warning');
            }
        });
    });

});

