
  <style>
      .main-menu-content ul ,.main-menu-content ul li ul a {
          font-size: 12px;
      }
  </style>
   <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true" data-img="../../../app-assets/images/backgrounds/02.jpg">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route("super.dashboard") }}"><img class="brand-logo" src="{{ asset(Settings::settings()['company_logo']) }}"/>
              <h3 class="brand-text">{{ Settings::settings()['company_name'] }}</h3></a></li>
          <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
      </div>
      <div class="navigation-background"></div>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="{{ (request()->route()->named('super.dashboard')) ? 'active' : '' }}"><a href="{{ route("super.dashboard") }}"><i class="ft-home"></i><span class="menu-title">Dashboard</span></a></li>

            <li class=" nav-item
             @if(
                request()->route()->named('super.users')
                ) open  @endif
            ">
                <a href="#"><i class="ft-monitor"></i><span class="menu-title">Authority Control</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->route()->named('super.users')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("super.users") }}">User Accounts</a> </li>
                </ul>
            </li>
            <li class="{{ (request()->route()->named('super.company')) ? 'active' : '' }}"><a href="{{ route("super.company") }}"><i class="ft-users"></i><span class="menu-title">Company List</span></a></li>
            <li class="{{ (request()->route()->named('super.sms-api')) ? 'active' : '' }}"><a href="{{ route("super.sms-api") }}"><i class="ft-mail"></i><span class="menu-title">SMS API</span></a></li>
            <li class="{{ (request()->route()->named('super.sms_add_balance')) ? 'active' : '' }}"><a href="{{ route("super.sms_add_balance") }}"><i class="ft-mail"></i><span class="menu-title">SMS Balance</span></a></li>

        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->
