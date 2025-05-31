
  <style>
      .main-menu-content ul ,.main-menu-content ul li ul a {
          font-size: 12px;
      }
  </style>
   <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true" data-img="../../../app-assets/images/backgrounds/02.jpg">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route("dashboard") }}"><img class="brand-logo" src="{{ Settings::settings()['company_logo'] }}"/>
              <h3 class="brand-text">{{ Settings::settings()['company_name'] }}</h3></a></li>
          <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
      </div>
      <div class="navigation-background"></div>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="{{ (request()->is('dashboard')) ? 'active' : '' }}"><a href="{{ route("dashboard") }}"><i class="ft-home"></i><span class="menu-title">Dashboard</span></a></li>


            <li class="{{ (request()->is('isp-clients')) ? 'active' : '' }}">
                <a href="{{ route("isp-clients") }}"><i class="ft-user"></i><span class="menu-title">ISP Clients</span></a>
            </li>
            <li class="{{ (request()->is('catv-clients')) ? 'active' : '' }}">
                <a href="{{ route("catv-clients") }}"><i class="ft-user"></i><span class="menu-title">CATV Clients</span></a>
            </li>
            <li class=" nav-item
                @if(
                request()->is('client-bill')
                || request()->is('generate-isp-bill')
                || request()->is('catv-bill')
                || request()->is('other-bill')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-file-text"></i><span class="menu-title">Billing</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('client-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("client-bill") }}">ISP Client Bill</a> </li>
                    <li class="{{ (request()->is('catv-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("catv-bill") }}">CATV Client Bill</a> </li>
                 </ul>
            </li>

            <li class=" nav-item
            @if(
            request()->is('payment-method')
            ) open  @endif
                    ">
                <a href="#"><i class="ft-settings"></i><span class="menu-title">Settings</span></a>
                <ul class="menu-content">

                    <li class="{{ (request()->is('payment-method')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("payment-method") }}">Payment Method</a> </li>
                 </ul>
            </li>
        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->
