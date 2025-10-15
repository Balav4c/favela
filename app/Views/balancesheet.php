
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
                                            <li><a href="#">Home</a> <span class="bread-slash">/</span></li>
                                            <li><span class="bread-blod">Balance Sheet</span></li>
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
                                    <h1>Balance Sheet</h1>
									<div class="col-md-12 text-center">
										<?php 
										if(date('m')>03) {
				
											$currentFinYear = date('Y') + 1;
										}
										else {
											$currentFinYear = date('Y');
										}
										?>Financial Year: &nbsp;
										<select name="finyear" id="finyear" class="finyear" onchange="loadBalanceSheet()">
											<?php 
											$startingfrom = date('Y') - 14;
											for($i=$startingfrom;$i<=date('Y');$i++) {
												$fyear = " April ".$i." - March ".($i+1);
												$fyearVal = $i."-".($i+1);
												if($currentFinYear == ($i+1)) {
													$selected = 'selected="selected"';
												}
												else {
													$selected = '';
												}
												echo '<option value="'.$fyearVal.'" '.$selected.'>'.$fyear.'</option>';
											}
											?>
										</select>
										<div class="clearfix">&nbsp;</div>
									</div>
                                </div>
								
                                <div class="adminpro-message-list">
									<div class="balancesheet-block">
										<div class="row bs-head">
											<div class="col-md-4 bd-lt bd-rt bd-bt">Liabilities</div>
											<div class="col-md-2 text-end bd-rt bd-bt">Amount (<i class="fa fa-rupee"></i>)</div>
											<div class="col-md-4 bd-rt bd-bt">Assets</div>
											<div class="col-md-2 text-end bd-rt bd-bt">Amount (<i class="fa fa-rupee"></i>)</div>
										</div>
										<div class="row bs-list"></div>
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
    