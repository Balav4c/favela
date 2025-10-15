
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
                                            <li><span class="bread-blod">Gate Pass</span>
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
                                    <h1>Create New Gatepass</h1>
                                    <p>Enter the required details to create the gatepass.</p>
                                </div>
                                <div class="adminpro-message-list" id="gp-form-block">
									<form name="gatepassform" id="gatepassform">
										<div class="form-group">
											<label for="residence">Visitor Name</label>
											<input class="form-control" type="text" name="visitor" id="visitor" value=""/>
										</div>
										<div class="form-group">
											<label for="residence">Place / Location</label>
											<input class="form-control" type="text" name="location" id="location" value=""/>
										</div>
										<div class="form-group">
											<label for="residence">Whatsapp No.</label>
											<input class="form-control" type="text" name="phone" id="phone" value=""/>
										</div>
										<div class="form-group">
											<label for="residence">Date of Visit</label>
											<input class="form-control" type="date" name="dateofvisit" id="dateofvisit" value=""/>
										</div>
										<div class="form-group">
											<label for="residence">Purpose Of Visit</label>
											<input class="form-control" type="text" name="purpose" id="purpose" value=""/>
										</div>
										<div class="form-group">
											<label for="residence">Person or flat to visit</label>
											<input class="form-control" type="text" name="person_flat" id="person_flat" value=""/>
										</div>
										<div class="form-group text-right">
											<button type="button" name="gp-btn" id="gp-btn" class="btn btn-primary">Create</button>
										</div>
									</form>
								</div>
								<div id="gp-view-block">
									<div class="col-md-12 no-gutter">
										<div class="gp-card" id="gp-card">
											<div>
												<img src="<?php echo base_url('public/img/gatepass-logo.png'); ?>" class="gatepass-logo"/>
												<div class="clearfix">&nbsp;</div>
											</div>
											<div>
												<h3><strong>Gate Pass</strong></h3>
												<p><?php echo $orgname; ?></p>
											</div>
											<img src="" class="qrimg"/>
											<div><small>Scan the QR to get access.</small></div>
											<div class="clearfix">&nbsp;</div>
											<div class="gp-vname"></div>
											<div class="gp-vdate"></div>
											<div class="clearfix">&nbsp;</div>
										</div>
										<div class="form-group text-center">
											<div class="clearfix">&nbsp;</div>
											<a href="" target="_blank" id="sharewhatsapp"><button class="btn btn-success"><i class="fa fa-whatsapp"></i>&nbsp;Share on whatsapp</button></a>
											<button class="btn btn-primary" id="done-gp">Done&nbsp;<i class="fa fa-check-circle"></i></button>
										</div>
									</div>
								</div>
                            </div>
                        </div>
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <div class="sparkline8-list shadow-reset" id="gatepasslist-block">
                                <div class="sparkline8-hd">
                                    <div class="main-sparkline8-hd">
                                        <h1>Listed Gatepass</h1>
                                    </div>
                                </div>
                                <div class="sparkline8-graph">
                                    <div class="datatable-dashv1-list custom-datatable-overright">
										<table class="table table-borderless datatable" id="gatepasslist">
											<thead>
												<tr>
													<th>Slno</th>
													<th>Visitor Info</th>               
													<th>Date of Visit</th>
													<th>Check In</th>
													<th>Check Out</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
                                    </div>
                                </div>
                            </div>
							<div class="welcome-wrapper shadow-reset res-mg-t mg-b-30" id="gatepassDetails-block">
								<div class="welcome-adminpro-title">
									<small class="pull-right">Esc (Close)</small>
                                    <h1>Gatepass Details</h1>
                                    <p>Information shared with the visitor.</p>
                                </div>
								<div class="col-md-12 no-gutter">
									<div class="row">
										<div class="col-md-4 text-center">
											<img src="" class="gatepassimg"/>
											<div class="clearfix">&nbsp;</div>
											<a href="" target="_blank" id="sharewhatsapp-view"><button class="btn btn-success"><i class="fa fa-whatsapp"></i>&nbsp;Share on whatsapp</button></a>
										</div>
										<div class="col-md-8">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="residence">Visitor Name</label>
														<div class="form-control" id="v-visitor"></div>
													</div>
													<div class="form-group">
														<label for="residence">Visitor Place</label>
														<div class="form-control" id="v-place"></div>
													</div>
													<div class="form-group">
														<label for="residence">Visitor Phone</label>
														<div class="form-control" id="v-phone"></div>
													</div>
													<div class="form-group">
														<label for="residence">Date Of Visit</label>
														<div class="form-control" id="v-date"></div>
													</div>
													<div class="form-group">
														<label for="residence">Purpose Of Visit</label>
														<div class="form-control" id="v-purpose"></div>
													</div>
													<div class="form-group">
														<label for="residence">Person/Flat To Visit</label>
														<div class="form-control" id="v-person"></div>
													</div>	
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="residence">Check In Time</label>
														<div class="form-control" id="v-checkin"></div>
													</div>
													<div class="form-group">
														<label for="residence">Check Out Time</label>
														<div class="form-control" id="v-checkout"></div>
													</div>
													<div class="form-group">
														<label for="residence">Security Gaurd</label>
														<div class="form-control" id="v-security"></div>
													</div>
													<div class="form-group">
														<label for="residence">Status</label>
														<div id="v-status"></div>
													</div>
													<div class="form-group">
														<label for="residence">Created By</label>
														<div class="form-control" id="v-created"></div>
													</div>
													<div class="form-group">
														<label for="residence">Created On</label>
														<div class="form-control" id="v-createdon"></div>
													</div>
												</div>
											</div>
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
    