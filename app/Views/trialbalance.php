
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
                                            <li><span class="bread-blod">Trial Balance</span>
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
                                    <h1>Trial Balance</h1>
                                </div>
                                <div class="adminpro-message-list">
									<div class="trialbalance-block">
										<div class="row jrn-head">
											<div class="col-md-7 bd-lt bd-rt bd-bt">Particulars</div>
											<div class="col-md-1 text-end bd-rt bd-bt">L.F.</div>
											<div class="col-md-2 text-end bd-rt bd-bt">Dr. Balance</div>
											<div class="col-md-2 text-end bd-rt bd-bt">Cr. Balance</div>
										</div>
										<div class="trialbalance-list">
										<?php
										if($trialbalancelist) {
											$Cr_Gtotal = 0;
											$Dr_Gtotal = 0;
											for($i=0;$i<count($trialbalancelist);$i++) {
												$drTotal = ($trialbalancelist[$i]['drtotal'] ? $trialbalancelist[$i]['drtotal'] : 0);
												$crTotal = ($trialbalancelist[$i]['crtotal'] ? $trialbalancelist[$i]['crtotal'] : 0);
												echo '<div class="row jrn-tnrs">
														<div class="col-md-7 bd-lt bd-rt">'.$trialbalancelist[$i]['account_type'].'</div>
														<div class="col-md-1 bd-rt">&nbsp;</div>
														<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;'.$drTotal.'</div>
														<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;'.$crTotal.'</div>
													</div>';
												$Cr_Gtotal = $Cr_Gtotal + $crTotal;
												$Dr_Gtotal = $Dr_Gtotal + $drTotal;
											}
											echo '<div class="row jrn-tnrs">
													<div class="col-md-7 bd-lt bd-rt"><strong>Total</strong></div>
													<div class="col-md-1 bd-rt">&nbsp;</div>
													<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;<strong>'.$Dr_Gtotal.'</strong></div>
													<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;<strong>'.$Cr_Gtotal.'</strong></div>
												</div>';
										}
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
    