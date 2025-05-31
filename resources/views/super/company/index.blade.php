@extends('layouts.app')

@section('title', 'Company')

@section('content')


    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <h3 class="content-header-title">@yield('title')</h3>
                </div>
                <div class="content-header-right col-md-8 col-12">
                    <ul class="nav nav-tabs float-md-right">
                        <li class="nav-item">
                            <select class="form-control select2-icons loadCompany" onchange="loadCompany()">
                                <option value="1" data-icon="check-circle">Active Company</option>
                                <option value="2" data-icon="times">Pending Company</option>
                                <option value="3" data-icon="exclamation">Inactive Company</option>
                            </select>
                        </li>
                        <li class="nav-item hidden">
                            <a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="tab1"
                                href="#home" aria-expanded="false">Home</a>
                            <a class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                                href="#operation" aria-expanded="false">New</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="content-body"><!-- Zero configuration table -->
                <section id="configuration">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard">

                                        <div class="tab-content pt-1">
                                            <div role="tabpanel" class="tab-pane active" id="home" aria-expanded="true"
                                                aria-labelledby="base-tab1">

                                                <div class="table-responsive">
                                                    <table id="datalist" class="display" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>SL</th>
                                                                <th>Name</th>
                                                                <th>Contact</th>
                                                                <th>Approved Date</th>
                                                                <th>Registration Date</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="tab-pane" id="operation" aria-labelledby="base-tab2"></div>
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

@section('page_script')

    <script type="text/javascript">
        $(document).ready(function() {

            loadCompany();
            $("#operation").empty();

            $(document).on('click', '.mymodal', function() {
                var element = $(this);
                var del_id = element.attr("id");
                $("#operation").empty();
                $.ajax({
                    type: "POST",
                    url: "{{ route('super.company_profile') }}",
                    data: {
                        id: del_id,
                        tab:  element.attr("tab"),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $("[href='#operation']").tab("show");
                        $("#operation").html(response);
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        $("[href='#operation']").tab("hide");
                    }
                });
            });
            $(document).on('click', '.back', function() {
                $("[href='#home']").tab("show");
                $("[href='#operation']").tab("hide");
                $("#operation").empty();
            });
        });


        function loadCompany() {
            console.log("table")
            $('#datalist').DataTable({
                "lengthMenu": [
                    [50, 100, 200, -1],
                    [50, 100, 200, "All"]
                ],
                destroy: true,
                "bProcessing": true,
                "serverSide": true,
                "responsive": false,
                "aaSorting": [
                    [3, 'asc']
                ],
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [{
                    "targets": [0, 1, 2, 5, 6],
                    "orderable": false
                }, {
                    "targets": [0, 3, 4, 5, 6],
                    className: "text-center"
                }],
                "ajax": {
                    url: "{{ route('super.client_datalist') }}",
                    type: "post",
                    "data": {
                        _token: "{{ csrf_token() }}",
                        company_status: $('.loadCompany').val()
                    },
                    "aoColumnDefs": [{
                        'bSortable': false
                    }],

                    "dataSrc": function(jsonData) {

                        return jsonData.data;
                    },
                    error: function(request, status, error) {
                        console.log(request.responseText);
                        toastr.warning('Server Error. Try aging!', 'Warning');
                    }
                }
            });
        }
    </script>
@endsection
