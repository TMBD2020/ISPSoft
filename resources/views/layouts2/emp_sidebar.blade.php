
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

            <?php// dd(Module::s(2))?>
            @foreach(Module::m() as $module)
                <li class="{{ $module->module_link !="#"?(request()->is($module->module_link)) ? 'active' : '':'' }}"><a href="{{ $module->module_link !="#"? route($module->module_link): "#" }}"><i class="ft-home"></i><span class="menu-title">{{ $module->module_name }}</span></a>
                    @if(count(Module::s($module->moduleId))>0)
                    <ul class="menu-content">
                        @foreach(Module::s($module->moduleId) as $subModule)
                        <li class="{{ $subModule->sub_module_link !="#"?(request()->is($subModule->sub_module_link)) ? 'active' : '':'' }}"><a class="menu-item " href="{{ $subModule->sub_module_link !="#"? route($subModule->sub_module_link): "#" }}">{{ $subModule->sub_module_name }}</a> </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
            @endforeach

        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->
