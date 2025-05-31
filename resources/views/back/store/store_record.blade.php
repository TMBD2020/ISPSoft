@extends('layouts.app')

@section('title', 'Store Record')

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

                                    <div class="table-responsive">
                                        <table id="datalist" class="table table-striped table-bordered zero-configuration" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Product Name</th>
                                                <th>Available</th>
                                                <th>Stock In</th>
                                                <th>Stock Out</th>
                                                <th>Damage</th>
                                            </tr>
                                            </thead>
                                        </table>
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
        $(document).ready(function () {

            var table = $('#datalist').DataTable
            ({

                "bProcessing": true,
                "serverSide": true,
                "responsive": true,
                "aaSorting": [[0, 'desc']],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [ {
                    "targets":[2,3,4,5],
                    "orderable": false
                },{
                    "targets":[0,2,3,4,5],
                    className: "text-center"
                } ],
                "ajax": {
                    url: "{{ route('store_record') }}",
                    type: "post",
                    "data":{ _token: "{{csrf_token()}}"},
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function (jsonData) {
//                        for (var i = 0, len = jsonData.data.length; i < len; i++) {
//
//                            jsonData.data[i][3] = '<div class="btn-group align-top" role="group">' +
//                                    '<button id="' + jsonData.data[i][0] + '" class="updateItem edit'+jsonData.data[i][0]+' btn btn-primary btn-sm badge">' +
//                                    '<span class="ft-edit"></span> Edit</button>' +
//                                    '<button  id=' + jsonData.data[i][0] + ' class="deleteItem btn btn-danger btn-sm badge">' +
//                                    '<span class="ft-delete"></span> Del</button>' +
//                                    '</div>';
//                        }
                        return jsonData.data;
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        toastr.warning( 'Server Error. Try again!', 'Warning');
                    }
                }
            });


        });
    </script>

@endsection
