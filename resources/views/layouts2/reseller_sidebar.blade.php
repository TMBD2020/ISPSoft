
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


            <li class="{{ (request()->is('client-list')) ? 'active' : '' }}">
                <a href="{{ route("client-list") }}"><i class="ft-user"></i><span class="menu-title">Clients</span></a>
            </li>

        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->
