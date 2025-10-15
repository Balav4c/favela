
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
                                            <li><span class="bread-blod">Accounts Type</span>
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
                                    <h1>Create New Account Type</h1>
                                    <p>Enter the required details to create the account types.</p>
                                </div>
                                <div class="adminpro-message-list">
									<div class="alert"></div>
									<form name="createacctype" id="createacctype" method="post">
										<div class="form-group">
											<label for="buildname">Accounts Type Name</label>
											<input type="text" class="form-control" name="acctype" id="acctype" placeholder="Enter the account type name" value="<?= $acctype_name; ?>"/>
										</div>
										<div class="form-group">
											<label for="buildname">Financial Statement</label>
											<select class="form-control" name="fin_statement" id="fin_statement" onchange="loadcategory(this.value);">
												<option value="0" class="fs fs-0">Select Statement</option>
												<option value="1" <?php echo ($fin_statement == 1 ? 'selected="selected"' : ''); ?>>Balance Sheet</option>
												<option value="2" <?php echo ($fin_statement == 2 ? 'selected="selected"' : ''); ?>>Profit & Loss Statement</option>
												<!--<option value="0" <?php echo ($fin_statement == 0 ? 'selected="selected"' : ''); ?>>Others / NA</option>-->
											</select>
										</div>
										<div class="form-group">
											<label for="buildname">Statement Category</label>
											<select class="form-control" name="statement_category" id="statement_category">
												<option value="0" class="fs fs-0">Select Category</option>
												<option value="1" <?php echo ($statement_category == 1 ? 'selected="selected"' : ''); ?> class="fs fs-2">Profit</option>
												<option value="2" <?php echo ($statement_category == 2 ? 'selected="selected"' : ''); ?> class="fs fs-2">Loss</option>
												<option value="3" <?php echo ($statement_category == 3 ? 'selected="selected"' : ''); ?> class="fs fs-1">Assets</option>
												<option value="4" <?php echo ($statement_category == 4 ? 'selected="selected"' : ''); ?> class="fs fs-1">Liabilities</option>
											</select>
										</div>
										<div class="form-group text-right">
											<input type="hidden" name="acctype_id" value="<?php echo (isset($acctype_id) && $acctype_id!="" ? $acctype_id : 0); ?>" />
											<?php 
											if(isset($acctype_id) && $acctype_id!="") {
											?>
											<a href="<?= base_url('accounts');?>"><button type="button" class="btn btn-secondary">Cancel</button></a>
											<?php 
											}
											?>
											<button type="button" name="acctype-btn" id="acctype-btn" class="btn btn-primary">
											<?php 
											if(isset($acctype_id) && $acctype_id!="") {
												echo "Update";
											}
											else {
												echo "Create";
											}
											?>
											</button>
										</div>
										<div class="row">
											
										</div>
									</form>
                                </div>
                            </div>
                        </div>
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
							<div class="sparkline8-list shadow-reset">
                                <div class="sparkline8-hd">
                                    <div class="main-sparkline8-hd">
                                        <h1>Listed Account Types</h1>
                                    </div>
                                </div>
                                <div class="sparkline8-graph">
                                    <div class="datatable-dashv1-list custom-datatable-overright">
										<table class="table table-borderless datatable" id="acctypelist">
											<thead>
												<tr>
													<th>Slno</th>
													<th>Account Types</th>                 
													<th>Financial Statement</th>                 
													<th>Category</th>                 
													<th>Type</th>                 
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
    