<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Favela - Organize Your Residence | Version 1.0.0</title>
    <meta name="robots" content="noindex">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('public/favicon.ico'); ?>">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i,800" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/bootstrap.min.css'); ?>">
    <!-- Bootstrap CSS
		============================================ -->
    <!--<link rel="stylesheet" href="<?php echo base_url('public/css/font-awesome.min.css'); ?>">-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- adminpro icon CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/adminpro-custon-icon.css'); ?>">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/meanmenu.min.css'); ?>">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/jquery.mCustomScrollbar.min.css'); ?>">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/animate.css'); ?>">
    <!-- data-table CSS
		============================================ -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/normalize.css'); ?>">
    <!-- charts C3 CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/c3.min.css'); ?>">
    <!-- forms CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/form/all-type-forms.css'); ?>">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/style.css'); ?>">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/responsive.css'); ?>">
    <!-- modernizr JS
		============================================ -->
    <link rel="stylesheet" href="<?php echo base_url('public/css/custom.css'); ?>">
    <!-- custom CSS
	============================================ -->
    <!-- Chat Box End-->
    <script src="<?php echo base_url('public/js/vendor/modernizr-2.8.3.min.js'); ?>"></script>
</head>

<body class="materialdesign">
    <!-- Header top area start-->
    <div class="wrapper-pro">
        <div class="content-inner-all">
            <div class="header-top-area">
                <div class="fixed-header-top">
                    <div class="f-alert">testing alert message</div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-9 col-md-1 col-sm-1 col-xs-12 logo-menu">
                                <div class="header-top-menu tabl-d-n">
                                    <ul class="nav navbar-nav mai-top-nav">
                                        <li class="white-bg"><a href="javascript:void(0);"
                                                data-url="<?php echo base_url('dashboard'); ?>"
                                                class="nav-link logo-block"><img
                                                    src="<?php echo base_url('public/img/logo.png'); ?>" /></a>
                                        <li class="nav-item" title="Dashboard"
                                            data-url="<?php echo base_url('dashboard'); ?>">
                                            <a href="javascript:void(0);"
                                                class="nav-link mu-rt-bd <?php echo ($menu==1 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-tachometer" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="nav-item" title="Manage Flats"
                                            data-url="<?php echo base_url('flats'); ?>">
                                            <a href="javascript:void(0);"
                                                class="nav-link mu-rt-bd <?php echo ($menu==2 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-building-o" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="nav-item" title="Manage App User Roles"
                                            data-url="<?php echo base_url('roles'); ?>">
                                            <a href="javascript:void(0);"
                                                class="nav-link mu-rt-bd <?php echo ($menu==3 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-dot-circle-o" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="nav-item" title="Manage Residents"
                                            data-url="<?php echo base_url('residents'); ?>">
                                            <a href="javascript:void(0);"
                                                class="nav-link mu-rt-bd <?php echo ($menu==4 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-user" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="nav-item" title="Manage Securities"
                                            data-url="<?php echo base_url('securities'); ?>">
                                            <a href="javascript:void(0);"
                                                class="nav-link mu-rt-bd <?php echo ($menu==13 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-user-secret" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="nav-item" title="Complaints & Informations"
                                            data-url="<?php echo base_url('complaintsinfo'); ?>">
                                            <a href="javascript:void(0);"
                                                class="nav-link mu-rt-bd <?php echo ($menu==5 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-exclamation-circle" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="nav-item" title="Gate Pass"
                                            data-url="<?php echo base_url('visitorgatepass'); ?>">
                                            <a href="javascript:void(0);"
                                                class="nav-link mu-rt-bd <?php echo ($menu==6 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-ticket" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="nav-item dropdown" title="Asset Management">
                                            <a href="javascript:void(0);" data-toggle="dropdown" role="button"
                                                aria-expanded="false"
                                                class="nav-link dropdown-toggle mu-rt-bd <?php echo ($menu==14 ? 'mu-active' : ''); ?>">
                                                <i class="fa fa-th-large" aria-hidden="true"></i>
                                                 <span
                                                    class="angle-down-topmenu"><i
                                                        class="fa fa-angle-down"></i></span></a>
                                            <div role="menu" class="dropdown-menu  flipInX">
                                                <a href="<?php echo base_url('category'); ?>"
                                                    class="dropdown-item">Asset Category</a>
                                                <a href="#"
                                                    class="dropdown-item">Assets</a>    
                                            </div>
                                        </li>
                                          
                                        <li class="nav-item" title="Residents Messages"
                                            data-url="<?php echo base_url('residentsmsg'); ?>">
                                            <a href="javascript:void(0);"
                                                class="nav-link mu-rt-bd <?php echo ($menu==7 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-commenting" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="nav-item dropdown" title="Finance & Operations">
                                            <a href="javascript:void(0);" data-toggle="dropdown" role="button"
                                                aria-expanded="false"
                                                class="nav-link dropdown-toggle mu-rt-bd <?php echo ($menu==8 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-money" aria-hidden="true"></i> <span
                                                    class="angle-down-topmenu"><i
                                                        class="fa fa-angle-down"></i></span></a>
                                            <div role="menu" class="dropdown-menu  flipInX">
                                                <a href="<?php echo base_url('paymentrequest'); ?>"
                                                    class="dropdown-item">Payment Request <span
                                                        class="shtcut">F1</span></a>
                                                <a href="<?php echo base_url('contra'); ?>" class="dropdown-item">Contra
                                                    <span class="shtcut">F4</span></a>
                                                <a href="<?php echo base_url('payments'); ?>"
                                                    class="dropdown-item">Payments <span class="shtcut">F5</span></a>
                                                <a href="<?php echo base_url('receipts'); ?>"
                                                    class="dropdown-item">Receipts <span class="shtcut">F6</span></a>
                                            </div>
                                        </li>
                                        <li class="nav-item dropdown" title="Book Of Accounts">
                                            <a href="javascript:void(0);" data-toggle="dropdown" role="button"
                                                aria-expanded="false"
                                                class="nav-link dropdown-toggle mu-rt-bd <?php echo ($menu==9 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-book" aria-hidden="true"></i> <span
                                                    class="angle-down-topmenu"><i
                                                        class="fa fa-angle-down"></i></span></a>
                                            <div role="menu" class="dropdown-menu  flipInX">
                                                <a href="<?php echo base_url('accounts'); ?>"
                                                    class="dropdown-item">Account Types</a>
                                                <a href="<?php echo base_url('chartofaccounts'); ?>"
                                                    class="dropdown-item">Chart Of Accounts</a>
                                                <a href="<?php echo base_url('journals'); ?>"
                                                    class="dropdown-item">Journal</a>
                                                <a href="<?php echo base_url('ledger'); ?>"
                                                    class="dropdown-item">Ledger</a>
                                                <a href="<?php echo base_url('trialbalance'); ?>"
                                                    class="dropdown-item">Trial Balance</a>
                                                <a href="<?php echo base_url('profitandloss'); ?>"
                                                    class="dropdown-item">Profit & Loss Statement</a>
                                                <a href="<?php echo base_url('balancesheet'); ?>"
                                                    class="dropdown-item">Balance Sheet</a>
                                                <a href="<?php echo base_url('ïncomevsexpense'); ?>"
                                                    class="dropdown-item">Income Vs Expense</a>
                                            </div>
                                        </li>
                                        <!--<li class="nav-item" title="Chat">
											<a href="javascript:void(0);" class="nav-link mu-rt-bd <?php echo ($menu==10 ? 'mu-active' : ''); ?>"><i class="fa fa-comments" aria-hidden="true"></i></a>
                                        </li>-->
                                        <li class="nav-item" title="Emergency Alerts">
                                            <a href="javascript:void(0);"
                                                class="nav-link mu-rt-bd <?php echo ($menu==11 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="nav-item" title="App Settings">
                                            <a href="javascript:void(0);"
                                                class="nav-link mu-rt-bd <?php echo ($menu==12 ? 'mu-active' : ''); ?>"><i
                                                    class="fa fa-cogs" aria-hidden="true"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                <div class="header-right-info">
                                    <ul class="nav navbar-nav mai-top-nav header-right-menu">

                                        <li class="nav-item"><a href="#" data-toggle="dropdown" role="button"
                                                aria-expanded="false" class="nav-link dropdown-toggle"><i
                                                    class="fa fa-bell-o" aria-hidden="true"></i><span
                                                    class="indicator-nt"></span></a>
                                            <div role="menu" class="notification-author dropdown-menu  flipInX">
                                                <div class="notification-single-top">
                                                    <h1>Notifications</h1>
                                                </div>
                                                <ul class="notification-menu">
                                                    <li>
                                                        <a href="#">
                                                            <div class="notification-icon">
                                                                <span class="adminpro-icon adminpro-checked-pro"></span>
                                                            </div>
                                                            <div class="notification-content">
                                                                <span class="notification-date">16 Sept</span>
                                                                <h2>Advanda Cro</h2>
                                                                <p>Please done this project as soon possible.</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <div class="notification-icon">
                                                                <span
                                                                    class="adminpro-icon adminpro-cloud-computing-down"></span>
                                                            </div>
                                                            <div class="notification-content">
                                                                <span class="notification-date">16 Sept</span>
                                                                <h2>Sulaiman din</h2>
                                                                <p>Please done this project as soon possible.</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <div class="notification-icon">
                                                                <span class="adminpro-icon adminpro-shield"></span>
                                                            </div>
                                                            <div class="notification-content">
                                                                <span class="notification-date">16 Sept</span>
                                                                <h2>Victor Jara</h2>
                                                                <p>Please done this project as soon possible.</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            <div class="notification-icon">
                                                                <span
                                                                    class="adminpro-icon adminpro-analytics-arrow"></span>
                                                            </div>
                                                            <div class="notification-content">
                                                                <span class="notification-date">16 Sept</span>
                                                                <h2>Victor Jara</h2>
                                                                <p>Please done this project as soon possible.</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="notification-view">
                                                    <a href="#">View All Notification</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                                                class="nav-link dropdown-toggle">
                                                <span class="admin-name"><?php echo $orgname; ?>&nbsp;<i
                                                        class="fa fa-angle-down"></i></span>
                                            </a>
                                            <ul role="menu"
                                                class="dropdown-header-top author-log dropdown-menu  flipInX">
                                                <li><a href="#">My Account</a>
                                                </li>
                                                <li><a href="#">Subscription</a>
                                                </li>
                                                <li><a href="#">Settings</a>
                                                </li>
                                                <li><a href="<?php echo base_url('login/signout') ?>">Log Out</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header top area end-->