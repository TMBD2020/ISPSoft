@extends('layouts.app')

@section('title', 'Company Setting')

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
                                                       @if (session('error'))
                                                           <div class="alert alert-danger">
                                                               {{ session('error') }}
                                                           </div>
                                                       @endif
                                                       @if (session('success'))
                                                           <div class="alert alert-success">
                                                               {{ session('success') }}
                                                           </div>
                                                       @endif
                                                       <form class="form-horizontal" method="POST" action="#" enctype="multipart/form-data">
                                                           {{ csrf_field() }}

                                                           <div class="row">
                                                               <label for="company_name" class="col-sm-4 control-label">Company Name <span class="text-danger">*</span></label>
                                                               <div class="col-sm-8">
                                                                   <input type="text" name="company_name" id="company_name" class="form-control" value="{{ $setting->name }}" required>
                                                               </div>
                                                           </div>

                                                           <div class="row">
                                                               <label for="contact_number" class="col-sm-4 control-label">Contact Number <span class="text-danger">*</span></label>
                                                               <div class="col-sm-8">
                                                                   <input type="text"   name="contact_number" id="contact_number" class="form-control" value="{{ $setting->mobile }}" required>
                                                               </div>
                                                           </div>

                                                           <div class="row">
                                                               <label for="company_email" class="col-sm-4 control-label">Email Address<span class="text-danger">*</span></label>
                                                               <div class="col-sm-8">
                                                                   <input type="text"   name="company_email" id="company_email" class="form-control" value="{{ $setting->email_id }}" required>
                                                               </div>
                                                           </div>

                                                           <div class="row">
                                                               <label for="contact_address" class="col-sm-4 control-label">Contact Address<span class="text-danger">*</span></label>
                                                               <div class="col-sm-8">
                                                                   <textarea  name="contact_address" id="contact_address" class="form-control"> {{ $setting->address }}</textarea>
                                                               </div>
                                                           </div>

                                                           <div class="row">
                                                               <label for="company_logo" class="col-sm-4 control-label">Logo<span class="text-danger">*</span></label>
                                                               <div class="col-sm-8">
                                                                   <div><img src="{{ $setting->logo }}" width="150"></div>
                                                                   <input type="file"   name="company_logo" id="company_logo" class="form-control">
                                                               </div>
                                                           </div>
                                                           <div class="row">
                                                               <label for="contact_address" class="col-sm-4 control-label"></label>
                                                               <div class="col-sm-8">
                                                                   <button type="submit" class="btn btn-primary">
                                                                       Save
                                                                   </button>
                                                                </div>
                                                           </div>

                                                       </form>

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
    form .row{
        margin-top: 10px;
    }
</style>
@endsection
