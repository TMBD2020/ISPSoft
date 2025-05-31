
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
		  @if(Settings::settings()['company_logo'])
                      <img class="brand-logo" src="{{ asset(Settings::settings()['company_logo']) }}"/> @endif
                    <h3 class="brand-text">{{ Settings::settings()['company_name'] }}</h3>
		 </a></li>
          <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
      </div>
      <div class="navigation-background"></div>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="{{ (request()->is('home')) ? 'active' : '' }}"><a href="{{ route("dashboard") }}"><i class="ft-home"></i><span class="menu-title">Dashboard</span></a></li>

            @if(Permission::module(2,'read_access'))
            <li class=" nav-item
             @if(
                request()->is('user-list')
                or request()->is('role-list')
                or request()->is('access-permission')
                ) open  @endif
            ">
                <a href="#"><i class="ft-command"></i><span class="menu-title">Authority Control</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(1,'read_access'))<li class="{{ (request()->is('user-list')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("user-list") }}">User List</a> </li>@endif
                    @if(Permission::sub_module(2,'read_access'))<li class="{{ (request()->is('role-list')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("role-list") }}">Role List</a> </li>@endif
                    @if(Permission::sub_module(3,'read_access'))<li class="{{ (request()->is('access-permission')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("access-permission") }}">Access Permission</a> </li>@endif
                </ul>
            </li>
            @endif

            @if(Permission::module(3,'read_access'))
            <li class=" nav-item
                @if(
                request()->is('income-statement')
                or request()->is('due-bill')
                or request()->is('expense-report')
                or request()->is('client-collection')
                or request()->is('client-collection-summery')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-trending-up"></i><span class="menu-title">Reports</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(4,'read_access'))<li class="{{ (request()->is('income-statement')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("income-statement") }}">Income Statement</a> </li>@endif
                    @if(Permission::sub_module(5,'read_access'))<li class="{{ (request()->is('expense-report')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("expense-report") }}">Expense</a> </li>@endif
                    @if(Permission::sub_module(39,'read_access'))
                       <li class="{{ (request()->is('client-collection-summery')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("client-collection-summery") }}">ISP</a>
                            <ul>
                                <li class="{{ (request()->is('due-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("due-bill") }}">Due Bill</a> </li>
                                <li class="{{ (request()->is('client-collection')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("client-collection") }}">Collection</a> </li>
                                <li class="{{ (request()->is('client-collection-summery')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("client-collection-summery") }}">Collection Summery</a> </li>
                            </ul>
                        </li>
                    @endif
                    @if(Permission::sub_module(40,'read_access'))
                        <li class="has-sub"><a href="javascript:void(0)">CATV</a>

                            <ul class="menu-content">
                                <li class="{{ (request()->is('catv-collection')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("catv-collection") }}">Collection</a></li>
                                <li class="{{ (request()->is('catv-collection-summery')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("catv-collection-summery") }}">Collection Summery</a> </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(Permission::module(4,"read_access"))
                <li class="{{ (request()->is('connection-details')) ? 'active' : '' }}">
                    <a href="{{ route("connection-details") }}"><i class="ft-monitor"></i><span class="menu-title">Connection Details</span></a>
                </li>
            @endif

            @if(Permission::module(5,"read_access"))
            <li class="{{ (request()->is('network-list')) ? 'active' : '' }}">
                <a href="{{ route("network-list") }}"><i class="ft-activity"></i><span class="menu-title">Network Station</span></a>
            </li>
            @endif

            @if(Permission::module(6,"read_access"))
            <li class=" nav-item
                @if(
                request()->is('pop-list')
                || request()->is('pop-category')
                || request()->is('zone-list')
                || request()->is('node-list')
                || request()->is('box-list')
                || request()->is('package-list')
                ) open  @endif
                ">
                <a href="#"><i class="ft-inbox"></i><span class="menu-title">POP Station</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(9,'read_access'))<li class="{{ (request()->is('pop-list')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("pop-list") }}">POP List</a> </li>@endif
                    @if(Permission::sub_module(10,'read_access'))<li class="{{ (request()->is('pop-category')) ? 'active' : '' }}"><a class="menu-item" href="{{ url("pop-category") }}">POP Category</a> </li>@endif
                    @if(Permission::sub_module(11,'read_access'))<li class="{{ (request()->is('zone-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("zone-list") }}">Zone List</a></li>@endif
                    @if(Permission::sub_module(12,'read_access'))<li class="{{ (request()->is('node-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("node-list") }}">Node List</a></li>@endif
                    @if(Permission::sub_module(13,'read_access'))<li class="{{ (request()->is('box-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("box-list") }}">Sub Node List</a></li>@endif
                    @if(Permission::sub_module(14,'read_access'))<li class="{{ (request()->is('package-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("package-list") }}">Package List</a></li>@endif
                </ul>
            </li>
            @endif

            @if(Permission::module(18,"read_access"))
            <li class=" nav-item
                @if(
                request()->is('catb-zone-list')
                || request()->is('catv-sub-zone')
                || request()->is('catv-station-excel')
                || request()->is('catv-package')
                ) open  @endif
                ">
                <a href="#"><i class="ft-tv"></i><span class="menu-title">CATV Station</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(31,'read_access'))<li class="{{ (request()->is('catv-station-excel')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("catv-station-excel") }}">Import/Export</a></li>@endif
                    @if(Permission::sub_module(32,'read_access'))<li class="{{ (request()->is('catv-zone')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("catv-zone") }}">Zone</a></li>@endif
                    @if(Permission::sub_module(33,'read_access'))<li class="{{ (request()->is('catv-sub-zone')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("catv-sub-zone") }}">Sub Zone</a></li>@endif
                    @if(Permission::sub_module(34,'read_access'))<li class="{{ (request()->is('catv-package')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("catv-package") }}">Package</a></li>@endif
                </ul>
            </li>
            @endif

            @if(Permission::module(8,"read_access"))
            <li class=" nav-item
                @if(
                request()->is('isp-clients')
                || request()->is('isp-pppoe')
                || request()->is('isp-queue')
                || request()->is('catv-clients')
                || request()->is('isp-reseller')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-users"></i><span class="menu-title">Clients</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(35,'read_access'))<li class="{{ (request()->is('isp-clients')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("isp-clients") }}">ISP Clients</a> </li>@endif
                    @if(Permission::sub_module(37,'read_access'))<li class="{{ (request()->is('isp-reseller')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("isp-reseller") }}">ISP Resellers</a> </li>@endif
                    @if(Permission::sub_module(36,'read_access'))<li class="{{ (request()->is('catv-clients')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("catv-clients") }}">CATV Clients</a> </li>@endif
                </ul>
            </li>
            @endif

            @if(Permission::module(12,"read_access"))
            <li class=" nav-item
                @if(
                request()->is('client-bill')
                || request()->is('catv-bill')
                || request()->is('reseller-bill')
                || request()->is('other-bill')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-file-text"></i><span class="menu-title">Billing</span></a>
                <ul class="menu-content">
                    
                    @if(Permission::sub_module(50,'read_access'))<li class="{{ (request()->is('generate-isp-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("generate-isp-bill") }}">Generate ISP Bill</a> </li>@endif
                    @if(Permission::sub_module(18,'read_access'))<li class="{{ (request()->is('client-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("client-bill") }}">ISP Client Bill</a> </li>@endif
                    @if(Permission::sub_module(19,'read_access'))<li class="{{ (request()->is('catv-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("catv-bill") }}">CATV Client Bill</a> </li>@endif
                    @if(Permission::sub_module(19,'read_access'))<li class="{{ (request()->is('reseller-bill')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("reseller-bill") }}">Reseller Bill</a> </li>@endif
                    {{--<li class="{{ (request()->is('other-bill')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("upcoming-clients") }}">Other Bill</a> </li>--}}
                </ul>
            </li>
            @endif

            @if(Permission::module(7,"read_access"))
            <li class=" nav-item
                @if(
                request()->is('employee-list')
                || request()->is('emp-liability')
                || request()->is('emp-salary')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-user"></i><span class="menu-title">Employees</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(15,'read_access'))<li class="{{ (request()->is('employee-list')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("employee-list") }}">Employee List</a> </li>@endif
                    @if(Permission::sub_module(16,'read_access'))<li class="{{ (request()->is('emp-liability')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("emp-liability") }}">Liability</a> </li>@endif
                    @if(Permission::sub_module(17,'read_access'))<li class="{{ (request()->is('emp-salary')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("emp-salary") }}">Salary</a> </li>@endif
                </ul>
            </li>
            @endif

            @if(Permission::module(10,"read_access"))
            <li class="{{ (request()->is('expense')) ? 'active' : '' }}">
                <a href="{{ route("expense") }}"><i class="ft-bar-chart-2"></i><span class="menu-title">Expenses</span></a>
            </li>
            @endif

            @if(Permission::module(11,"read_access"))
            <li class="{{ (request()->is('liability')) ? 'active' : '' }}">
                <a href="{{ route("liability") }}"><i class="ft-zap"></i><span class="menu-title">Liability</span></a>
            </li>
            @endif

            @if(Permission::module(13,"read_access"))
            <li class=" nav-item
                @if(
                   request()->is('send-sms')
                || request()->is('sms-template')
                || request()->is('sms_preview')
                || request()->is('sms-history')
                || request()->is('sms-api')

                ) open  @endif
                    ">
                <a href="#"><i class="ft-mail"></i><span class="menu-title">SMS</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(20,'read_access'))<li class="@if(request()->is('send-sms'))  active @elseif(request()->is('sms_preview'))active @endif "><a class="menu-item " href="{{ url("send-sms") }}">Send SMS</a> </li>@endif
                    @if(Permission::sub_module(21,'read_access'))<li class="{{ (request()->is('sms-history')) ? 'active' : '' }}"><a class="menu-item" href="{{ url("sms-history") }}">SMS History</a> </li>@endif
                    @if(Permission::sub_module(22,'read_access'))<li class="{{ (request()->is('sms-template')) ? 'active' : '' }}"><a class="menu-item" href="{{ url("sms-template") }}">SMS Template</a> </li>@endif
                    @if(Permission::sub_module(23,'read_access'))<li class="{{ (request()->is('sms-api')) ? 'active' : '' }}"><a class="menu-item" href="{{ url("sms-api") }}">SMS API</a> </li>@endif

                </ul>
            </li>
            @endif

            @if(Permission::module(14,"read_access"))
            <li class=" nav-item
			@if(
                request()->is('tickets')
                || request()->is('upcoming-clients')
                || request()->is('line-shift')
                || request()->is('package-change')
                ) open  @endif
                ">
                <a href="#"><i class="ft-help-circle"></i><span class="menu-title">Client Support</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(24,'read_access'))<li class="{{ (request()->is('tickets')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("tickets") }}">Complain Ticket</a> </li>@endif
                    @if(Permission::sub_module(25,'read_access'))<li class="{{ (request()->is('upcoming-clients')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("upcoming-clients") }}">Upcoming Clients</a> </li>@endif
                    @if(Permission::sub_module(26,'read_access'))<li class="{{ (request()->is('line-shift')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("line-shift") }}">Line Shift</a> </li>@endif
                    @if(Permission::sub_module(27,'read_access'))<li class="{{ (request()->is('package-change')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("package-change") }}">Package Change</a> </li>@endif
                 </ul>
            </li>
            @endif

            @if(Permission::module(15,"read_access"))
            <li class=" nav-item
                @if(
                request()->is('purchase-product')
                || request()->is('stock-product')
                || request()->is('store-record')
                || request()->is('store-requisition')
                ) open active @endif
                ">
                <a href="#"><i class="ft-box"></i><span class="menu-title">Store</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(28,'read_access'))<li class="{{ (request()->is('store-record')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("store-record") }}">Store Record</a> </li>@endif
                    @if(Permission::sub_module(29,'read_access'))<li class="{{ (request()->is('purchase-product')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("purchase-product") }}">New Purchase</a> </li>@endif
                    @if(Permission::sub_module(30,'read_access'))<li class="{{ (request()->is('store-product')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("store-product") }}">Products</a> </li>@endif
                 </ul>
            </li>
            @endif

            @if(Permission::module(16,"read_access"))
            <li class="{{ (request()->is('store-requisition')) ? 'active' : '' }}">
                <a href="{{ route("store-requisition") }}"><i class="ft-file-plus"></i><span class="menu-title">New Requisition</span></a>
            </li>
            @endif

            @if(Permission::module(19,"read_access"))
            <li class=" nav-item
                @if(
                request()->is('department')
                || request()->is('designation')
                || request()->is('id-prefix')
                || request()->is('id-type')
                || request()->is('salary-setting')
                || request()->is('company-setting')
                ) open  @endif
                ">
                <a href="#"><i class="ft-settings"></i><span class="menu-title">Settings</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(42,'read_access'))<li class="{{ (request()->is('department')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("department") }}">Department</a> </li>@endif
                    @if(Permission::sub_module(43,'read_access'))<li class="{{ (request()->is('designation')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("designation") }}">Designation</a> </li>@endif
                    @if(Permission::sub_module(44,'read_access'))
                        <li><a class="has-sub
                            @if(
                            request()->is('id-prefix')
                            || request()->is('id-type')
                            )   open  @endif
                            " href="#">ID Generator</a>
                            <ul class="menu-content">
                                <li class="{{ (request()->is('id-prefix')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("id-prefix") }}">ID Prefix</a> </li>
                                <li class="{{ (request()->is('id-type')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("id-type") }}">ID Type</a> </li>
                            </ul>
                        </li>
                    @endif
                    @if(Permission::sub_module(45,'read_access'))<li class="{{ (request()->is('salary-setting')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("salary-setting") }}">Salary Distribution</a> </li>@endif
                    @if(Permission::sub_module(46,'read_access'))<li class="{{ (request()->is('payment-method')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("payment-method") }}">Payment Method</a> </li>@endif
                    @if(Permission::sub_module(47,'read_access'))<li class="{{ (request()->is('company-setting')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("company-setting") }}">Company Setting</a> </li>@endif
                </ul>
            </li>
            @endif
        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->
