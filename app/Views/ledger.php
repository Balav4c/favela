
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
                                            <li><span class="bread-blod">Ledgers</span>
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
                    <div class="row" id="manage-ledgers">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="welcome-wrapper shadow-reset res-mg-t mg-b-30">
                                <div class="welcome-adminpro-title">
                                    <h1>Create New Ledger</h1>
                                    <p>Enter the required details to create the ledger.</p>
                                </div>
                                <div class="adminpro-message-list">
									<div class="alert"></div>
									<form name="ledgerform" id="ledgerform" method="post">
										<div class="form-group">
											<label for="buildname">Ledger Name</label>
											<input type="text" class="form-control" name="ledgername" id="ledgername" placeholder="Enter the ledger name" value="<?= $ledger_name; ?>"/>
										</div>
										<div class="form-group">
											<label for="buildname">Account Type</label>
											<select id="acctype" name="acctype" class="form-control" onchange="getsubhead(this.value);">
												<option value="">Select Type</option>
												<?php 
												if($accountType) {
													foreach($accountType as $acc) {
														$selected = ($account_type == $acc->acc_id ? 'selected="selected"' : '');
														echo '<option value="'.$acc->acc_id.'" '.$selected.'>'.$acc->master_name.'</option>';
													}
												}
												?>
											</select>
										</div>
										<div class="form-group">
											<label for="subhead">Subhead Account</label>
											<select id="subheadid" class="form-control" name="subhead">
												<option value="">Select Subhead</option>
											</select>
										</div>
										<div class="form-group">
											<label for="buildname">Opening Balance</label>
											<input type="text" class="form-control" name="openbal" id="openbal" placeholder="Enter the opening balance" value="<?= $opening_balance; ?>"/>
										</div>
										<div class="form-group text-right">
											<input type="hidden" id="ledger_id" name="ledger_id" value="<?php echo (isset($ledger_id) && $ledger_id!="" ? $ledger_id : 0); ?>" />
											<?php 
											if(isset($ledger_id) && $ledger_id!="") {
											?>
											<a href="<?= base_url('ledger');?>"><button type="button" class="btn btn-secondary">Cancel</button></a>
											<?php 
											}
											?>
											<button type="button" name="ledger-btn" id="ledger-btn" class="btn btn-primary">
											<?php 
											if(isset($ledger_id) && $ledger_id!="") {
												echo "Update";
											}
											else {
												echo "Create";
											}
											?>
											</button>
										</div>
									</form>
                                </div>
                            </div>
                        </div>
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
							<div class="sparkline8-list shadow-reset">
                                <div class="sparkline8-hd">
                                    <div class="main-sparkline8-hd">
                                        <h1>Listed Ledgers</h1>
                                    </div>
                                </div>
                                <div class="sparkline8-graph">
                                    <div class="datatable-dashv1-list custom-datatable-overright">
										<table class="table table-borderless datatable" id="roleslist">
											<thead>
												<tr>
													<th>Slno</th>
													<th>Ledger</th>               
													<th>Account Type</th>               
													<th>Subhead</th>               
													<th>Opening Bal.</th>               
													<th>Created On</th>               
													<th>Status</th>               
													<th>Action</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
                                    </div>
                                </div>
                            </div>
						</div>
                        <!---->
					</div>
					<div class="row" id="open-ledger">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="welcome-wrapper shadow-reset res-mg-t mg-b-30 ledger-wrapper">
								<div class="ledger-block">
									<input type="hidden" id="ledger_id" />
									<input type="hidden" id="ledger_name" />
									<div class="col-md-12 text-center">
										<h4 class="ledname"></h4>
										<p class="finyear"></p>
										<?php 
										if(date('m')>03) {
				
											$currentFinYear = date('Y') + 1;
										}
										else {
											$currentFinYear = date('Y');
										}
										?>Financial Year: &nbsp;
										<select name="finyear" id="finyear" class="finyear" onchange="resetLedger()">
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
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-12 gutter-0"><b>Dr.</b></div>
										</div>
										<div class="row ledg-head">
											<div class="col-md-2 bd-lt bd-rt">Date</div>
											<div class="col-md-6 bd-rt">Particulars</div>
											<div class="col-md-1 bd-rt">J.F</div>
											<div class="col-md-3 bd-rt text-end">Amount</div>
										</div>
										<div class="dr-trns-holder"></div>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-12 gutter-0 text-end"><b>Cr.</b></div>
										</div>
										<div class="row ledg-head">
											<div class="col-md-2 bd-rt">Date</div>
											<div class="col-md-6 bd-rt">Particulars</div>
											<div class="col-md-1 bd-rt">J.F</div>
											<div class="col-md-3 bd-rt text-end">Amount</div>
										</div>
										<div class="cr-trns-holder"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
            <!-- welcome Project, sale area start-->
        </div>
    </div>