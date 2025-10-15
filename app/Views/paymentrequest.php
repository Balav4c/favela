
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
                                            <li><span class="bread-blod">Payment Request</span>
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
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="welcome-wrapper shadow-reset res-mg-t mg-b-30">
								<div class="welcome-adminpro-title">
                                    <h1>Create New Payment Request</h1>
                                    <p>Enter the required details to create the request.</p>
                                </div>
								<div class="adminpro-message-list">
									<div class="alert"></div>
									<form name="createpayrequest" id="createpayrequest" method="post">
										<div class="form-group">
											<label for="payment_type">Payment Type</label>
											<select class="form-control" name="payment_type" id="payment_type">
												<option value="">Select payment types</option>
												<option value="1" <?php if($payment_type == 1) { echo 'selected="selected"'; } ?>>Monthly</option>
												<option value="2" <?php if($payment_type == 2) { echo 'selected="selected"'; } ?>>Yearly</option>
												<option value="3" <?php if($payment_type == 3) { echo 'selected="selected"'; } ?>>Quarterly</option>
												<option value="4" <?php if($payment_type == 4) { echo 'selected="selected"'; } ?>>Weekly</option>
												<option value="5" <?php if($payment_type == 5) { echo 'selected="selected"'; } ?>>One Time</option>
											</select>
										</div>
										<div class="form-group">
											<label for="paynotes">Payment Notes</label>
											<input type="text" class="form-control" name="paynotes" id="paynotes" placeholder="Enter the payment notes" value="<?= $paynotes; ?>"/>
										</div>
										<div class="form-group">
											<label for="paynotes">Amount</label>
											<input type="text" class="form-control" name="amount" id="amount" placeholder="Enter the amount" value="<?= $amount; ?>"/>
										</div>
										<div class="form-group">
											<label for="request_to">Send Request To</label>
											<select class="form-control" name="request_to" id="request_to" onchange="enableResident(this.value);">
												<option value="1" <?php if($request_to == 1) { echo 'selected="selected"'; } ?>>All Residents</option>
												<option value="2" <?php if($request_to == 2) { echo 'selected="selected"'; } ?>>Individuals</option>
											</select>
										</div>
										<div class="form-group">
											<label for="residence">Resident Name</label>
											<input class="form-control" type="text" name="residence" id="residence" value="<?= $uname; ?>" <?php echo ($uname!="" ? '' : 'disabled'); ?>/>
											<input type="hidden" name="residence_hd" id="residence_hd"  value="<?= $uid; ?>"/>
											<div class="autocomplete-box" id="res-auto"></div>
										</div>
										<div class="form-group text-right">
											<input type="hidden" name="payid" id="payid" value="<?= $pr_id; ?>"/>
											<a href="<?= base_url('paymentrequest');?>"><button type="button" class="btn btn-secondary">Cancel</button></a>
											<button type="button" name="payrequest-btn" id="payrequest-btn" class="btn btn-primary">
											<?php 
											if(isset($pr_id) && $pr_id!="") {
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
										<h1>Listed Payment Request</h1>
									</div>
								</div>
								<div class="sparkline8-graph">
									<div class="datatable-dashv1-list custom-datatable-overright">
										<table class="table table-borderless datatable" id="payrqlist">
											<thead>
												<tr>
													<th>Slno</th>
													<th>Payment Type</th>               
													<th>Notes</th>               
													<th>Amount</th>               
													<th>Request To</th>               
													<th>Resident</th>               
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
                </div>
            </div>
            <!-- welcome Project, sale area start-->
        </div>
    </div>
    