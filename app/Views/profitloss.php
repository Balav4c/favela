
            <!-- Breadcome start-->
            <div class="breadcome-area mg-b-30 small-dn">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcome-list map-mg-t-40-gl shadow-reset">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
										<div class="breadcome-heading">
											<h2>
											<img src="<?php echo base_url('public/img/user.png'); ?>"/>
											&nbsp;Welcome <?php echo ucwords($username); ?></h2>
										</div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <ul class="breadcome-menu">
                                            <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">Profit & Loss</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Breadcome End-->
           <!-- welcome Project, sale area start-->
            <div class="welcome-adminpro-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="welcome-wrapper shadow-reset res-mg-t mg-b-30 journal-wrapper">
								<div class="welcome-adminpro-title text-center">
                                    <h1>Profit & Loss Statement</h1>
                                </div>
                                <div class="adminpro-message-list">
									<div class="profit-loss-block">
										<div class="row pl-head">
											<div class="col-md-8 bd-lt bd-rt bd-bt">Income</div>
											<div class="col-md-2 text-end bd-rt bd-bt">Amount (<i class="fa fa-rupee"></i>)</div>
											<div class="col-md-2 text-end bd-rt bd-bt">Total (<i class="fa fa-rupee"></i>)</div>
										</div>
										<div class="pl-list">
											<?php
											$totalIncome = 0;
											$totalExpense = 0;
											if($income) {
												
												for($i=0;$i<count($income);$i++) {
													echo '<div class="row pl-tnrs">
															<div class="col-md-8 bd-lt bd-rt">'.$income[$i]['acctype'].'</div>
															<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;'.$income[$i]['subheadtotal'].'</div>
															<div class="col-md-2 text-end bd-rt">&nbsp;</div>
														</div>';
													$totalIncome = $totalIncome + $income[$i]['subheadtotal'];
												}
												echo '<div class="row pl-tnrs">
															<div class="col-md-8 bd-lt bd-rt"><b>Total Income</b></div>
															<div class="col-md-2 text-end bd-rt">&nbsp;</div>
															<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;'.$totalIncome.'</div>
														</div>';
											}
											?>
											<div class="row pl-head-sec">
												<div class="col-md-8 bd-lt bd-rt bd-bt">Expenses</div>
												<div class="col-md-2 text-end bd-rt bd-bt">Amount (<i class="fa fa-rupee"></i>)</div>
												<div class="col-md-2 text-end bd-rt bd-bt">Total (<i class="fa fa-rupee"></i>)</div>
											</div>
											<?php 
											if($expense) {
												
												for($i=0;$i<count($expense);$i++) {
													echo '<div class="row pl-tnrs">
															<div class="col-md-8 bd-lt bd-rt">'.$expense[$i]['acctype'].'</div>
															<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;'.$expense[$i]['subheadtotal'].'</div>
															<div class="col-md-2 text-end bd-rt">&nbsp;</div>
														</div>';
													$totalExpense = $totalExpense + $expense[$i]['subheadtotal'];
												}
												echo '<div class="row pl-tnrs">
															<div class="col-md-8 bd-lt bd-rt"><b>Total Expense</b></div>
															<div class="col-md-2 text-end bd-rt">&nbsp;</div>
															<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;'.$totalExpense.'</div>
														</div>';
											}
											$profit_loss = $totalIncome - $totalExpense;
											echo '<div class="row pl-tnrs">
													<div class="col-md-12 bd-lt bd-rt">&nbsp;</div>
												</div>
												<div class="row pl-tnrs">
													<div class="col-md-8 bd-lt bd-rt"><b>Profit/Loss</b></div>
													<div class="col-md-2 text-end bd-rt">&nbsp;</div>
													<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;'.$profit_loss.'</div>
												</div>'
											?>
										</div>
									</div>
								</div>
                            </div>
                        </div>
						<!---->
					</div>
                </div>
            </div>
            <!-- welcome Project, sale area start-->
        </div>
    </div>
    