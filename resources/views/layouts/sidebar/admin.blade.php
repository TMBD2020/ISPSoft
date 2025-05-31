
  <style>
      .main-menu-content ul ,.main-menu-content ul li ul a {
          font-size: 12px;
      }
  </style>
   <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true" data-img="{{ asset("app-assets/images/backgrounds/02.jpg") }}">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mr-auto">
              <a class="navbar-brand" href="{{ route("dashboard") }}">
                  @if(Settings::settings()['company_logo'])
                      <img class="brand-logo" src="{{ asset(Settings::settings()['company_logo']) }}"/> @endif
                    <h3 class="brand-text">{{ Settings::settings()['company_name'] }}</h3>
              </a>
          </li>
          <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
      </div>
      <div class="navigation-background"></div>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="{{ (request()->route()->named('home')) ? 'active' : '' }}"><a href="{{ route("dashboard") }}"><i class="ft-home"></i><span class="menu-title">Dashboard</span></a></li>

            @if(Auth::user()->can('admin-user-list')
            or Auth::user()->can('role-list')
            )
            <li class=" nav-item
             @if(
                request()->route()->named('user-list')
                or request()->route()->named('role-list')
                ) open  @endif
            ">
                <a href="#"><i class="ft-command"></i><span class="menu-title">Authority Control</span></a>
                <ul class="menu-content">
                    @if(Auth::user()->can('admin-user-list'))<li class="{{ (request()->route()->named('user-list')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("user-list") }}">User List</a> </li>@endif
                    @if(Auth::user()->can('role-list'))<li class="{{ (request()->route()->named('role-list')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("role-list") }}">Role List</a> </li>@endif
                </ul>
            </li>
            @endif
           
            @if(Auth::user()->can('income-statement')
            or Auth::user()->can('expense-report')
            or Auth::user()->can('salary-report')
            or Auth::user()->can('isp-client-report')
            or Auth::user()->can('isp-bill-generate-report')
            or Auth::user()->can('isp-due-report')
            or Auth::user()->can('isp-collection-report')
            or Auth::user()->can('isp-collection-summery-report')
            or Auth::user()->can('catv-collection-report')
            or Auth::user()->can('catv-collection-summery-report')
            or Auth::user()->can('isp-connection-details')
            )
            <li class=" nav-item
                @if(
                request()->route()->named('income-statement')
                or request()->route()->named('bill-generate-report')
                or request()->route()->named('salary-report')
                or request()->route()->named('due-bill')
                or request()->route()->named('expense-report')
                or request()->route()->named('client-collection')
                or request()->route()->named('client-collection-summery')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-trending-up"></i><span class="menu-title">Reports</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(4,'read_access'))<li class="{{ (request()->route()->named('income-statement')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("income-statement") }}">Income Statement</a> </li>@endif
                    @if(Permission::sub_module(5,'read_access'))<li class="{{ (request()->route()->named('expense-report')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("expense-report") }}">Expense</a> </li>@endif
                    @if(Permission::sub_module(5,'read_access'))<li class="{{ (request()->route()->named('salary-report')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("salary-report") }}">Salary</a> </li>@endif
                    @if(Permission::sub_module(39,'read_access'))
                       <li class="{{ (request()->route()->named('client-collection-summery')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("client-collection-summery") }}">ISP</a>
                            <ul>
                                <li class="{{ (request()->route()->named('isp-client-list')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("isp-client-list") }}">Clients</a> </li>
                                <li class="{{ (request()->route()->named('bill-generate-report')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("bill-generate-report") }}">Bill Generate</a> </li>
                                <li class="{{ (request()->route()->named('due-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("due-bill") }}">Due Bill</a> </li>
                                <li class="{{ (request()->route()->named('client-collection')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("client-collection") }}">Collection</a> </li>
                                <li class="{{ (request()->route()->named('client-collection-summery')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("client-collection-summery") }}">Collection Summery</a> </li>
                            </ul>
                        </li>
                    @endif
                    @if(Permission::sub_module(40,'read_access'))
                        <li class="has-sub"><a href="javascript:void(0)">CATV</a>

                            <ul class="menu-content">
                                <li class="{{ (request()->route()->named('catv-collection')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("catv-collection") }}">Collection</a></li>
                                <li class="{{ (request()->route()->named('catv-collection-summery')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("catv-collection-summery") }}">Collection Summery</a> </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </li>
            @endif
            
            @if(Auth::user()->can('isp-connection-details'))
                <li class="{{ (request()->route()->named('connection-details')) ? 'active' : '' }}">
                    <a href="{{ route("connection-details") }}"><i class="ft-monitor"></i><span class="menu-title">Connection Details</span></a>
                </li>
            @endif

            @if(Auth::user()->can('network-station-list'))
            <li class="{{ (request()->route()->named('network-list')) ? 'active' : '' }}">
                <a href="{{ route("network-list") }}"><i class="ft-activity"></i><span class="menu-title">Network Station</span></a>
            </li>
            @endif

            @if(Auth::user()->can('pop-list')
            or Auth::user()->can('pop-category-list')
            or Auth::user()->can('isp-zone-list')
            or Auth::user()->can('node-list')
            or Auth::user()->can('sub-node-list')
            or Auth::user()->can('isp-package-list')
            )
            <li class=" nav-item
                @if(
               request()->route()->named('pop-list')
                || request()->route()->named('pop-category')
                || request()->route()->named('zone-list')
                || request()->route()->named('node-list')
                || request()->route()->named('box-list')
                || request()->route()->named('package-list')
                ) open  @endif
                ">
                <a href="#"><i class="ft-inbox"></i><span class="menu-title">POP Station</span></a>
                <ul class="menu-content">
                    @if(Auth::user()->can('pop-list'))<li class="{{ (request()->route()->named('pop-list')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("pop-list") }}">POP List</a> </li>@endif
                    @if(Auth::user()->can('pop-category-list'))<li class="{{ (request()->route()->named('pop-category')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("pop-category") }}">POP Category</a> </li>@endif
                    @if(Auth::user()->can('isp-zone-list'))<li class="{{ (request()->route()->named('zone-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("zone-list") }}">Zone List</a></li>@endif
                    @if(Auth::user()->can('node-list'))<li class="{{ (request()->route()->named('node-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("node-list") }}">Node List</a></li>@endif
                    @if(Auth::user()->can('sub-node-list'))<li class="{{ (request()->route()->named('box-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("box-list") }}">Sub Node List</a></li>@endif
                    @if(Auth::user()->can('isp-package-list'))<li class="{{ (request()->route()->named('package-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("package-list") }}">Package List</a></li>@endif
                </ul>
            </li>
            @endif
            
            @if(Auth::user()->can('catv-station'))
            <li class=" nav-item
                @if(
                request()->route()->named('catb-zone-list')
                || request()->route()->named('catv-sub-zone')
                || request()->route()->named('catv-station-excel')
                || request()->route()->named('catv-package')
                ) open  @endif
                ">
                <a href="#"><i class="ft-tv"></i><span class="menu-title">CATV Station</span></a>
                <ul class="menu-content">
                    @if(Auth::user()->can('catv-import-export'))<li class="{{ (request()->route()->named('catv-station-excel')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("catv-station-excel") }}">Import/Export</a></li>@endif
                    @if(Auth::user()->can('catv-zone-list'))<li class="{{ (request()->route()->named('catv-zone')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("catv-zone") }}">Zone</a></li>@endif
                    @if(Auth::user()->can('catv-sub-zone-list'))<li class="{{ (request()->route()->named('catv-sub-zone')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("catv-sub-zone") }}">Sub Zone</a></li>@endif
                    @if(Auth::user()->can('catv-package-list'))<li class="{{ (request()->route()->named('catv-package')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("catv-package") }}">Package</a></li>@endif
                </ul>
            </li>
            @endif

            @if(Auth::user()->can('isp-client-list')
            or Auth::user()->can('isp-reseller-list')
            or Auth::user()->can('catv-client-list')
            or Auth::user()->can('catv-reseller-list')
            or Auth::user()->can('isp-client-custom-edit')
            )
            <li class=" nav-item
                @if(
                request()->route()->named('isp-clients')
                || request()->route()->named('isp-pppoe')
                || request()->route()->named('isp-queue')
                || request()->route()->named('catv-clients')
                || request()->route()->named('isp-reseller')
                || request()->route()->named('custom-edit')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-users"></i><span class="menu-title">Clients</span></a>
                <ul class="menu-content">
                    @if(Auth::user()->can('isp-client-list'))<li class="{{ (request()->route()->named('isp-clients')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("isp-clients") }}">ISP Clients</a> </li>@endif
                    @if(Auth::user()->can('isp-reseller-list'))<li class="{{ (request()->route()->named('isp-reseller')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("isp-reseller") }}">ISP Resellers</a> </li>@endif
                    @if(Auth::user()->can('catv-client-list'))<li class="{{ (request()->route()->named('catv-clients')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("catv-clients") }}">CATV Clients</a> </li>@endif
                    @if(Auth::user()->can('isp-client-custom-edit'))<li class="{{ (request()->route()->named('custom-edit')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("custom-edit") }}">Custom Edit</a> </li>@endif
                </ul>
            </li>
            @endif

            @if(Auth::user()->can('isp-bill-list')
            or Auth::user()->can('catv-bill-list')
            or Auth::user()->can('isp-reseller-bill-list')
            or Auth::user()->can('catv-reseller-bill-list')
            or Auth::user()->can('isp-bill-approve')
            or Auth::user()->can('catv-bill-approve')
            or Auth::user()->can('generate-isp-bill')
            or Auth::user()->can('generate-catv-bill')
            )
            <li class=" nav-item
                @if(
                request()->route()->named('client-bill')
                || request()->route()->named('bill-approval')
                || request()->route()->named('generate-isp-client-bill')
                || request()->route()->named('catv-bill')
                || request()->route()->named('reseller-bill')
                || request()->route()->named('other-bill')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-file-text"></i><span class="menu-title">Billing</span></a>
                <ul class="menu-content">
                    {{-- @if(Permission::sub_module(51,'read_access'))<li class="{{ (request()->route()->named('bill-approval')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("bill-approval") }}">Bill Approval</a> </li>@endif --}}
                    @if(Auth::user()->can('generate-isp-bill'))<li class="{{ (request()->route()->named('generate-isp-client-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("generate-isp-client-bill") }}">Generate ISP Bill</a> </li>@endif
                    @if(Auth::user()->can('generate-catv-bill'))<li class="{{ (request()->route()->named('generate-catv-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("generate-catv-bill") }}">Generate CATV Bill</a> </li>@endif
                    @if(Auth::user()->can('isp-bill-list'))<li class="{{ (request()->route()->named('client-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("client-bill") }}">ISP Client Bill</a> </li>@endif
                    @if(Auth::user()->can('catv-bill-list'))<li class="{{ (request()->route()->named('catv-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("catv-bill") }}">CATV Client Bill</a> </li>@endif
                    @if( Auth::user()->can('isp-reseller-bill-list'))<li class="{{ (request()->route()->named('reseller-bill')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("reseller-bill") }}">Reseller Bill</a> </li>@endif
                </ul>
            </li>
            @endif

            @if(Auth::user()->can("emp-liability")
            or Auth::user()->can("employee-list")
            or Auth::user()->can("emp-liability")
            )
            <li class=" nav-item
                @if(
                request()->route()->named('employee-list')
                || request()->route()->named('emp-liability')
                || request()->route()->named('emp-salary')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-user"></i><span class="menu-title">HR</span></a>
                <ul class="menu-content">
                    @if(Auth::user()->can("employee-list"))<li class="{{ (request()->route()->named('employee-list')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("employee-list") }}">Employeee</a> </li>@endif
                    @if(Auth::user()->can("emp-liability"))<li class="{{ (request()->route()->named('emp-liability')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("emp-liability") }}">Liability</a> </li>@endif
                    @if(Auth::user()->can("emp-salary"))<li class="{{ (request()->route()->named('emp-salary')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("emp-salary") }}">Salary</a> </li>@endif
                </ul>
            </li>
            @endif

            @if(Auth::user()->can("expense"))
            <li class="{{ (request()->route()->named('expense')) ? 'active' : '' }}">
                <a href="{{ route("expense") }}"><i class="ft-bar-chart-2"></i><span class="menu-title">Expenses</span></a>
            </li>
            @endif

           
            @if(Auth::user()->can("liability-panel"))
            <li class="{{ (request()->route()->named('liability')) ? 'active' : '' }}">
                <a href="{{ route("liability") }}"><i class="ft-zap"></i><span class="menu-title">Liability</span></a>
            </li>
            @endif

            @if(Auth::user()->can("sms-panel"))
            <li class=" nav-item
                @if(
                   request()->route()->named('send-sms')
                || request()->route()->named('sms-template')
                || request()->route()->named('sms_preview')
                || request()->route()->named('sms-history')

                ) open  @endif
                    ">
                <a href="#"><i class="ft-mail"></i><span class="menu-title">SMS</span></a>
                <ul class="menu-content">
                    @if(Permission::sub_module(20,'read_access'))<li class="@if(request()->route()->named('send-sms'))  active @elseif(request()->route()->named('sms_preview'))active @endif "><a class="menu-item " href="{{ route("send-sms",'') }}">Send SMS</a> </li>@endif
                    @if(Permission::sub_module(21,'read_access'))<li class="{{ (request()->route()->named('sms-history')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("sms-history") }}">SMS History</a> </li>@endif
                    @if(Permission::sub_module(22,'read_access'))<li class="{{ (request()->route()->named('sms-template')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("sms-template") }}">SMS Template</a> </li>@endif
                   

                </ul>
            </li>
            @endif

            @if(Auth::user()->can("complain-ticket")
             or Auth::user()->can("upcoming-client")
             or Auth::user()->can("line-shift")
             or Auth::user()->can("package-change")
              )
            <li class=" nav-item
                @if(
                request()->route()->named('tickets')
                || request()->route()->named('upcoming-clients')
                || request()->route()->named('line-shift')
                || request()->route()->named('package-change')
                ) open  @endif
                ">
                <a href="#"><i class="ft-help-circle"></i><span class="menu-title">Client Support</span></a>
                <ul class="menu-content">
                    @if(Auth::user()->can("complain-ticket"))<li class="{{ (request()->route()->named('tickets')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("tickets") }}">Complain Ticket</a> </li>@endif
                    @if(Auth::user()->can("upcoming-client"))<li class="{{ (request()->route()->named('upcoming-clients')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("upcoming-clients") }}">Upcoming Clients</a> </li>@endif
                    @if(Auth::user()->can("line-shift"))<li class="{{ (request()->route()->named('line-shift')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("line-shift") }}">Line Shift</a> </li>@endif
                    @if(Auth::user()->can("package-change"))<li class="{{ (request()->route()->named('package-change')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("package-change") }}">Package Change</a> </li>@endif
                 </ul>
            </li>
            @endif

            @if(Auth::user()->can("store-record")
            or Auth::user()->can("purchase-product")
            or Auth::user()->can("store-product")
            )
            <li class=" nav-item
                @if(
                request()->route()->named('purchase-product')
                || request()->route()->named('stock-product')
                || request()->route()->named('store-record')
                || request()->route()->named('store-requisition')
                ) open active @endif
                ">
                <a href="#"><i class="ft-box"></i><span class="menu-title">Store</span></a>
                <ul class="menu-content">
                    @if(Auth::user()->can("store-record"))<li class="{{ (request()->route()->named('store-record')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("store-record") }}">Store Record</a> </li>@endif
                    @if(Auth::user()->can("purchase-product"))<li class="{{ (request()->route()->named('purchase-product')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("purchase-product") }}">New Purchase</a> </li>@endif
                    @if(Auth::user()->can("store-product"))<li class="{{ (request()->route()->named('store-product')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("store-product") }}">Products</a> </li>@endif
                 </ul>
            </li>
            @endif

            @if(Auth::user()->can("store-requisition"))
            <li class="{{ (request()->route()->named('store-requisition')) ? 'active' : '' }}">
                <a href="{{ route("store-requisition") }}"><i class="ft-file-plus"></i><span class="menu-title">New Requisition</span></a>
            </li>
            @endif

           @if(Auth::user()->can("department")
           or Auth::user()->can("designation")
           or Auth::user()->can("id-generator")
           )
            <li class=" nav-item
                @if(
                request()->route()->named('department')
                || request()->route()->named('designation')
                || request()->route()->named('id-prefix')
                || request()->route()->named('id-type')
                || request()->route()->named('salary-setting')
                || request()->route()->named('company-setting')
                ) open  @endif
                ">
                <a href="#"><i class="ft-settings"></i><span class="menu-title">Settings</span></a>
                <ul class="menu-content">
                    @if(Auth::user()->can("department"))<li class="{{ (request()->route()->named('department')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("department") }}">Department</a> </li>@endif
                    @if(Auth::user()->can("designation"))<li class="{{ (request()->route()->named('designation')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("designation") }}">Designation</a> </li>@endif
                    @if(Auth::user()->can("id-generator"))
                        <li><a class="has-sub
                            @if(
                            request()->route()->named('id-prefix')
                            || request()->route()->named('id-type')
                            )   open  @endif
                            " href="#">ID Generator</a>
                            <ul class="menu-content">
                                <li class="{{ (request()->route()->named('id-prefix')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("id-prefix") }}">ID Prefix</a> </li>
                                <li class="{{ (request()->route()->named('id-type')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("id-type") }}">ID Type</a> </li>
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->can("salary-setting"))<li class="{{ (request()->route()->named('salary-setting')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("salary-setting") }}">Salary Distribution</a> </li>@endif
                    @if(Auth::user()->can("payment-method"))<li class="{{ (request()->route()->named('payment-method')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("payment-method") }}">Payment Method</a> </li>@endif
                    @if(Auth::user()->can("company-setting"))<li class="{{ (request()->route()->named('company-setting')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("company-setting") }}">Company Setting</a> </li>@endif
                </ul>
            </li>
            @endif
        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->
