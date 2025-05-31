
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

            <li class=" nav-item
             @if(
                request()->is('user-list')
                or request()->is('role-list')
                or request()->is('access-permission')
                ) open  @endif
            ">
                <a href="#"><i class="ft-monitor"></i><span class="menu-title">Authority Control</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('user-list')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("user-list") }}">User List</a> </li>
                    <li class="{{ (request()->is('role-list')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("role-list") }}">Role List</a> </li>
                    <li class="{{ (request()->is('access-permission')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("access-permission") }}">Access Permission</a> </li>
                </ul>
            </li>

            <li class=" nav-item
                @if(
                request()->is('income-statement')
                or request()->is('due-bill')
                or request()->is('expense-report')
                or request()->is('client-collection')
                or request()->is('client-collection-summery')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-monitor"></i><span class="menu-title">Reports</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('income-statement')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("income-statement") }}">Income Statement</a> </li>
                    <li class="{{ (request()->is('due-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("due-bill") }}">Due Bill</a> </li>
                    <li class="{{ (request()->is('expense-report')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("expense-report") }}">Expense</a> </li>
                    <li class="{{ (request()->is('client-collection')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("client-collection") }}">Collection</a> </li>
                    <li class="{{ (request()->is('client-collection-summery')) ? 'active' : '' }}"><a class="menu-item " href="{{ route("client-collection-summery") }}">Collection Summery</a> </li>
                </ul>
            </li>
            <li class="{{ (request()->is('connection-details')) ? 'active' : '' }}">
                <a href="{{ route("connection-details") }}"><i class="ft-monitor"></i><span class="menu-title">Connection Details</span></a>
            </li>
            <li class="{{ (request()->is('network-list')) ? 'active' : '' }}">
                <a href="{{ route("network-list") }}"><i class="ft-monitor"></i><span class="menu-title">Network Station</span></a>
            </li>
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
                <a href="#"><i class="ft-monitor"></i><span class="menu-title">POP Station</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('pop-list')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("pop-list") }}">POP List</a> </li>
                    <li class="{{ (request()->is('pop-category')) ? 'active' : '' }}"><a class="menu-item" href="{{ url("pop-category") }}">POP Category</a> </li>
                    <li class="{{ (request()->is('zone-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("zone-list") }}">Zone List</a></li>
                    <li class="{{ (request()->is('node-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("node-list") }}">Node List</a></li>
                    <li class="{{ (request()->is('box-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("box-list") }}">Box List</a></li>
                    <li class="{{ (request()->is('package-list')) ? 'active' : '' }}"><a class="menu-item"  href="{{ route("package-list") }}">Package List</a></li>
                </ul>
            </li>

            <li class=" nav-item
                @if(
                request()->is('employee-list')
                || request()->is('emp-liability')
                || request()->is('emp-salary')
                ) open  @endif
                    ">
                <a href="#"><i class="ft-monitor"></i><span class="menu-title">Employee</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('employee-list')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("employee-list") }}">Employee List</a> </li>
                    <li class="{{ (request()->is('emp-liability')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("emp-liability") }}">Liability</a> </li>
                    <li class="{{ (request()->is('emp-salary')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("emp-salary") }}">Salary</a> </li>
                </ul>
            </li>

            <li class="{{ (request()->is('client-list')) ? 'active' : '' }}">
                <a href="{{ route("client-list") }}"><i class="ft-monitor"></i><span class="menu-title">Clients</span></a>
            </li>

            <li class="{{ (request()->is('reseller-list')) ? 'active' : '' }}">
                <a href="{{ route("reseller-list") }}"><i class="ft-monitor"></i><span class="menu-title">Resellers</span></a>
            </li>

            <li class="{{ (request()->is('expense-list')) ? 'active' : '' }}">
                <a href="{{ route("expense") }}"><i class="ft-monitor"></i><span class="menu-title">Expenses</span></a>
            </li>

            <li class="{{ (request()->is('liability')) ? 'active' : '' }}">
                <a href="{{ route("liability") }}"><i class="ft-monitor"></i><span class="menu-title">Liability</span></a>
            </li>

            <li class=" nav-item
                @if(
                request()->is('client-bill')
                || request()->is('reseller-bill')
                || request()->is('other-bill')
                ) open  @endif
                ">
                <a href="#"><i class="ft-monitor"></i><span class="menu-title">Billing</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('client-bill')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("client-bill") }}">Client Bill</a> </li>
                    <li class="{{ (request()->is('reseller-bill')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("reseller-bill") }}">Reseller Bill</a> </li>
                    {{--<li class="{{ (request()->is('other-bill')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("upcoming-clients") }}">Other Bill</a> </li>--}}
                 </ul>
            </li>

            <li class=" nav-item
                @if(
                request()->is('send-sms')
                || request()->is('sms-template')
                || request()->is('sms_preview')
                || request()->is('sms-history')
                || request()->is('sms-api')

                ) open  @endif
                    ">
                <a href="#"><i class="ft-monitor"></i><span class="menu-title">SMS</span></a>
                <ul class="menu-content">
                    <li class="@if(request()->is('send-sms'))  active @elseif(request()->is('sms_preview'))active @endif "><a class="menu-item " href="{{ url("send-sms") }}">Send SMS</a> </li>
                    <li class="{{ (request()->is('sms-history')) ? 'active' : '' }}"><a class="menu-item" href="{{ url("sms-history") }}">SMS History</a> </li>
                    <li class="{{ (request()->is('sms-template')) ? 'active' : '' }}"><a class="menu-item" href="{{ url("sms-template") }}">SMS Template</a> </li>
                    <li class="{{ (request()->is('sms-api')) ? 'active' : '' }}"><a class="menu-item" href="{{ url("sms-api") }}">SMS API</a> </li>

                </ul>
            </li>

            <li class=" nav-item
                @if(
                request()->is('tickets')
                || request()->is('upcoming-clients')
                || request()->is('line-shift')
                || request()->is('package-change')
                ) open  @endif
                ">
                <a href="#"><i class="ft-monitor"></i><span class="menu-title">Client Support</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('tickets')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("tickets") }}">Complain Ticket</a> </li>
                    <li class="{{ (request()->is('upcoming-clients')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("upcoming-clients") }}">Upcoming Clients</a> </li>
                    <li class="{{ (request()->is('line-shift')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("line-shift") }}">Line Shift</a> </li>
                    <li class="{{ (request()->is('package-change')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("package-change") }}">Package Change</a> </li>
                 </ul>
            </li>

            <li class=" nav-item
                @if(
                request()->is('purchase-product')
                || request()->is('stock-product')
                || request()->is('store-record')
                || request()->is('store-requisition')
                ) open  @endif
                ">
                <a href="#"><i class="ft-monitor"></i><span class="menu-title">Store</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('store-record')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("store-record") }}">Store Record</a> </li>
                    <li class="{{ (request()->is('purchase-product')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("purchase-product") }}">New Purchase</a> </li>
                    <li class="{{ (request()->is('store-product')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("store-product") }}">Products</a> </li>
                 </ul>
            </li>


            <li class="{{ (request()->is('store-requisition')) ? 'active' : '' }}">
                <a href="{{ route("store-requisition") }}"><i class="ft-monitor"></i><span class="menu-title">New Requisition</span></a>
            </li>
            @can('user-panel')
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
                <a href="#"><i class="ft-monitor"></i><span class="menu-title">Settings</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->is('department')) ? 'active' : '' }}"><a class="menu-item " href="{{ url("department") }}">Department</a> </li>
                    <li class="{{ (request()->is('designation')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("designation") }}">Designation</a> </li>
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
                    <li class="{{ (request()->is('salary-setting')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("salary-setting") }}">Salary Distribution</a> </li>
                    <li class="{{ (request()->is('payment-method')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("payment-method") }}">Payment Method</a> </li>
                    <li class="{{ (request()->is('income-head')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("income-head") }}">Income Head</a> </li>
                    <li class="{{ (request()->is('company-setting')) ? 'active' : '' }}"><a class="menu-item" href="{{ route("company-setting") }}">Company Setting</a> </li>
                </ul>
            </li>
            @endcan
        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->
