 <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light">
      <div class="navbar-wrapper">
        <div class="navbar-container content">
          <div class="collapse navbar-collapse show" id="navbar-mobile">
            <ul class="nav navbar-nav mr-auto float-left">
              <li class="nav-item d-none d-md-block"><a style="    font-size: 20px;margin-top: 6px; display: none;" class="headTitle nav-link nav-link-expand" href="#">@yield("title")</a></li>

              <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
              <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
              <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
@if(Auth::user()->user_type!="reseller" and Auth::user()->user_type!="client")
              <li class="nav-item dropdown navbar-search"><a class="nav-link dropdown-toggle hide" data-toggle="dropdown" href="#"><i class="ficon ft-search"></i></a>
                <ul class="dropdown-menu">
                  <li class="arrow_box">
                    <form>
                      <div class="input-group search-box">
                        <div class="position-relative has-icon-right full-width">
                          <input class="form-control" id="search" type="text" placeholder="Search here...">
                          <div class="form-control-position navbar-search-close"><i class="ft-x"></i></div>
                        </div>
                      </div>
                    </form>
                  </li>
                </ul>
              </li>
                <li class="nav-item d-none d-md-block" style="line-height: none !important;">
                    <a class="nav-link" href="{{ route("tickets") }}" style="font-size: 15px;">
                        <i class="ficon ft-navigation" style="font-size: 15px;"></i> Ticket
                    </a>
                </li>
@endif
            </ul>
              {{--@if(Auth::user()->user_type!="reseller" and Auth::user()->user_type!="client")--}}
            <ul class="nav navbar-nav float-right">
              <li class="nav-item">
                  <a class="nav-link nav-link-label" href="javascript:void(0)">
                      <span class="badge badge-pill badge-sm badge-danger badge-glow ft-mail"> {{ Settings::settings()['sms_balance'] }}</span>
                      {{--&#2547;--}}
                  </a>
              </li>
            </ul>
              {{--@endif--}}
            <ul class="nav navbar-nav float-right">

                {{--<li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown" aria-expanded="false"><i class="ficon ft-bell bell-shake" id="notification-navbar-link"></i><span class="badge badge-pill badge-sm badge-danger badge-up badge-glow">5</span></a>--}}
                {{--<ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">--}}
                  {{--<div class="arrow_box_right">--}}
                    {{--<li class="dropdown-menu-header">--}}
                      {{--<h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span></h6>--}}
                    {{--</li>--}}
                    {{--<li class="scrollable-container media-list w-100 ps"><a href="javascript:void(0)">--}}
                        {{--<div class="media">--}}
                          {{--<div class="media-left align-self-center"><i class="ft-share info font-medium-4 mt-2"></i></div>--}}
                          {{--<div class="media-body">--}}
                            {{--<h6 class="media-heading info">New Order Received</h6>--}}
                            {{--<p class="notification-text font-small-3 text-muted text-bold-600">Lorem ipsum dolor sit amet!</p><small>--}}
                              {{--<time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">3:30 PM</time></small>--}}
                          {{--</div>--}}
                        {{--</div></a><a href="javascript:void(0)">--}}
                        {{--<div class="media">--}}
                          {{--<div class="media-left align-self-center"><i class="ft-save font-medium-4 mt-2 warning"></i></div>--}}
                          {{--<div class="media-body">--}}
                            {{--<h6 class="media-heading warning">New User Registered</h6>--}}
                            {{--<p class="notification-text font-small-3 text-muted text-bold-600">Aliquam tincidunt mauris eu risus.</p><small>--}}
                              {{--<time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">10:05 AM</time></small>--}}
                          {{--</div>--}}
                        {{--</div></a><a href="javascript:void(0)">--}}
                        {{--<div class="media">--}}
                          {{--<div class="media-left align-self-center"><i class="ft-repeat font-medium-4 mt-2 danger"></i></div>--}}
                          {{--<div class="media-body">--}}
                            {{--<h6 class="media-heading danger">New Purchase</h6>--}}
                            {{--<p class="notification-text font-small-3 text-muted text-bold-600">Lorem ipsum dolor sit ametest?</p><small>--}}
                              {{--<time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Yesterday</time></small>--}}
                          {{--</div>--}}
                        {{--</div></a><a href="javascript:void(0)">--}}
                        {{--<div class="media">--}}
                          {{--<div class="media-left align-self-center"><i class="ft-shopping-cart font-medium-4 mt-2 primary"></i></div>--}}
                          {{--<div class="media-body">--}}
                            {{--<h6 class="media-heading primary">New Item In Your Cart</h6><small>--}}
                              {{--<time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Last week</time></small>--}}
                          {{--</div>--}}
                        {{--</div></a><a href="javascript:void(0)">--}}
                        {{--<div class="media">--}}
                          {{--<div class="media-left align-self-center"><i class="ft-heart font-medium-4 mt-2 info"></i></div>--}}
                          {{--<div class="media-body">--}}
                            {{--<h6 class="media-heading info">New Sale</h6><small>--}}
                              {{--<time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Last month</time></small>--}}
                          {{--</div>--}}
                        {{--</div></a><div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></li>--}}
                    {{--<li class="dropdown-menu-footer"><a class="dropdown-item info text-right pr-1" href="javascript:void(0)">Read all</a></li>--}}
                  {{--</div>--}}
                {{--</ul>--}}
              {{--</li>--}}

                <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                  <span class="avatar avatar-online"><img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset("app-assets/images/avatar.png") }} " alt="avatar"></span></a>
                <div class="dropdown-menu dropdown-menu-right">
                  <div class="arrow_box_right">
                      <a class="dropdown-item text-center" href="javascript:void(0)">
                          <span class="avatar avatar-online">
                              <img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset("app-assets/images/avatar.png") }} " alt="avatar">
                          </span>
                      </a>
                    <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="{{ route("change-password") }}"><i class="ft-settings"></i> Settings</a>
                        <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="ft-power"></i> Logout</a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                      </form>
                  </div>
                </div>
              </li>
            </ul>

          </div>
        </div>
      </div>
    </nav>
    <!-- END: Header-->

