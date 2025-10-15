
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
                                            <li><span class="bread-blod">Payments</span>
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
							<form name="receipt" id="receipt" autocomplete="off">
								<div class="welcome-wrapper shadow-reset res-mg-t mg-b-30">
									<div class="welcome-adminpro-title">
										<div class="row">
											<div class="col-md-6">
												<h1><span class="voucher-title">Payment</span> No: <span class="receipt_no"><?php echo $recp_prefix ."-". $receiptno; ?></span></h1>
											</div>
											<div class="col-md-6 text-end">
												<div><input type="date" class="recp_date" name="recp_date" value="" /></div>
												<div class="today_day"><?php echo date("l"); ?></div>
											</div>
											
										</div>
									</div>
									<div class="receipt-box">
										<div class="receipt-head">
											<div class="row">
											<div class="col-md-8">Particular</div>
											<div class="col-md-2 text-end">Debit</div>
											<div class="col-md-2 text-end">Credit</div>
											</div>
										</div>
										<div class="trns-details">
											<div class="row">
												<div class="col-md-8">
													<span class="trntype-text">Cr</span>
													<input type="text" class="ledger-input" id="cr_ledger" name="ledger" /> 
													<input type="hidden" id="cr_ledger_id" name="cr_ledger_id" class="hidden_ledger"/>
												</div>
												<div class="col-md-2">&nbsp;</div>
												<div class="col-md-2">
													<input type="text" class="amount-input cr_amnt" name="cr_amnt" /> 
												</div>
											</div>
										</div>
										<div class="trns-total">
											<div class="clearfix">&nbsp;</div>
											<div class="row">
												<div class="col-md-8">
													<input type="text" name="narrate" id="narrate" class="narrate-box" placeholder="Narration"/>
												</div>
												<div class="col-md-2 dr_total text-end">0</div>
												<div class="col-md-2 cr_total text-end">0</div>
												<input type="hidden" name="trntype_hd" id="trntype_hd" />
												<input type="hidden" name="trnref_hd" id="trnref_hd" />
												<input type="hidden" name="receipt_no" id="receipt_no" value="<?= $receiptno; ?>"/>
												<input type="hidden" name="receipt_prefix" id="receipt_prefix" value="<?= $recp_prefix; ?>"/>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
                </div>
            </div>
            <!-- welcome Project, sale area start-->
        </div>
    </div>
    <div class="ledger-list-box">
		<div class="ledger-list-head">
			List of Ledger Accounts
		</div>
		<div class="ledger-list-block"></div>
	</div>
	<div class="acceptReceipt-block">
		<div class="col-md-12">
			Accept Payment?
		</div>
		<div class="col-md-12 text-center">
			Yes(Y) Or No(N)
		</div>
		<input type="text" id="accpt_ans" />
	</div>
	<div class="transaction-mode-block shadow-reset">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<p>Choose the mode of transaction</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<select name="trntype" id="trntype" class="form-control">
						<option value="">Select</option>
						<option value="1">RTGS/NEFT</option>
						<option value="2">Cheque</option>
						<option value="3">UPI</option>
						<option value="4">Cash</option>
					</select>
					<div class="clearfix">&nbsp;</div>
				</div>
				<div class="col-md-12">
					<p>Transaction Reference</p>
					<input type="text" name="trnref" id="trnref" class="form-control"/>
				</div>
			</div>
		</div>
	</div>