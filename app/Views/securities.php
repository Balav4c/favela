
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
                                            <li><span class="bread-blod">Securities</span>
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
                            <div class="welcome-wrapper shadow-reset res-mg-t mg-b-30 journal-wrapper">
								<div class="welcome-adminpro-title">
                                    <h1>Create New Security</h1>
                                    <p>Enter the required details to create the security.</p>
                                </div>
                                <div class="adminpro-message-list" id="gp-form-block">
									<form name="securityform" id="securityform">
										
										<div class="form-group">
											<label for="residence">Security ID Card.</label>
											<select class="form-control" name="id_card" id="id_card">
												<option value="1">Aadhaar Card</option>
												<!--<option value="2">Pan Card</option>
												<option value="3">Passport</option>
												<option value="4">Official ID</option>-->
											</select>
										</div>
										<div class="form-group">
											<label for="residence">ID Card No.</label>
											<input class="form-control" type="text" name="id_card_no" id="id_card_no" value="<?php echo $id_card_no; ?>"/>
										</div>
										<div class="security-search-block text-end">
											<button type="button" name="security-search-btn" id="security-search-btn" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Search</button>
										</div>
										<div class="security-block">
											<div class="form-group">
												<label for="residence">Security Name</label>
												<input class="form-control" type="text" name="security_name" id="security_name" value="<?php echo $security_name; ?>"/>
											</div>
											<div class="form-group">
												<label for="residence">Security Phone No.</label>
												<input class="form-control" type="text" name="security_phone" id="security_phone" value="<?php echo $security_phone; ?>"/>
											</div>
											<div class="form-group">
												<label for="residence">Security Company Name.</label>
												<input class="form-control" type="text" name="security_cmp_name" id="security_cmp_name" value="<?php echo $security_cmp_name; ?>"/>
											</div>
											<div class="form-group">
												<label for="residence">Security Company Address.</label>
												<textarea class="form-control" name="security_address" id="security_address" rows="5"><?php echo $security_address; ?></textarea>
											</div>
											<div class="form-group text-right">
												<input type="hidden" name="security_id" id="security_id" value="<?php echo $security_id; ?>"/>
												<input type="hidden" name="editmode" id="editmode" value="<?php echo $editmode; ?>"/>
												<input type="hidden" name="fv_id" id="fv_id" value="<?= session('fv_id') ?>">

												<?php 
												if(isset($editmode) && $editmode==1) {
												?>
													<a href="<?= base_url('securities')?>"><button type="button" class="btn btn-secondary">Cancel</button></a>
												<?php 
												}
												?>
												<button type="button" name="security-btn" id="security-btn" class="btn btn-primary">
													<?php 
													if(isset($editmode) && $editmode==1) {
														echo "Update Security Info";
													}
													else {
														echo "Add To Association";
													}
													?>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
                        </div>
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="securitylist-block">
                            <div class="sparkline8-list shadow-reset" id="gatepasslist-block">
                                <div class="sparkline8-hd">
                                    <div class="main-sparkline8-hd">
                                        <h1>Listed Securities</h1>
                                    </div>
                                </div>
                                <div class="sparkline8-graph">
                                    <div class="datatable-dashv1-list custom-datatable-overright">
										<table class="table table-borderless datatable" id="securitylist">
											<thead>
												<tr>
													<th>Slno</th>
													<th>Security Name</th>               
													<th>Security Phone</th>
													<th>ID Proof</th>
													<th>ID Proof No.</th>
													<th>Company Info</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id="securityfeedback-block">
							<div class="welcome-wrapper shadow-reset res-mg-t mg-b-30">
								<div class="welcome-adminpro-title">
									<small class="pull-right">Esc (Close)</small>
                                    <h1>Feedback About Mr.<span>Midhun</span></h1>
                                    <p>Enter your feedback for the security.</p>
                                </div>
                                <div class="adminpro-message-list" id="">
									<div class="row">
										<div class="col-md-5">
											<div class="star-rates">
												<i class="fa fa-star" id="1" data-val="1"></i>
												<i class="fa fa-star" id="2" data-val="2"></i>
												<i class="fa fa-star" id="3" data-val="3"></i>
												<i class="fa fa-star" id="4" data-val="4"></i>
												<i class="fa fa-star" id="5" data-val="5"></i>
												<input type="hidden" id="star_ratings" />
											</div>
											<label for="feedback">Share your feedback</label>
											<textarea class="form-control" id="feedback" rows="5"></textarea>
											<div class="col-md-12 no-gutter text-end">
												<div class="clearfix">&nbsp;</div>
												<button class="btn btn-primary">Share now</button>
											</div>
										</div>
										<div class="col-md-7 feedback-list"></div>
									</div>
								</div>
							</div>
						<div>
						<!---->
					</div>
                </div>
            </div>
            <!-- welcome Project, sale area start-->
        </div>
    </div>
    