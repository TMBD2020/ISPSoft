<?php


use App\Http\Middleware\AdminRole;

use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;


Auth::routes(["register"=>false]);
Route::post('/authenticate',  [UserController::class,'authenticate']   )->name('authenticate');
Route::get('/register',       [UserController::class,'registerTmbdUser']  )->name('register');
Route::post('/register',      [UserController::class,'registerTmbdUserSave']   )->name('register');

Route::get('/',function(){
    return view('auth.login');
})->middleware(['auth']);

Route::prefix('client')->middleware([AdminRole::class,'auth'])->group(function () {
   // Route::get('testBIll/{id}',        'App\Http\Controllers\BillController@testBIll')->name('testBIll');

    Route::get('/',                 [HomeController::class,'index'])->name('dashboard');
    Route::get('/home',             [HomeController::class,'index'])->name('dashboard');
    Route::post('/dashboard_data',  'App\Http\Controllers\HomeController@dashboard')->name('dashboard_data');
    Route::get('/change-password',  'App\Http\Controllers\UserController@showChangePasswordForm')->name('change-password');
    Route::post('/changePassword',  'App\Http\Controllers\UserController@changePassword')->name('changePassword');
    Route::post('/changePhoto',     'App\Http\Controllers\UserController@changePhoto')->name('changePhoto');
    Route::post('/changeColor',     'App\Http\Controllers\UserController@changeThemeColors')->name('changeColor');

    Route::get('user-list',         'App\Http\Controllers\UserController@index')->name("user-list");
    Route::post('user_datalist',    'App\Http\Controllers\UserController@userList')->name('user_datalist');
    Route::post('save_user',        'App\Http\Controllers\UserController@save_user')->name('save_user');
    Route::post('update_user',      'App\Http\Controllers\UserController@adminUpdate')->name('update_user');
    Route::post('user_delete',      'App\Http\Controllers\UserController@adminDelete')->name('user_delete');
    Route::post('userList',         'App\Http\Controllers\UserController@empList')->name('userList');

    Route::get('role-list',         'App\Http\Controllers\RoleController@index')->name("role-list");
    Route::post('role_datalist',    'App\Http\Controllers\RoleController@roleList')->name("role_datalist");
    Route::post('save_role',        'App\Http\Controllers\RoleController@save_role')->name("save_role");
    Route::post('role_update',      'App\Http\Controllers\RoleController@roleUpdate')->name("role_update");
    Route::post('role_delete',      'App\Http\Controllers\RoleController@roleDelete')->name("role_delete");

    Route::get('network-list',      'App\Http\Controllers\MikrotikController@index')->name("network-list");
    Route::post('network_datalist', 'App\Http\Controllers\MikrotikController@networkList')->name('network_datalist');
    Route::post('save_network',     'App\Http\Controllers\MikrotikController@save_network')->name('save_network');
    Route::post('network_update',   'App\Http\Controllers\MikrotikController@networkUpdate')->name('network_update');
    Route::post('network_delete',   'App\Http\Controllers\MikrotikController@networkDelete')->name('network_delete');
    Route::post('connect_network',  'App\Http\Controllers\MikrotikController@connect_network')->name('connect_network');
    

    Route::get('pop-list',         'App\Http\Controllers\PoP\PopController@index')->name("pop-list");
    Route::post('pop-datalist',    'App\Http\Controllers\PoP\PopController@popList')->name("pop-datalist");
    Route::post('save_pop',        'App\Http\Controllers\PoP\PopController@save_pop')->name("save_pop");
    Route::post('pop_update',      'App\Http\Controllers\PoP\PopController@popUpdate')->name("pop_update");
    Route::post('pop_delete',      'App\Http\Controllers\PoP\PopController@popDelete')->name("pop_delete");
    Route::post('pop_by_network',  'App\Http\Controllers\PoP\PopController@popByNetwork')->name("pop_by_network");

    Route::get('pop-category',              'App\Http\Controllers\PoP\PopCategoryController@index')->name("pop-category");
    Route::post('pop_category_datalist',    'App\Http\Controllers\PoP\PopCategoryController@pop_categoryList')->name('pop_category_datalist');
    Route::post('save_pop_category',        'App\Http\Controllers\PoP\PopCategoryController@save_pop_category')->name('save_pop_category');
    Route::post('pop_category_update',      'App\Http\Controllers\PoP\PopCategoryController@pop_categoryUpdate')->name('pop_category_update');
    Route::post('pop_category_delete',      'App\Http\Controllers\PoP\PopCategoryController@pop_categoryDelete')->name('pop_category_delete');

    Route::get('shift-list',       'App\Http\Controllers\ShiftController@index');
    Route::post('save_shift',      'App\Http\Controllers\ShiftController@save_shift');
    Route::post('shift_datalist',  'App\Http\Controllers\ShiftController@shiftList');
    Route::post('shift_update',    'App\Http\Controllers\ShiftController@shiftUpdate');
    Route::post('shift_delete',    'App\Http\Controllers\ShiftController@shiftDelete');

    Route::get('zone-list',         [App\Http\Controllers\PoP\ZoneController::class,'index'])->name("zone-list");
    Route::get('catv-zone',         [App\Http\Controllers\PoP\ZoneController::class,'catv_index'])->name("catv-zone");
    Route::post('save_zone',        [App\Http\Controllers\PoP\ZoneController::class,'save_zone'])->name('save_zone');
    Route::post('isp_zone_list',    [App\Http\Controllers\PoP\ZoneController::class,'IspZoneList'])->name('isp_zone_list');
    Route::post('catb_zone_list',   [App\Http\Controllers\PoP\ZoneController::class,'CatbZoneList'])->name('catb_zone_list');
    Route::post('CatvZoneList',     [App\Http\Controllers\PoP\ZoneController::class,'CatvZoneList'])->name('CatvZoneList');
    Route::post('zone_update',      [App\Http\Controllers\PoP\ZoneController::class,'zoneUpdate'])->name('zone_update');
    Route::post('zone_delete',      [App\Http\Controllers\PoP\ZoneController::class,'zoneDelete'])->name('zone_delete');
    Route::post('zone_by_pop',      [App\Http\Controllers\PoP\ZoneController::class,'zoneByPOP'])->name('zone_by_pop');
    Route::post('ge_sub_zone',      [App\Http\Controllers\PoP\ZoneController::class,'subZone'])->name('ge_sub_zone');
    Route::get('catv-sub-zone',      [App\Http\Controllers\PoP\SubZoneController::class,'catv_index'])->name("catv-sub-zone");
    Route::post('catv_sub_zone_list',[App\Http\Controllers\PoP\SubZoneController::class,'catv_sub_zone'])->name('catv_sub_zone_list');
    Route::post('save_sub_zone',     [App\Http\Controllers\PoP\SubZoneController::class,'save_sub_zone'])->name('save_sub_zone');
    Route::post('sub_zone_update',   [App\Http\Controllers\PoP\SubZoneController::class,'update_sub_zone'])->name('sub_zone_update');
    Route::post('delete_sub_zone',   [App\Http\Controllers\PoP\SubZoneController::class,'delete_sub_zone'])->name('delete_sub_zone');

    Route::get('node-list',        'App\Http\Controllers\PoP\NodeController@index')->name("node-list");
    Route::post('save_node',       'App\Http\Controllers\PoP\NodeController@save_node')->name('save_node');
    Route::post('node_datalist',   'App\Http\Controllers\PoP\NodeController@nodeList')->name('node_datalist');
    Route::post('node_update',     'App\Http\Controllers\PoP\NodeController@nodeUpdate')->name('node_update');
    Route::post('node_delete',     'App\Http\Controllers\PoP\NodeController@nodeDelete')->name('node_delete');
    Route::post('save_node_id',    'App\Http\Controllers\PoP\NodeController@saveNodeId')->name('save_node_id');
    Route::post('last_node_id',    'App\Http\Controllers\PoP\NodeController@lastNodeId')->name('last_node_id');
    Route::post('node_id_datalist','App\Http\Controllers\PoP\NodeController@nodeIdDataList')->name('node_id_datalist');
    Route::post('node_id_list',    'App\Http\Controllers\PoP\NodeController@nodeIdList')->name('node_id_list');
    Route::post('node_by_zone',    'App\Http\Controllers\PoP\NodeController@nodeByZone')->name('node_by_zone');

    Route::get('box-list',       'App\Http\Controllers\PoP\BoxController@index')->name("box-list");
    Route::post('save_box',      'App\Http\Controllers\PoP\BoxController@save_box')->name('save_box');
    Route::post('box_datalist',  'App\Http\Controllers\PoP\BoxController@boxList')->name('box_datalist');
    Route::post('box_update',    'App\Http\Controllers\PoP\BoxController@boxUpdate')->name('box_update');
    Route::post('box_delete',    'App\Http\Controllers\PoP\BoxController@boxDelete')->name('box_delete');
    Route::post('box_by_node',   'App\Http\Controllers\PoP\BoxController@boxByNode')->name('box_by_node');

    Route::get('package-list',       'App\Http\Controllers\PoP\PackageController@index')->name("package-list");
    Route::post('save_package',      'App\Http\Controllers\PoP\PackageController@save_package')->name('save_package');
    Route::post('package_datalist',  'App\Http\Controllers\PoP\PackageController@packageList')->name('package_datalist');
    Route::post('package_update',    'App\Http\Controllers\PoP\PackageController@packageUpdate')->name('package_update');
    Route::post('package_delete',    'App\Http\Controllers\PoP\PackageController@packageDelete')->name('package_delete');

    Route::get('catv-package',         [App\Http\Controllers\PoP\PackageController::class,'catv_pack_index'])->name("catv-package");
    Route::post('catv_save_package',   [App\Http\Controllers\PoP\PackageController::class,'catv_pack_save_package'])->name('catv_save_package');
    Route::post('catv_package_list',   [App\Http\Controllers\PoP\PackageController::class,'catv_pack_list'])->name('catv_package_list');
    Route::post('catv_package_update', [App\Http\Controllers\PoP\PackageController::class,'catv_pack_update'])->name('catv_package_update');
    Route::post('catv_package_delete', [App\Http\Controllers\PoP\PackageController::class,'catv_pack_delete'])->name('catv_package_delete');



    Route::get('liability',                 'App\Http\Controllers\LiabilityController@index')->name("liability");
    Route::post('save_loan',                'App\Http\Controllers\LiabilityController@save_loan')->name('save_loan');
    Route::post('loan_receive_list',        'App\Http\Controllers\LiabilityController@LoanReceiveList')->name('loan_receive_list');
    Route::post('loan_payment_list',        'App\Http\Controllers\LiabilityController@LoanPaymentList')->name('loan_payment_list');
    Route::post('loan_summery_list',        'App\Http\Controllers\LiabilityController@LoanSummerList')->name('loan_summery_list');
    Route::post('loan_person_list',         'App\Http\Controllers\LiabilityController@LoanPersonList')->name('loan_person_list');
    Route::post('save_loan_person',         'App\Http\Controllers\LiabilityController@SaveLoanPerson')->name('save_loan_person');
    Route::post('loan_delete',              'App\Http\Controllers\LiabilityController@loanDelete')->name('loan_delete');
    Route::post('creditor_list',            'App\Http\Controllers\LiabilityController@CreditorList')->name('creditor_list');
    Route::post('creditor_update',          'App\Http\Controllers\LiabilityController@CreditorUpdate')->name('creditor_update');
    Route::post('payable_liability',        'App\Http\Controllers\LiabilityController@PayableLiability')->name('payable_liability');
    Route::post('creditor_liability_list',  'App\Http\Controllers\LiabilityController@creditor_liability_list')->name('creditor_liability_list');
    Route::post('download-liability-pdf',   'App\Http\Controllers\LiabilityController@downloadPDF')->name("download-liability-pdf");

    Route::get('client-bill',                      [App\Http\Controllers\BillController::class,'index'])->name("client-bill");
    Route::post('client_unpaid_bill',              'App\Http\Controllers\BillController@dueBill');
    Route::post('today_client_collection',         'App\Http\Controllers\BillController@TodayCollectionList')->name('today_client_collection');
    Route::post('client_all_collected_bill',       'App\Http\Controllers\BillController@AllCollectedBill');
    Route::post('client_bill_collect',             'App\Http\Controllers\BillController@createBill');
    Route::post('client_bill_details',             'App\Http\Controllers\BillController@BillInfo');
    Route::post('client_all_bill',                 'App\Http\Controllers\BillController@client_all_bill');
    Route::post('isp_client_bill_history',         'App\Http\Controllers\BillController@isp_client_bill_history')->name('isp_client_bill_history');
    Route::post('isp_client_due_sms',              'App\Http\Controllers\BillController@isp_client_due_sms')->name('isp_client_due_sms');
    Route::post('isp_client_due_sms_save',         'App\Http\Controllers\BillController@isp_client_due_sms_save')->name('isp_client_due_sms_save');
    Route::post('isp_commitment_date_update',      'App\Http\Controllers\BillController@isp_commitment_date_update')->name('isp_commitment_date_update');
    Route::get('generate-isp-client-bill',         'App\Http\Controllers\BillController@generate_bill')->name('generate-isp-client-bill');
    Route::post('generate-isp-client-bill-preview',        'App\Http\Controllers\BillController@GenerateIspClientPreivew')->name('generate-isp-client-bill-preview');
    Route::post('generate-isp-client-bill-save',        'App\Http\Controllers\BillController@GenerateIspClientBill')->name('generate-isp-client-bill-save');
    Route::post('isp_client_bill_era',             'App\Http\Controllers\BillController@isp_client_bill_era')->name('isp_client_bill_era');
    Route::post('create_isp_reactive_client_bill', 'App\Http\Controllers\BillController@createReActiveClientBill')->name('create_isp_reactive_client_bill');
    Route::get('bill-approval',                    'App\Http\Controllers\BillController@bill_approval')->name('bill-approval');
    Route::post('save_bill_approve',               'App\Http\Controllers\BillController@save_bill_approve')->name('save_bill_approve');
    Route::post('create_client_bill_other',        'App\Http\Controllers\BillController@create_client_bill_other')->name('create_client_bill_other');
    Route::post('isp-bill-print',                   'App\Http\Controllers\BillController@isp_client_bill_print')->name('isp-bill-print');
    Route::post('client_bill_mobile_update',        'App\\Http\\Controllers\\BillController@client_bill_mobile_update')->name('client_bill_mobile_update');
    Route::post('isp_bill_responsible_person',   'App\\Http\\Controllers\\BillController@getBillResponsiblePerson')->name('isp_bill_responsible_person');
    Route::post('isp_bill_responsible_person_update',   'App\\Http\\Controllers\\BillController@saveBillResponsiblePerson')->name('isp_bill_responsible_person_update');


    //catv bill
    Route::get('generate-catv-bill',            'App\Http\Controllers\CatBBillController@generate_bill')->name("generate-catv-bill");
    Route::get('catv-bill',                     'App\Http\Controllers\CatBBillController@index')->name("catv-bill");
    Route::post('catv_all_bill',                'App\Http\Controllers\CatBBillController@catv_all_bill')->name('catv_all_bill');
    Route::post('generate-catv-client-bill',    'App\Http\Controllers\CatBBillController@GenerateCatvClientBill')->name('generate-catv-client-bill');
    Route::post('catv_bill_details',            'App\Http\Controllers\CatBBillController@catv_bill_details')->name('catv_bill_details');
    Route::post('catv_bill_history',            'App\Http\Controllers\CatBBillController@catv_bill_history')->name('catv_bill_history');
    Route::post('catv_bill_collect',            'App\Http\Controllers\CatBBillController@catv_bill_collect')->name('catv_bill_collect');
    //other bill
    Route::get('generate-other-bill',           'App\Http\Controllers\Bills\OtherBillController@generate_other_bill');

    //Reseller Bill
    Route::get('reseller-bill',                   'App\Http\Controllers\Bills\ResellerBillController@index')->name("reseller-bill");
    Route::post('reseller_unpaid_bill',           'App\Http\Controllers\Bills\ResellerBillController@dueBill')->name('reseller_unpaid_bill');
    Route::post('today_reseller_collection',      'App\Http\Controllers\Bills\ResellerBillController@TodayCollectionList');
    Route::post('reseller_all_collected_bill',    'App\Http\Controllers\Bills\ResellerBillController@AllCollectedBill')->name('reseller_all_collected_bill');
    Route::post('reseller_bill_collect',          'App\Http\Controllers\Bills\ResellerBillController@createBill')->name('reseller_bill_collect');
    Route::post('reseller_bill_details',          'App\Http\Controllers\Bills\ResellerBillController@BillInfo')->name('reseller_bill_details');
    Route::post('reseller_all_bill',              'App\Http\Controllers\Bills\ResellerBillController@reseller_all_bill')->name('reseller_all_bill');
    Route::get('reseller_auto_bill',              'App\Http\Controllers\Bills\ResellerBillController@autobill')->name('reseller_auto_bill');
    Route::post('isp_reseller_bill_history',      'App\Http\Controllers\Bills\ResellerBillController@isp_reseller_bill_history')->name('isp_reseller_bill_history');
    Route::post('isp_reseller_due_sms',           'App\Http\Controllers\Bills\ResellerBillController@isp_reseller_due_sms')->name('isp_reseller_due_sms');
    Route::post('isp_reseller_due_sms_save',      'App\Http\Controllers\Bills\ResellerBillController@isp_reseller_due_sms_save')->name('isp_reseller_due_sms_save');


//    Route::group(['middleware' => ['can:user-panel']], function () {

    Route::get('department',            'App\Http\Controllers\Settings\DepartmentController@index')->name("department")->middleware("can:department");
    Route::post('save_department',      'App\Http\Controllers\Settings\DepartmentController@save_department')->name("save_department");
    Route::post('department_datalist',  'App\Http\Controllers\Settings\DepartmentController@departmentList')->name("department_datalist");
    Route::post('department_update',    'App\Http\Controllers\Settings\DepartmentController@departmentUpdate')->name("department_update");
    Route::post('department_delete',    'App\Http\Controllers\Settings\DepartmentController@departmentDelete')->name("department_delete");

    Route::get('designation',            'App\Http\Controllers\Settings\DesignationController@index')->name("designation")->middleware("can:designation");
    Route::post('save_designation',      'App\Http\Controllers\Settings\DesignationController@save_designation')->name("save_designation");
    Route::post('designation_datalist',  'App\Http\Controllers\Settings\DesignationController@designationList')->name("designation_datalist");
    Route::post('designation_update',    'App\Http\Controllers\Settings\DesignationController@designationUpdate')->name("designation_update");
    Route::post('designation_delete',    'App\Http\Controllers\Settings\DesignationController@designationDelete')->name("designation_delete");

    Route::get('payment-method',            'App\Http\Controllers\Settings\PaymentMethodController@index')->name("payment-method")->middleware("can:payment-method");
    Route::post('save_payment_method',      'App\Http\Controllers\Settings\PaymentMethodController@save_payment_method')->name("save_payment_method");
    Route::post('payment_method_datalist',  'App\Http\Controllers\Settings\PaymentMethodController@paymentMethodList')->name("payment_method_datalist");
    Route::post('payment_method_update',    'App\Http\Controllers\Settings\PaymentMethodController@paymentMethodUpdate')->name("payment_method_update");
    Route::post('payment_method_delete',    'App\Http\Controllers\Settings\PaymentMethodController@paymentMethodDelete')->name("payment_method_delete");

    Route::get('income-head',            'App\Http\Controllers\Settings\IncomeHeadController@index')->name("income-head");
    Route::post('save_income_head',      'App\Http\Controllers\Settings\IncomeHeadController@save_income_head');
    Route::post('income_head_datalist',  'App\Http\Controllers\Settings\IncomeHeadController@income_headList');
    Route::post('income_head_update',    'App\Http\Controllers\Settings\IncomeHeadController@income_headUpdate');
    Route::post('income_head_delete',    'App\Http\Controllers\Settings\IncomeHeadController@income_headDelete');

    Route::get('id-type',            'App\Http\Controllers\Settings\IDGeneratorController@idType')->name("id-type")->middleware("can:id-generator");
    Route::post('save_id_type',      'App\Http\Controllers\Settings\IDGeneratorController@save_idType')->name('save_id_type');
    Route::post('id_type_datalist',  'App\Http\Controllers\Settings\IDGeneratorController@idTypeList')->name('id_type_datalist');
    Route::post('id_type_update',    'App\Http\Controllers\Settings\IDGeneratorController@idTypeUpdate')->name('id_type_update');
    Route::post('id_type_delete',    'App\Http\Controllers\Settings\IDGeneratorController@idTypeDelete')->name('id_type_delete');

    Route::get('id-prefix',            'App\Http\Controllers\Settings\IDGeneratorController@idPrefix')->name("id-prefix")->middleware("can:id-generator");
    Route::post('save_id_prefix',      'App\Http\Controllers\Settings\IDGeneratorController@save_idPrefix')->name('save_id_prefix');
    Route::post('id_prefix_datalist',  'App\Http\Controllers\Settings\IDGeneratorController@idPrefixList')->name('id_prefix_datalist');
    Route::post('id_prefix_update',    'App\Http\Controllers\Settings\IDGeneratorController@idPrefixUpdate')->name('id_prefix_update');
    Route::post('id_prefix_delete',    'App\Http\Controllers\Settings\IDGeneratorController@idPrefixDelete')->name('id_prefix_delete');

    Route::get('salary-setting',                'App\Http\Controllers\Settings\SalarySettingController@index')->name("salary-setting")->middleware("can:salary-setting");
    Route::post('distribution-list',            'App\Http\Controllers\Settings\SalarySettingController@DistributionList')->name('distribution-list');
    Route::post('save_salary_setting',          'App\Http\Controllers\Settings\SalarySettingController@save_Salary_Setting')->name('save_salary_setting');
    Route::post('salary_setting_update',        'App\Http\Controllers\Settings\SalarySettingController@SalarySettingUpdate')->name('salary_setting_update');
    Route::post('salary_setting_delete',        'App\Http\Controllers\Settings\SalarySettingController@SalarySettingDelete')->name('salary_setting_delete');
    Route::post('salary_distribute_percent',    'App\Http\Controllers\Settings\SalarySettingController@availablePercent')->name('salary_distribute_percent');

    Route::get('sidebar-order',        'App\Http\Controllers\Settings\CompanySettingController@order_module')->name("sidebar-order");
    Route::post('sidebar-order-save',  'App\Http\Controllers\Settings\CompanySettingController@order_module_save')->name("sidebar-order-save");
    Route::get('company-setting',      'App\Http\Controllers\Settings\CompanySettingController@index')->name("company-setting");
    Route::post('company-setting',     'App\Http\Controllers\Settings\CompanySettingController@save')->name("company-setting-save");
   // });


    //Client
    Route::get('isp-clients',             'App\Http\Controllers\ClientController@index')->name("isp-clients")->middleware("can:isp-client-list");
    Route::get('printpdf',                'App\Http\Controllers\ClientController@printpdf')->name("printpdf");
    Route::get('isp-pppoe',               'App\Http\Controllers\ClientController@add_pppoe')->name("isp-pppoe");
    Route::get('isp-queue',               'App\Http\Controllers\ClientController@add_queue')->name("isp-queue");
    Route::post('save_client',            'App\Http\Controllers\ClientController@save_client')->name('save_client');
    Route::get('isp-client-update/{id}',  'App\Http\Controllers\ClientController@update')->name("isp-client-update");
    Route::post('client_datalist',        'App\Http\Controllers\ClientController@clientList')->name('client_datalist');
    Route::post('client_update',          'App\Http\Controllers\ClientController@clientUpdate')->name('client_update');
    Route::post('client_delete',          'App\Http\Controllers\ClientController@clientDelete')->name('client_delete');
    Route::post('get_client_id',          'App\Http\Controllers\ClientController@last_client_id')->name('get_client_id');
    Route::post('lockedUnlockedClient',   'App\Http\Controllers\ClientController@clientLockedUnlocked')->name('lockedUnlockedClient');
    Route::post('get_client_count',       'App\Http\Controllers\ClientController@clientCount')->name('get_client_count');
    Route::post('client_status_update',   'App\Http\Controllers\ClientController@clientStatusUpdate')->name('client_status_update');
    Route::post('client-widget',          'App\Http\Controllers\Clients\WidgetController@index')->name('client-widget');

    //client connection details
    Route::get('connection-details',    'App\Http\Controllers\Clients\ConnectionDetailsController@index')->name("connection-details");

    //Reseller
    Route::get('isp-reseller',       'App\Http\Controllers\ResellerController@index')->name("isp-reseller");
    Route::get('catv-reseller',      'App\Http\Controllers\ResellerController@index')->name("catv-reseller");
    Route::post('save_reseller',     'App\Http\Controllers\ResellerController@save_reseller')->name('save_reseller');
    Route::post('reseller_list',     'App\Http\Controllers\ResellerController@ResellerList')->name('reseller_list');
    Route::post('reseller_update',   'App\Http\Controllers\ResellerController@resellerUpdate')->name('reseller_update');
    Route::post('reseller_delete',   'App\Http\Controllers\ResellerController@resellerDelete')->name('reseller_delete');
    Route::post('get_reseller_id',   'App\Http\Controllers\ResellerController@last_reseller_id')->name('get_reseller_id');

    Route::get('upcoming-clients',          'App\Http\Controllers\ClientSupports\UpcomingClientController@index')->name("upcoming-clients");
    Route::post('save_upcoming_client',     'App\Http\Controllers\ClientSupports\UpcomingClientController@save_client');
    Route::post('upcoming_client_datalist', 'App\Http\Controllers\ClientSupports\UpcomingClientController@clientList');
    Route::post('upcoming_client_update',   'App\Http\Controllers\ClientSupports\UpcomingClientController@clientUpdate');
    Route::post('upcoming_client_delete',   'App\Http\Controllers\ClientSupports\UpcomingClientController@clientDelete');

    Route::get('tickets',                   'App\Http\Controllers\ClientSupports\TicketController@index')->name("tickets");
    Route::post('save_ticket',              'App\Http\Controllers\ClientSupports\TicketController@save_ticket')->name('save_ticket');
    Route::post('ticket_datalist',          'App\Http\Controllers\ClientSupports\TicketController@ticketList')->name('ticket_datalist');
    Route::post('ticket_close',             'App\Http\Controllers\ClientSupports\TicketController@ticketClose')->name('ticket_close');
    Route::post('view_ticket',              'App\Http\Controllers\ClientSupports\TicketController@ticketView')->name('view_ticket');
    Route::post('save_ticket_comment',      'App\Http\Controllers\ClientSupports\TicketController@post_ticket_comment')->name('save_ticket_comment');
    Route::post('pending_ticket_list',      'App\Http\Controllers\ClientSupports\TicketController@pending_ticket_list')->name('pending_ticket_list');
    Route::post('closed_ticket_list',       'App\Http\Controllers\ClientSupports\TicketController@closed_ticket_list')->name('closed_ticket_list');

    Route::get('package-change',            'App\Http\Controllers\ClientSupports\PackageChangeController@index')->name("package-change");
    Route::post('package_client',           'App\Http\Controllers\ClientSupports\PackageChangeController@package_client')->name("package_client");
    Route::post('all_package',              'App\Http\Controllers\ClientSupports\PackageChangeController@all_package')->name("all_package");
    Route::post('migrate_new_package',      'App\Http\Controllers\ClientSupports\PackageChangeController@migrate_new_package')->name("migrate_new_package");

    Route::get('line-shift',                'App\Http\Controllers\ClientSupports\LineShiftController@index')->name("line-shift");
    Route::post('save_line_shift',          'App\Http\Controllers\ClientSupports\LineShiftController@save_line_shift')->name('save_line_shift');

    //accounts
    Route::get('expense',               'App\Http\Controllers\Accounts\PayableBillController@index')->name("expense");
    Route::post('save_expense',         'App\Http\Controllers\Accounts\PayableBillController@save_expense')->name('save_expense');
    Route::post('expense_datalist',     'App\Http\Controllers\Accounts\PayableBillController@expenseDataTable')->name('expense_datalist');
    Route::post('expense_update',       'App\Http\Controllers\ExpenseController@expenseUpdate')->name('expense_update');
    Route::post('expense_approve',      'App\Http\Controllers\ExpenseController@expenseApprove')->name('expense_approve');
    Route::post('save_expense_head',    'App\Http\Controllers\ExpenseController@save_expense_head')->name('save_expense_head');
    Route::post('expense_head_datalist','App\Http\Controllers\ExpenseController@expenseHeadList')->name('expense_head_datalist');
    Route::post('expense_head_update',  'App\Http\Controllers\ExpenseController@expenseHeadUpdate')->name('expense_head_update');
    Route::post('expense_head_delete',  'App\Http\Controllers\ExpenseController@expenseHeadDelete')->name('expense_head_delete');
    Route::post('expenseHeadList',      'App\Http\Controllers\ExpenseController@expense_head_list')->name('expenseHeadList');

    Route::get('employee-list',                 'App\\Http\\Controllers\\EmployeeController@index')->name("employee-list");
    Route::post('save_employee',                'App\Http\Controllers\EmployeeController@save_employee')->name('save_employee');
    Route::post('employee_datalist',            'App\Http\Controllers\EmployeeController@employeeList')->name('employee_datalist');
    Route::post('employee_update',              'App\Http\Controllers\EmployeeController@employeeUpdate')->name('employee_update');
    Route::post('employee_delete',              'App\Http\Controllers\EmployeeController@employeeDelete')->name('employee_delete');
    Route::post('next_emp_id',                  'App\Http\Controllers\EmployeeController@next_emp_id')->name('next_emp_id');

    Route::get('emp-salary',                    'App\Http\Controllers\SalaryController@index')->name("emp-salary");
    Route::post('employee_list',                'App\Http\Controllers\SalaryController@employee_list')->name('employee_list');
    Route::post('search_individual_salary',     'App\Http\Controllers\SalaryController@searchIndividualSalary')->name('search_individual_salary');
    Route::post('save_monthly_salary',          'App\Http\Controllers\SalaryController@saveMonthlySalary')->name('save_monthly_salary');
    Route::post('save_advanced_salary',         'App\Http\Controllers\ExpenseController@save_advanced_salary')->name('save_advanced_salary');
    Route::post('check_previous_advanced_salary','App\Http\Controllers\ExpenseController@check_previous_advanced_salary')->name('check_previous_advanced_salary');
    Route::post('get_adv_salary',               'App\Http\Controllers\ExpenseController@get_adv_salary')->name('get_adv_salary');

    Route::get('emp-liability',                'App\Http\Controllers\EmpStorageController@index')->name("emp-liability");
    Route::post('save_emp_store',              'App\Http\Controllers\EmpStorageController@save_store')->name('save_emp_store');
    Route::post('emp_liability_list',          'App\Http\Controllers\EmpStorageController@storeList')->name('emp_liability_list');
    Route::post('emp_liability_update',        'App\Http\Controllers\EmpStorageController@empLiabilityUpdate')->name('emp_liability_update');
    Route::post('emp_store_delete',            'App\Http\Controllers\EmpStorageController@delete')->name('emp_store_delete');
    Route::post('emp_liability_view',          'App\Http\Controllers\EmpStorageController@liabilityView')->name('emp_liability_view');

    Route::get('salary-distribution',       'App\Http\Controllers\SalaryController@index');
    Route::post('get_employee_list',        'App\Http\Controllers\SalaryController@getEmployeeList');
    Route::post('save_salary_distribution', 'App\Http\Controllers\SalaryController@saveSalaryDistribution');
    Route::get('individual-salary',         'App\Http\Controllers\SalaryController@individualSalarySheet');
    Route::post('salary_sheet_datalist',    'App\Http\Controllers\SalaryController@salarySheetDataList');
    Route::post('salary_sheet_show',        'App\Http\Controllers\SalaryController@salarySheetDisplay');
    Route::post('salary_approve',           'App\Http\Controllers\SalaryController@salaryApproved');
    Route::post('salary-sheet-print',       'App\Http\Controllers\SalaryController@salarySheetPrint')->name('salary-sheet-print');

    //reports
    Route::get('due-bill',                          [App\Http\Controllers\Reports\DueBillController::class,'index'])->name("due-bill");

    Route::post('due-bill-filter',                  'App\Http\Controllers\Reports\DueBillController@filterDueBill')->name("due-bill-filter");
    Route::post('download-due-pdf',                 [App\Http\Controllers\Reports\DueBillController::class,'downloadPDF'])->name("download-due-pdf");
    Route::get('income-statement',                  'App\Http\Controllers\Reports\IncomeController@index')->name("income-statement");
    Route::post('search_income_statement',          'App\Http\Controllers\Reports\IncomeController@search_income_statement')->name('search_income_statement');
    Route::post('expense_details',                  'App\Http\Controllers\Reports\IncomeController@expense_details')->name('expense_details');
    Route::get('expense-report',                    'App\Http\Controllers\Reports\ExpenseReportController@index')->name("expense-report");
    Route::post('search_expense_report',            'App\Http\Controllers\Reports\ExpenseReportController@search_expense_report')->name('search_expense_report');
    Route::post('download-expense-pdf',             'App\Http\Controllers\Reports\ExpenseReportController@downloadPDF')->name("download-expense-pdf");
    Route::get('client-collection',                 'App\Http\Controllers\Reports\ClientCollectionController@index')->name("client-collection");
    Route::post('search_collection_report',         'App\Http\Controllers\Reports\ClientCollectionController@searchCollectionReport')->name('search_collection_report');
    Route::post('client-collection-pdf',            'App\Http\Controllers\Reports\ClientCollectionController@downloadPDF')->name("client-collection-pdf");
    Route::get('client-collection-summery',         'App\Http\Controllers\Reports\ClientCollectionSummeryController@index')->name("client-collection-summery");
    Route::post('search_client_collection_summery', 'App\Http\Controllers\Reports\ClientCollectionSummeryController@searchCollectionReport')->name('search_client_collection_summery');
    Route::post('client-collection-summery-pdf',    'App\Http\Controllers\Reports\ClientCollectionSummeryController@downloadPDF')->name("client-collection-summery-pdf");
    Route::get('bill-generate-report',              'App\Http\Controllers\Reports\BillsReportController@index')->name("bill-generate-report");
    Route::post('bill-generate-report',             'App\Http\Controllers\Reports\BillsReportController@filter')->name("bill-generate-report");
    Route::get('salary-report',                     'App\Http\Controllers\SalaryController@salary_report')->name("salary-report");
    Route::post('salary-report',                    'App\Http\Controllers\SalaryController@salary_report_filter')->name("salary-report");
    Route::get('isp/client-list',                   'App\Http\Controllers\Reports\BillsReportController@isp_client')->name("isp-client-list");
    Route::post('isp/client-list',                  'App\Http\Controllers\Reports\BillsReportController@isp_client_search')->name("isp-client-list");
    Route::post('download-isp-client-pdf',          'App\Http\Controllers\Reports\BillsReportController@isp_client_search')->name("download-isp-client-pdf");

    //catv reports
    Route::get('catv-collection',                 'App\Http\Controllers\Reports\CATVCollectionController@index')->name("catv-collection");
    Route::get('catv-graph',                      'App\Http\Controllers\Reports\CATVCollectionController@graph')->name("catv-graph");
    Route::post('search_catv_collection_rpt',     'App\Http\Controllers\Reports\CATVCollectionController@searchCollectionReport')->name('search_catv_collection_rpt');
    Route::post('catv-collection-pdf',            'App\Http\Controllers\Reports\CATVCollectionController@downloadPDF')->name("catv-collection-pdf");
    Route::get('catv-collection-summery',         'App\Http\Controllers\Reports\CATVCollectionSummeryController@index')->name("catv-collection-summery");
    Route::post('search_catv_collection_summery', 'App\Http\Controllers\Reports\CATVCollectionSummeryController@searchCollectionReport')->name('search_catv_collection_summery');
    Route::post('catv-collection-summery-pdf',    'App\Http\Controllers\Reports\CATVCollectionSummeryController@downloadPDF')->name("catv-collection-summery-pdf");
    //end catv reports


    Route::get('modules',           'App\Http\Controllers\Access\ModuleController@index')->name("modules");
    Route::post('module_list',      'App\Http\Controllers\Access\ModuleController@moduleList')->name("module_list");
    Route::post('save_module',      'App\Http\Controllers\Access\ModuleController@save_module')->name("save_module");
    Route::post('update_module',    'App\Http\Controllers\Access\ModuleController@updateModule')->name("update_module");
    Route::post('sub_module_list',  'App\Http\Controllers\Access\ModuleController@subModuleList')->name("sub_module_list");
    Route::post('save_sub_module',  'App\Http\Controllers\Access\ModuleController@saveSubModule')->name("save_sub_module");
    Route::post('update_sub_module','App\Http\Controllers\Access\ModuleController@updateSubModule')->name("update_sub_module");
    Route::post('delete_module',    'App\Http\Controllers\Access\ModuleController@deleteModule')->name("delete_module");
    Route::get('access-permission', 'App\Http\Controllers\Access\AccessController@index')->name("access-permission");
    Route::post('permission',       'App\Http\Controllers\Access\AccessController@modulePermission')->name("permission");
    Route::post('get_permission',   'App\Http\Controllers\Access\AccessController@get_permission')->name("get_permission");
    Route::post('save_permission',  'App\Http\Controllers\Access\AccessController@savePermission')->name("save_permission");

    //sms
    Route::get('send-sms',          'App\Http\Controllers\SMS\SMSController@index')->name("send-sms");
    Route::get('send-sms/{type}',   'App\Http\Controllers\SMS\SMSController@index')->name("send-sms");
    Route::post('sms_client_list',  'App\Http\Controllers\SMS\SMSController@sms_client_list')->name('sms_client_list');
    Route::post('sms_preview',      'App\Http\Controllers\SMS\SMSController@sms_preview')->name('sms_preview');
    Route::post('save_sms',         'App\Http\Controllers\SMS\SMSController@save_sms')->name('save_sms');
    Route::post('send_sms_from_client',    'App\Http\Controllers\SMS\SMSController@send_sms_from_client')->name('send_sms_from_client');
    Route::post('sms_json_data',    'App\Http\Controllers\SMS\SMSController@json_data')->name('sms_json_data');

    Route::get('sms-history',       'App\Http\Controllers\SMS\SMSHistoryController@index')->name("sms-history");
    Route::post('sms-history',      'App\Http\Controllers\SMS\SMSHistoryController@search_sms_history')->name('sms-history');
    Route::post('sms-update',       'App\Http\Controllers\SMS\SMSHistoryController@smsStatusUpdate')->name('sms-update');
    Route::post('send_general_sms', 'App\Http\Controllers\SMS\SMSController@send_general_sms')->name('send_general_sms');
    Route::get('sms-report',      'App\Http\Controllers\SMS\SMSHistoryController@report')->name('sms-report');
    Route::post('sms-report',      'App\Http\Controllers\SMS\SMSHistoryController@search_report')->name('sms-report');

    Route::get('sms_test',  [App\Http\Controllers\SMS\SMSController::class,'test_page'])->name("sms_test");
    Route::post('sms_test',  [App\Http\Controllers\SMS\SMSController::class,'test'])->name("sms_test");

    //sms template
    Route::get('sms-template',         'App\Http\Controllers\SMS\SMSTemplateController@index')->name("sms-template");
    Route::post('save_sms_template',   'App\Http\Controllers\SMS\SMSTemplateController@save_SMSTemplate')->name("save_sms_template");
    Route::post('sms_template_list',   'App\Http\Controllers\SMS\SMSTemplateController@SMSTemplateList')->name("sms_template_list");
    Route::post('sms_template_update', 'App\Http\Controllers\SMS\SMSTemplateController@SMSTemplateUpdate')->name("sms_template_update");
    Route::post('sms_template_delete', 'App\Http\Controllers\SMS\SMSTemplateController@SMSTemplateDelete')->name("sms_template_delete");


    //stock product controller
    Route::get('store-product',      'App\Http\Controllers\Store\ProductController@index')->name("store-product");
    Route::post('save_product',      'App\Http\Controllers\Store\ProductController@productSave')->name('save_product');
    Route::post('product_datalist',  'App\Http\Controllers\Store\ProductController@productList')->name('product_datalist');
    Route::post('product_update',    'App\Http\Controllers\Store\ProductController@productUpdate')->name('product_update');
    Route::post('product_delete',    'App\Http\Controllers\Store\ProductController@productDelete')->name('product_delete');
    Route::post('product_list_show', 'App\Http\Controllers\Store\ProductController@productListShow')->name('product_list_show');

    Route::get('product-brand',         'App\Http\Controllers\Store\BrandController@index')->name("product-brand");
    Route::post('save_product_brand',   'App\Http\Controllers\Store\BrandController@productBrandSave')->name('save_product_brand');
    Route::post('product_brand_list',   'App\Http\Controllers\Store\BrandController@productBrandList')->name('product_brand_list');
    Route::post('product_brand_update', 'App\Http\Controllers\Store\BrandController@productBrandUpdate')->name('product_brand_update');
    Route::post('product_brand_delete', 'App\Http\Controllers\Store\BrandController@productBrandDelete')->name('product_brand_delete');
    Route::post('product_brand_show',   'App\Http\Controllers\Store\BrandController@productBrandShow')->name('product_brand_show');

    Route::get('purchase-product',     'App\Http\Controllers\Store\ProductPurchaseController@index')->name("purchase-product");
    Route::post('new_product_purchase','App\Http\Controllers\Store\ProductPurchaseController@purchase_product')->name('new_product_purchase');

    Route::get('store-record',      'App\Http\Controllers\Store\StoreRecordController@index')->name("store-record");
    Route::post('store_record',     'App\Http\Controllers\Store\StoreRecordController@store_record')->name('store_record');

    Route::get('store-requisition',       'App\Http\Controllers\Store\StoreRequisitionController@index')->name("store-requisition");
    Route::post('get_available_product',  'App\Http\Controllers\Store\StoreRequisitionController@available_product')->name('get_available_product');
    Route::post('requisition_product',    'App\Http\Controllers\Store\StoreRequisitionController@requisition_product')->name('requisition_product');


    Route::get('catv-clients',         [App\Http\Controllers\CatBClientsController::class,'index'])->name("catv-clients");
    Route::resource('catb-clients',    App\Http\Controllers\CatBClientsController::class);
    Route::post('catb_client_update',  [App\Http\Controllers\CatBClientsController::class,'edit'])->name('catb_client_update');
    Route::post('get_catv_client_id',  [App\Http\Controllers\CatBClientsController::class,'last_client_id'])->name('get_catv_client_id');
    Route::post('catb_client_list',    [App\Http\Controllers\CatBClientsController::class,'clientList'])->name('catb_client_list');
    Route::post('catb_client_del',     [App\Http\Controllers\CatBClientsController::class,'destroy'])->name('catb_client_del');
    Route::post('catv_client_count',   [App\Http\Controllers\CatBClientsController::class,'clientCount'])->name('catv_client_count');


    Route::get('catv-station-excel',      [App\Http\Controllers\Excel\CATVStationController::class,'stationImport'])->name("catv-station-excel");
    Route::post('catv-station-import',    [App\Http\Controllers\Excel\CATVStationController::class,'fileImport'])->name("catv-station-import");
    Route::post('catv-client-import',     [App\Http\Controllers\CatBClientsController::class,'catv_client_import'])->name("catv-client-import");
    Route::post('isp-client-import',[App\Http\Controllers\ClientController::class,'isp_client_import'])->name("isp-client-import");
    Route::post('isp_client_import_pppoe_save',[App\Http\Controllers\ClientController::class,'isp_client_import_pppoe_save'])->name("isp_client_import_pppoe_save");
    Route::post('isp_client_import_queue_save',[App\Http\Controllers\ClientController::class,'isp_client_import_queue_save'])->name("isp_client_import_queue_save");
    
    
    Route::get('custom-edit',             'App\Http\Controllers\ClientSupports\ClientCustomUpdate@index')->name("custom-edit");
    Route::post('custom-edit',            'App\Http\Controllers\ClientSupports\ClientCustomUpdate@search')->name("custom-edit");
    Route::post('custom-client-edit-save','App\Http\Controllers\ClientSupports\ClientCustomUpdate@save')->name("custom-client-edit-save");

    
    //accounts
    Route::get('account-balance',         'App\Http\Controllers\Accounts\AccountsController@balance_page')->name("account-balance");
    Route::post('search_account_balance', 'App\Http\Controllers\Accounts\AccountsController@search_account_balance')->name("search_account_balance");
    Route::get('accounts',                  'App\Http\Controllers\Accounts\AccountsController@index')->name("accounts");
    Route::post('accounts_dashboard_data',  [App\Http\Controllers\Accounts\AccountsController::class,'dashboard'])->name("accounts_dashboard");



});

Route::get('auto-bill',                 'App\Http\Controllers\AutoBillController@index');
Route::get('catv-auto-bill/{month}',    'App\Http\Controllers\CatBBillController@auto_bill');


//Route::prefix('super')->middleware('auth')->group(function () {
//    Route::get('/dashboard',                 'App\Http\Controllers\Super\DashboardController@index')->name("super.dashboard");
//});

Route::get('router-disabled',      'App\Http\Controllers\MikrotikController@network_disabled')->name('router-disabled');
Route::get('router',      'App\Http\Controllers\MikrotikController@connect_network_test')->name('test_network');
Route::get('clear', function (){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});