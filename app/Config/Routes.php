<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
 /********Get Methods************/
 //Login
$routes->get('/', 'Login::index');
$routes->get('/sync/', 'Login::login/');
$routes->get('/sync/(:any)', 'Login::login/$1');
$routes->get('/login/signout', 'Login::signout');
//Dashboard
$routes->get('dashboard', 'Dashboard::index');
//Flats
$routes->get('flats', 'Flats::index');
$routes->get('flats/(:any)', 'Flats::index/$1');
//Roles
$routes->get('roles', 'Roles::index');
$routes->get('roles/(:any)', 'Roles::index/$1');
//Residents
$routes->get('residents', 'Residents::index');
$routes->get('residents/(:any)', 'Residents::index/$1');
//Account types
$routes->get('accounts', 'Accounts::index');
$routes->get('accounts/(:any)', 'Accounts::index/$1');
//Ledger
$routes->get('ledger', 'Ledger::index');
$routes->get('ledger/(:any)', 'Ledger::index/$1');
//Receipts
$routes->get('receipts', 'Receipts::index');
//Journal
$routes->get('journals', 'Journals::index');
//Trial Balance
$routes->get('trialbalance', 'TrialBalance::index');
//Profit & Loss
$routes->get('profitandloss', 'ProfitAndLoss::index');
//Payments
$routes->get('payments', 'Payments::index');
//Contra
$routes->get('contra', 'Contra::index');
//Chart of accounts
$routes->get('chartofaccounts', 'ChartOfAccounts::index');
//Balance Sheet
$routes->get('balancesheet', 'Balancesheet::index');
//Income Vs Expense
$routes->get('ïncomevsexpense', 'IncomeVsExpense::index');
//Payment Request
$routes->get('paymentrequest', 'PaymentRequest::index');
$routes->get('paymentrequest/(:any)', 'PaymentRequest::index/$1');
//Complaints & Informations
$routes->get('complaintsinfo', 'ComplaintsInfo::index');
$routes->get('complaintsinfo/(:any)', 'ComplaintsInfo::index/$1');

//Announcement
$routes->get('announcements', 'Announcements::index');
$routes->get('announcements/(:any)', 'Announcements::index/$1');
//Gatepass
$routes->get('visitorgatepass/access/(:any)', 'VisitorGatepass::access/$1');
$routes->get('visitorgatepass', 'VisitorGatepass::index');
$routes->get('visitorgatepass/mygatepass/(:any)', 'VisitorGatepass::shareGatePass/$1');
$routes->get('mygatepass/(:any)', 'Mygatepass::index/$1');
//Security
$routes->get('securities', 'Securities::index');
$routes->get('securities/(:any)', 'Securities::index/$1');
//Messages
$routes->get('residentsmsg', 'ResidentsMsg::index');


/**************Post Methods***********/

$routes->post('login/authinticate', 'Login::signin');

//Flats
$routes->post('flats/listflats', 'Flats::listflats');
$routes->post('flats/createnew', 'Flats::createnew');
$routes->post('flats/changeStatus', 'Flats::changeStatus');
$routes->post('flats/deleteFlat/(:any)', 'Flats::deleteFlat/$1');
//Roles
$routes->post('roles/createnew', 'Roles::createnew');
$routes->post('roles/listroles', 'Roles::listroles');
$routes->post('roles/deleteRoles/(:any)', 'Roles::deleteRoles/$1');
$routes->post('roles/changeStatus', 'Roles::changeStatus');
//Residents
$routes->post('residents/verifyaadhaar', 'Residents::verifyaadhaar');
$routes->post('residents/acceptUser', 'Residents::acceptUser');
$routes->post('residents/sendotp', 'Residents::sendotp');
$routes->post('residents/verifyotp', 'Residents::verifyotp');
$routes->post('residents/updateuser', 'Residents::updateuser');
$routes->post('residents/listresidents', 'Residents::listresidents');
$routes->post('residents/changeuser', 'Residents::changeuser');
$routes->post('residents/getUser', 'Residents::getUser');
$routes->post('residents/changeStatus', 'Residents::changeStatus');
$routes->post('residents/deleteUser/(:any)', 'Residents::deleteUser/$1');
$routes->post('residents/userDetails', 'Residents::userDetails');
$routes->post('residents/addnewdoor', 'Residents::addnewdoor');
$routes->post('residents/listdoor', 'Residents::listdoor');
$routes->post('residents/deleteDoor/(:any)', 'Residents::deleteDoor/$1');
//Account types
$routes->post('accounts/listacctype', 'Accounts::listacctype');
$routes->post('accounts/createnew', 'Accounts::createnew');
$routes->post('accounts/changeStatus', 'Accounts::changeStatus');
$routes->post('accounts/deleteAccType/(:any)', 'Accounts::deleteAccType/$1');
//Ledger
$routes->post('ledger/listledger', 'Ledger::listledger');
$routes->post('ledger/createnew', 'Ledger::createnew');
$routes->post('ledger/changeStatus', 'Ledger::changeStatus');
$routes->post('ledger/deleteLedger/(:any)', 'Ledger::deleteLedger/$1');
$routes->post('ledger/openledger', 'Ledger::openledger');
$routes->post('ledger/subheadlist', 'Ledger::subheadlist');
//Receipts
$routes->post('receipts/listledgers', 'Receipts::listledgers');
$routes->post('receipts/savereceipt', 'Receipts::savereceipt');
//Journal
$routes->post('journals/loadjournal', 'Journals::loadjournal');
//Trial Balance
$routes->post('trialbalance/loadtrialbalance', 'TrialBalance::loadtrialbalance');
//Profit & Loss
$routes->post('profitandloss/loadpl', 'ProfitAndLoss::loadpl');
//Payments
$routes->post('payments/listledgers', 'Payments::listledgers');
$routes->post('payments/savereceipt', 'Payments::savereceipt');
//Contra
$routes->post('contra/listledgers', 'Contra::listledgers');
$routes->post('contra/savereceipt', 'Contra::savereceipt');
//Sub heade
$routes->post('chartofaccounts/savesubhead', 'ChartOfAccounts::savesubhead');
$routes->post('chartofaccounts/getallsubheads', 'ChartOfAccounts::getallsubheads');
$routes->post('chartofaccounts/deleteSubhead/(:any)', 'ChartOfAccounts::deleteSubhead/$1');
$routes->post('chartofaccounts/loadCharts', 'ChartOfAccounts::loadCharts');
$routes->post('chartofaccounts/updatesubhead', 'ChartOfAccounts::updatesubhead');
$routes->post('chartofaccounts/loadLedgers', 'ChartOfAccounts::loadLedgers');
//Balance Sheet
$routes->post('balancesheet/loadBalanceSheet', 'Balancesheet::loadBalanceSheet');
//Income Vs Expense
$routes->post('incomevsexpense/loadIncomeExpense', 'IncomeVsExpense::loadIncomeExpense');
//Payment Request
$routes->post('paymentrequest/savepayrequest', 'PaymentRequest::savepayrequest');
$routes->post('paymentrequest/listpayrequest', 'PaymentRequest::listpayrequest');
$routes->post('paymentrequest/getResidents', 'PaymentRequest::getResidents');
$routes->post('paymentrequest/changeStatus', 'PaymentRequest::changeStatus');
$routes->post('paymentrequest/deletePayrequest/(:any)', 'PaymentRequest::deletePayrequest/$1');
//Complaints & Informations
$routes->post('complaintsinfo/getResidents', 'ComplaintsInfo::getResidents');
$routes->post('complaintsinfo/loadComplaints', 'ComplaintsInfo::loadComplaints');
$routes->post('complaintsinfo/saveComplaint', 'ComplaintsInfo::saveComplaint');
$routes->post('complaintsinfo/changeStatus', 'ComplaintsInfo::changeStatus');
$routes->post('complaintsinfo/deleteComplaint/(:any)', 'ComplaintsInfo::deleteComplaint/$1');
//Gatepass
$routes->post('visitorgatepass/getPass', 'VisitorGatepass::getPass');
$routes->post('visitorgatepass/createnew', 'VisitorGatepass::createnew');
$routes->post('visitorgatepass/listgatepass', 'VisitorGatepass::listgatepass');
$routes->post('visitorgatepass/savepassimg', 'VisitorGatepass::savepassimg');
$routes->post('visitorgatepass/getPassDetails', 'VisitorGatepass::getPassDetails');
$routes->post('visitorgatepass/deletePass/(:any)', 'VisitorGatepass::deletePass/$1');
//Security
$routes->post('securities/createnew', 'Securities::createnew');
$routes->post('securities/searchsecurity', 'Securities::searchsecurity');
$routes->post('securities/listsecurities', 'Securities::listsecurities');
$routes->post('securities/deleteSecurity/(:any)', 'Securities::deleteSecurity/$1');
$routes->post('securities/listfeedbacks', 'Securities::listfeedbacks');


/********Bala Routes starts here***************/

 //Key authendicate
 $routes->post('/authenticate', 'Login::authenticate');

//Replycomplaints
$routes->post('complaintsinfo/saveReply', 'ComplaintsInfo::saveReply');
$routes->get('viewdetails/(:num)', 'ComplaintsInfo::viewDetails/$1');

//Category

$routes->get('category', 'Category::index');
$routes->get('category/(:num)', 'Category::index/$1');
$routes->get('category/loadCategories','Category::loadCategories');
$routes->post('category/save','Category::saveCategory');
$routes->post('category/delete', 'Category::deleteCategory');
$routes->post('subcategory/delete', 'Category::deleteSubcategory');
$routes->post('category/getDiminishRate', 'Category::getDiminishRate');

//Announcements

$routes->post('announcements/listannouncement', 'Announcements::listannouncement');
$routes->post('announcements/createnew', 'Announcements::createnew');
$routes->post('announcements/changeStatus', 'Announcements::changeStatus');
$routes->post('announcements/deleteAnnouncement/(:any)', 'Announcements::deleteAnnouncement/$1');
$routes->post('announcements/toggleStatus', 'Announcements::toggleStatus');



/**********Bala Routes End Here *************/




/**************Customer Mobile Api's***********/

//verify mpin
$routes->post('usermobile/verifympinapi', 'UserMobile\Login::verifympinapi');
$routes->post('usermobile/loginapi', 'UserMobile\Login::loginapi');
//verify mpin
$routes->post('usermobile/verifympinapi', 'UserMobile\Login::verifympinapi');
//Create Update check mpin
$routes->post('usermobile/createMpin', 'UserMobile\Login::createMpin');
$routes->post('usermobile/changeMpin', 'UserMobile\Login::changeMpin');
$routes->post('usermobile/checkeMpin', 'UserMobile\Login::checkeMpin');

//Profile
$routes->post('usermobile/login/getprofile', 'UserMobile\Login::getprofile');
//Residence
$routes->post('usermobile/residence/getResidences', 'UserMobile\Residence::getResidences');
//Payments
$routes->post('usermobile/payments/getPaymentsRequest', 'UserMobile\Payments::getPaymentsRequest');
$routes->post('usermobile/payments/getPaymentDetails', 'UserMobile\Payments::getPaymentDetails');
$routes->post('usermobile/payments/updatePayment', 'UserMobile\Payments::updatePayment');
//Notification
$routes->post('usermobile/notifications/getnotifications', 'UserMobile\Notifications::getnotifications');
$routes->post('usermobile/notifications/processNotify', 'UserMobile\Notifications::processNotify');


//complaints
$routes->post('usermobile/complaints/getcomplaints', 'UserMobile\Complaints::getComplaints');
$routes->post('usermobile/complaints/savecomplaints','UserMobile\Complaints::saveComplaints');
 
 
//gatepass
$routes->post('usermobile/gatepass/getgatepass','UserMobile\VisitorGatepass::getGatepass');
$routes->post('usermobile/gatepass/savegatepass','UserMobile\VisitorGatepass::createnew');
 
 

// Announcements
$routes->post('usermobile/announcements/getAnnouncements', 'UserMobile\Announcement::getAnnouncements');
 
// Mark In Mark Out
$routes->post('usermobile/security/attendance', 'UserMobile\SecurityAttendance::recordAttendance');


$routes->post('security/usermobile/loginapi', 'UserMobile\Security::loginapi');
$routes->post('security/usermobile/createMpin', 'UserMobile\Security::createMpin');
$routes->post('security/usermobile/verifympinapi', 'UserMobile\Security::verifympinapi');
$routes->post('security/usermobile/checkMpin', 'UserMobile\Security::checkMpin');
$routes->post('security/usermobile/changeMpin', 'UserMobile\Security::changeMpin');

