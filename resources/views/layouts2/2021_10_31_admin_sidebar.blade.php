  <style>
      .main-menu-content ul ,.main-menu-content ul li ul a {
          font-size: 12px;
      }
  </style>
   <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true" data-img="{{ asset("app-assets/images/backgrounds/02.jpg") }}">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route("dashboard") }}">
                  @if(Settings::settings()['company_logo']))
                      <img class="brand-logo" src="{{ asset(Settings::settings()['company_logo']) }}"/>
                  @endif
              <h3 class="brand-text">{{ Settings::settings()['company_name'] }}</h3></a></li>
          <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
      </div>
      <div class="navigation-background"></div>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            @foreach(Module::ms() as $module)
                <li class="{{ $module->module_link !="#"?(request()->is($module->module_link)) ? 'active' : '':'' }}"><a href="{{ $module->module_link !="#"? route($module->module_link): "#" }}"><i class="{{ $module->module_icon }}"></i><span class="menu-title">{{ $module->module_name }}</span></a>

                    @if(count(Module::ss())>0)
                        <?php $isSub=0;?>
                        @foreach(Module::ss() as $subModule)
                            @if($module->moduleId==$subModule->moduleId and $module->moduleId!=1)

                                <?php $isSub=1;?>
                            @endif
                        @endforeach
                        @if($isSub==1 and $module->moduleId!=1)
                            <ul class="menu-content">
                                @foreach(Module::ss() as $subModule)
                                    @if($module->moduleId==$subModule->moduleId)
                                        <li class="{{ $subModule->sub_module_link !="#"?(request()->is($subModule->sub_module_link)) ? 'active' : '':'' }}"><a class="menu-item " href="{{ $subModule->sub_module_link !="#"? route($subModule->sub_module_link): "#" }}">{{ $subModule->sub_module_name }}</a>
                                            @if($subModule->subModuleId==39 or $subModule->subModuleId==40 or $subModule->subModuleId==44)
                                                <ul class="menu-content">
                                                    @if($subModule->subModuleId==39)
                                                        <li class="{{ request()->is('due-bill') ? 'active' : '' }}"><a class="menu-item" href="{{ route('due-bill') }}">Dues</a></li>
                                                        <li class="{{ request()->is('client-collection') ? 'active' : '' }}"><a class="menu-item" href="{{ route('client-collection') }}">Collection</a></li>
                                                        <li class="{{ request()->is('client-collection-summery') ? 'active' : '' }}"><a class="menu-item" href="{{ route('client-collection-summery') }}">Collection Summery</a></li>
                                                    @endif
                                                    @if($subModule->subModuleId==40)
                                                        <li class="{{ request()->is('catv-collection') ? 'active' : '' }}"><a class="menu-item" href="{{ route('catv-collection') }}">Collection</a></li>
                                                        <li class="{{ request()->is('catv-collection-summery') ? 'active' : '' }}"><a class="menu-item" href="{{ route('catv-collection-summery') }}">Collection Summery</a></li>
                                                    @endif
                                                    @if($subModule->subModuleId==44)
                                                        <li class="{{ request()->is('id-prefix') ? 'active' : '' }}"><a class="menu-item" href="{{ route('id-prefix') }}">ID Prefix</a></li>
                                                        <li class="{{ request()->is('id-type') ? 'active' : '' }}"><a class="menu-item" href="{{ route('id-type') }}">ID Type</a></li>
                                                    @endif
                                                </ul>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    @endif
                </li>
            @endforeach
        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->