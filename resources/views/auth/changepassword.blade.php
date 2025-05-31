@extends('layouts.app')

@section('title', 'Custom Settings')

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
                                                   <div class="col-md-4">
                                                       <div class="card-header">
                                                           <h4>Change Password</h4>
                                                       </div>

                                                       <div class="card-body">
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
                                                           <form class="form-horizontal" method="POST" action="{{ route('changePassword') }}">
                                                               {{ csrf_field() }}

                                                               <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                                                   <label for="new-password" class="control-label">Current Password</label>


                                                                   <input id="current-password" type="password" class="form-control" name="current-password" required>

                                                                   @if ($errors->has('current-password'))
                                                                       <span class="help-block">
                                                                            <strong>{{ $errors->first('current-password') }}</strong>
                                                                        </span>
                                                                   @endif
                                                               </div>

                                                               <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                                                                   <label for="new-password" class="control-label">New Password</label>

                                                                   <input id="new-password" type="password" class="form-control" name="new-password" required>

                                                                   @if ($errors->has('new-password'))
                                                                       <span class="help-block">
                                                                            <strong>{{ $errors->first('new-password') }}</strong>
                                                                        </span>
                                                                   @endif
                                                               </div>

                                                               <div class="form-group">
                                                                   <label for="new-password-confirm" class="control-label">Confirm New Password</label>

                                                                   <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>

                                                               </div>

                                                               <div class="form-group">
                                                                   <div class=" col-md-offset-4">
                                                                       <button type="submit" class="btn btn-primary">
                                                                           Change Password
                                                                       </button>
                                                                   </div>
                                                               </div>
                                                           </form>

                                                       </div>
                                                   </div>
                                                   <div class="col-md-4">
                                                       <div class="card-header">
                                                           <h4>Profile Picture</h4>
                                                       </div>
                                                       <div class="card-body">
                                                           @if (session('error2'))
                                                               <div class="alert alert-danger">
                                                                   {{ session('error2') }}
                                                               </div>
                                                           @endif
                                                           @if (session('success2'))
                                                               <div class="alert alert-success">
                                                                   {{ session('success2') }}
                                                               </div>
                                                           @endif
                                                           <form class="form-horizontal" method="POST" action="{{ route('changePhoto') }}" enctype="multipart/form-data">
                                                               {{ csrf_field() }}

                                                               <div class="form-group">
                                                                   <div>
                                                                       @if( Auth::user()->photo)
                                                                           <img src="{{ asset(Auth::user()->photo) }}" width="150" height="150">
                                                                       @endif
                                                                   </div>
                                                                       <input type="hidden" name="old_photo" value="{{  Auth::user()->photo }}">
                                                                       <input id="new-photo" type="file" class="form-control" name="new_photo" required>


                                                               </div>


                                                               <div class="form-group">
                                                                       <button type="submit" class="btn btn-primary">
                                                                           Change Picture
                                                                       </button>
                                                               </div>
                                                           </form>

                                                       </div>
                                                   </div>

                                                   <div class="col-md-4">
                                                       <div class="card-header">
                                                           <h4>Change Colors</h4>
                                                       </div>
                                                       <div class="card-body">
                                                           @if (session('error3'))
                                                               <div class="alert alert-danger">
                                                                   {{ session('error3') }}
                                                               </div>
                                                           @endif
                                                           @if (session('success3'))
                                                               <div class="alert alert-success">
                                                                   {{ session('success3') }}
                                                               </div>
                                                           @endif
                                                           <form class="form-horizontal" method="POST" action="{{ route('changeColor') }}">
                                                               {{ csrf_field() }}

                                                               <div class="form-group">
                                                                   <input id="clr1" type="text" class="form-control jscolor" name="header_bg_color_1" value="{{ Settings::theme()["header_bg_color_1"] }}">
                                                               </div>

                                                               <div class="form-group">
                                                                   <input id="clr2" type="text" class="form-control jscolor" name="header_bg_color_2" value="{{ Settings::theme()["header_bg_color_2"] }}">
                                                               </div>


                                                               <div class="form-group">
                                                                       <button type="submit" class="btn btn-primary">
                                                                           Save
                                                                       </button>
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
                    </div>
                </section>
                <!--/ Zero configuration table -->
            </div>
        </div>
    </div>
    <!-- END: Content-->


@endsection
