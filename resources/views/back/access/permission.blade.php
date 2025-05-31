@extends('layouts.app')

@section('title', 'Access Permission')

@section('content')
<style>
    table th{
        vertical-align: middle !important;
        font-weight: bold !important;
    }
    #ptable{
        position: relative;
    }
    #laod{
        position: absolute;
        width: 100%;
        top: 50%;
        right: 50%;
    }
</style>

        <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title">@yield("title")</h3>
            </div>
        </div>
        <div class="content-body"><!-- Zero configuration table -->
            <section id="configuration">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">

                                    <form id="PermissionForm" method="post">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="role_id">Choose Role : </label>
                                                <select class="form-control select2" id="role_id" name="role_id" required>
                                                    <option></option>
                                                    @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary save" style="margin-top: 25px;">Search</button>
                                            </div>
                                        </div>

                                    </form>
                                        <br>
                                    <form id="SavePermissionForm" method="post" style="display: none;">
                                        {{ csrf_field() }}
                                       <div id="ptable">
                                           <table class="table table-bordered">
                                               <thead>
                                               <tr>
                                                   <th class="text-center" colspan="2">Module</th>
                                                   <th class="text-center"><label for="read_access">Read<br><input type="checkbox" name="" id="read_access"> </label></th>
                                                   <th class="text-center"><label for="write_access">Write<br><input type="checkbox" name="" id="write_access"> </label></th>
                                                   <th class="text-center"><label for="update_access">Update<br><input type="checkbox" name="" id="update_access"> </label></th>
                                                   <th class="text-center"><label for="delete_access">Delete <br><input type="checkbox" name="" id="delete_access"> </label></th>
                                                   <th class="text-center"><label for="approve_access">Approve <br><input type="checkbox" name="" id="approve_access"> </label></th>
                                               </tr>
                                               </thead>
                                               <tbody class="permission_list">

                                               </tbody>
                                               <tfoot>
                                               <tr>
                                                   <th colspan="7">
                                                       <button class="btn btn-primary saves" type="submit">Save</button>
                                                   </th>
                                               </tr>
                                               </tfoot>
                                           </table>
                                           <div id="mylenth"></div>
                                       </div>
</form>
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
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', "#read_access", function () {
                if($(this).is(":checked")){
                    $(".permission_list").find("input[name='read_access[]']").val(1);
                    $(".permission_list").find("input[name='read_access_box']").attr("checked","checked");
                }else{
                    $(".permission_list").find("input[name='read_access[]']").val(0);
                    $(".permission_list").find("input[name='read_access_box']").removeAttr("checked");
                }
            });

            $(document).on('click', "#write_access", function () {
                if($(this).is(":checked")){
                    $(".permission_list").find("input[name='write_access[]']").val(1);
                    $(".permission_list").find("input[name='write_access_box']").attr("checked","checked");
                }else{
                    $(".permission_list").find("input[name='write_access[]']").val(0);
                    $(".permission_list").find("input[name='write_access_box']").removeAttr("checked");
                }
            });

            $(document).on('click', "#update_access", function () {
                if($(this).is(":checked")){
                    $(".permission_list").find("input[name='update_access[]']").val(1);
                    $(".permission_list").find("input[name='update_access_box']").attr("checked","checked");
                }else{
                    $(".permission_list").find("input[name='update_access[]']").val(0);
                    $(".permission_list").find("input[name='update_access_box']").removeAttr("checked");
                }
            });

            $(document).on('click', "#delete_access", function () {
                if($(this).is(":checked")){
                    $(".permission_list").find("input[name='delete_access[]']").val(1);
                    $(".permission_list").find("input[name='delete_access_box']").attr("checked","checked");
                }else{
                    $(".permission_list").find("input[name='delete_access[]']").val(0);
                    $(".permission_list").find("input[name='delete_access_box']").removeAttr("checked");
                }
            });

            $(document).on('click', "#approve_access", function () {
                if($(this).is(":checked")){
                    $(".permission_list").find("input[name='approve_access[]']").val(1);
                    $(".permission_list").find("input[name='approve_access_box']").attr("checked","checked");
                }else{
                    $(".permission_list").find("input[name='approve_access[]']").val(0);
                    $(".permission_list").find("input[name='approve_access_box']").removeAttr("checked");
                }
            });

            $(document).on('click', "input[type='checkbox']", function () {
                var id = $(this).attr("id");
                if($(this).is(":checked")){
                    $("."+id).val(1);
                }else{
                    $("."+id).val(0);
                }
            });

            $(document).on('submit', "#SavePermissionForm", function (e) {
                e.preventDefault();

                $(".saves").text("Saving...").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_permission') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        if (response == '') {
                            toastr.success('Saved Successfully!','Success');
                        }
                        else {
                            toastr.warning( 'Data Cannot Saved. Try again!', 'Warning');
                        }
                        $(".saves").text("Save").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try again!', 'Warning');
                        $(".saves").text("Save").prop("disabled", false);
                    }
                });
            });

            $(document).on('submit', "#PermissionForm", function (e) {
                e.preventDefault();
                $(".save").html('<i class="ft-loader"></i> Searching...').prop("disabled",true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('permission') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (response)
                    {
                        $("#write_access").prop("checked",false);$("#read_access").prop("checked",false);$("#delete_access").prop("checked",false);$("#update_access").prop("checked",false);$("#approve_access").prop("checked",false);
                        var html="";
                        if(response!=0){
                            var json = JSON.parse(response);
                            var module = json.module;
                            var subModule=json.subModule;
                            var permissions=json.permissions;
                            if(permissions.length>0){
                                $("#SavePermissionForm").show();
                                $(".permission_list").empty();

                                var sbm = "",row= "",sb=0,i=2;
                                $.each(module, function(key2,mvalue){
                                    $.each(subModule, function(key3,svalue){
                                        if(mvalue.id==svalue.ref_module_id){
                                            sb=1;
                                            row="rowspan='"+ (i++) +"'";
                                            sbm+=
                                                    '<tr>' +
                                                    '<td>'+svalue.sub_module_name+'</td>' ;

                                            $.each(permissions, function(key4,access){
                                                if(access.sub_module_id==svalue.id){
                                                    sbm+=
                                                            '<input type="hidden" name="read_access[]" class="'+svalue.ref_module_id+'read_access'+svalue.id+'"  value="'+access.read_access+'">'+
                                                            '<input type="hidden" name="write_access[]"  class="'+svalue.ref_module_id+'write_access'+svalue.id+'" value="'+access.write_access+'">'+
                                                            '<input type="hidden" name="update_access[]"  class="'+svalue.ref_module_id+'update_access'+svalue.id+'" value="'+access.update_access+'">'+
                                                            '<input type="hidden" name="delete_access[]"  class="'+svalue.ref_module_id+'delete_access'+svalue.id+'" value="'+access.delete_access+'">'+
                                                            '<input type="hidden" name="approve_access[]"  class="'+svalue.ref_module_id+'approve_access'+svalue.id+'" value="'+access.approve_access+'">';
                                                    sbm+=
                                                            '<input type="hidden" name="permission[]" value="'+access.id+'">' +
                                                            '<td class="text-center"><input name="read_access_box" type="checkbox" id="'+svalue.ref_module_id+'read_access'+svalue.id+'" '+ (getAccess(access.read_access))+'> </td>' +
                                                            '<td class="text-center"><input name="write_access_box" type="checkbox" id="'+svalue.ref_module_id+'write_access'+svalue.id+'" '+(getAccess(access.write_access))+'></td>' +
                                                            '<td class="text-center"><input name="update_access_box" type="checkbox" id="'+svalue.ref_module_id+'update_access'+svalue.id+'" '+(getAccess(access.update_access))+'></td>' +
                                                            '<td class="text-center"><input name="delete_access_box" type="checkbox" id="'+svalue.ref_module_id+'delete_access'+svalue.id+'" '+(getAccess(access.delete_access))+'></td>' ;
                                                    if(svalue.has_approve==1){
                                                        sbm+='<td class="text-center"><input name="approve_access_box" type="checkbox" id="'+svalue.ref_module_id+'approve_access'+svalue.id+'" '+(getAccess(access.approve_access))+'></td>' ;

                                                    }else{
                                                        sbm+='<td class="text-center"><input name="approve_access_box" type="checkbox"  hidden name="approve_access[]" id="'+svalue.ref_module_id+'approve_access'+svalue.id+'" '+(getAccess(access.approve_access))+'></td>' ;
                                                    }
                                                }
                                            });
                                            html+='</tr>';
                                        }
                                    });
                                    html+=
                                            '<tr>' +
                                            '<td '+row+'>'+mvalue.module_name+'</td>';
                                    if(sb==0){
                                        $.each(permissions, function(key4,access){
                                            if(access.module_id==mvalue.id){
                                                html+=
                                                        '<input type="hidden" name="read_access[]" class="'+mvalue.id+'read_access0"   value="'+access.read_access+'">'+
                                                        '<input type="hidden" name="write_access[]"  class="'+mvalue.id+'write_access0"  value="'+access.write_access+'">'+
                                                        '<input type="hidden" name="update_access[]"  class="'+mvalue.id+'update_access0"  value="'+access.update_access+'">'+
                                                        '<input type="hidden" name="delete_access[]"  class="'+mvalue.id+'delete_access0"  value="'+access.delete_access+'">'+
                                                        '<input type="hidden" name="approve_access[]"  class="'+mvalue.id+'approve_access0" value="'+access.approve_access+'" >';
                                                html+=
                                                        '<td></td>' +
                                                        '<input type="hidden" name="permission[]" value="'+access.id+'">' +
                                                        '<td class="text-center"><input name="read_access_box" type="checkbox"  id="'+mvalue.id+'read_access0"  '+(getAccess(access.read_access))+'></td>' +
                                                        '<td class="text-center"><input name="write_access_box" type="checkbox"  id="'+mvalue.id+'write_access0"  '+(getAccess(access.write_access))+'></td>' +
                                                        '<td class="text-center"><input name="update_access_box" type="checkbox"  id="'+mvalue.id+'update_access0" '+(getAccess(access.update_access))+'></td>' +
                                                        '<td class="text-center"><input name="delete_access_box" type="checkbox"  id="'+mvalue.id+'delete_access0" '+(getAccess(access.delete_access))+'></td>' ;
                                                if(mvalue.has_approve==1){
                                                    html+='<td class="text-center"><input name="approve_access_box" type="checkbox"  id="'+mvalue.id+'approve_access0"   '+(getAccess(access.approve_access))+'></td>' ;
                                                }else{
                                                    html+='<td class="text-center"><input name="approve_access_box" type="checkbox" hidden  id="'+mvalue.id+'approve_access0" '+(getAccess(access.approve_access))+'></td>' ;
                                                }
                                            }
                                        });
                                    }
                                    html+='</tr>' ;
                                    html+=sbm;
                                    sb=0;
                                    i=2;
                                    sbm = "";
                                    row = "";
                                });

                                $(".permission_list").html(html);
                            }else{
                                $("#SavePermissionForm").hide();
                            }
                        }else{
                            $("#SavePermissionForm").hide();
                        }
                        $(".save").text("Search").prop("disabled", false);
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try again!', 'Warning');
                        $(".save").html('Search').prop("disabled",false);
                    }
                });
            });
        });

        function getAccess(access){
            var checked = access==1?'checked':'';
            return checked;
        }
    </script>

@endsection
