@extends('layouts.app')

@section('title', 'Sidebar Ordering')

@section('content')


    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2">
                    <h3 class="content-header-title">@yield("title")</h3>
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
                                        <div class="tab-content pt-1">
                                            <div role="tabpanel" class="tab-pane active" id="DataList" aria-expanded="true" aria-labelledby="base-tab1">
                                               <div class="row">
                                                   <div class="col-md-8">
                                                       <ul class="list-unstyled" id="page_list">
                                                           @foreach($modules as $val)
                                                           <li id="{{$val->id}}">{{$val->module_name}}</li>
                                                           @endforeach
                                                       </ul>
                                                   </div>
                                               </div>
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
    #page_list li
    {
        padding:16px;
        background-color:#f9f9f9;
        border:1px dotted #ccc;
        cursor:move;
        margin-top:12px;
        line-height: 0;
    }
    #page_list li.ui-state-highlight
    {
        padding:24px;
        background-color:#ffffcc;
        border:1px dotted #ccc;
        cursor:move;
        margin-top:12px;
    }
</style>

@endsection


@section("page_script")
    <script src="{{ asset("app-assets/js/scripts/ui/jquery-ui.js")}}"></script>
    <script>
        $(document).ready(function(){
            $( "#page_list" ).sortable({
                placeholder : "ui-state-highlight",
                update  : function(event, ui)
                {
                    var page_id_array = new Array();
                    $('#page_list li').each(function(){
                        page_id_array.push($(this).attr("id"));
                    });
                    $.ajax({
                        url:"{{route("sidebar-order-save")}}",
                        method:"POST",
                        data:{page_id_array:page_id_array,_token:"{{csrf_token()}}"},
                        success:function(data)
                        {
                            console.log(data);
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            toastr.warning( 'Server Error. Try again!', 'Warning');
                        }
                    });
                }
            });

        });
    </script>
@endsection
