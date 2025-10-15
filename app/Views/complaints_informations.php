
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
                                            <li><span class="bread-blod">Complaints & Informations</span>
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
                                    <h1>Create New Complaint or Information</h1>
                                    <p>Enter the required details to create new.</p>
                                </div>
								<div class="adminpro-message-list">
									<form name="complaints" id="complaints">
										<div class="form-group">
											<label for="residence">Resident Name</label>
											<input class="form-control" type="text" name="residence" id="residence" value="<?= $residentname; ?>"/>
											<input type="hidden" name="residence_hd" id="residence_hd"  value="<?= $res_id; ?>"/>
											<div class="autocomplete-box" id="res-auto"></div>
										</div>
										<div class="form-group">
											<label for="subject">Type *</label>
											<select class="form-control" name="msgtype" id="msgtype">
												<option value="1" <?php echo ($type == 1 ? 'selected="selected"' : ''); ?>>Complaint</option>
												<option value="2" <?php echo ($type == 2 ? 'selected="selected"' : ''); ?>>Information</option>
											</select>
										</div>
										<div class="form-group">
											<label for="subject">Subject *</label>
											<input type="text" class="form-control" name="subject" id="subject" placeholder="Enter subject of complaint." value="<?= $subject; ?>"/>
										</div>
										<div class="form-group">
											<label for="subject">Complaint or Information *</label>
											<textarea class="form-control" name="content" id="content" placeholder="Enter complaint or information message." rows="3"><?= $content; ?></textarea>
										</div>
										<div class="form-group text-right">
											<input type="hidden" name="cmp_id" id="cmp_id" value="<?php echo (isset($cid) && $cid!="" ? $cid : 0); ?>" />
											<?php 
											if(isset($cid) && $cid!="") {
											?>
											<a href="<?= base_url('complaintsinfo');?>"><button type="button" class="btn btn-secondary">Cancel</button></a>
											<?php 
											}
											?>
											<button type="button" name="cmp-btn" id="cmp-btn" class="btn btn-primary">
											<?php 
											if(isset($cid) && $cid!="") {
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
                                        <h1>Listed Complaints & Informations</h1>
                                    </div>
                                </div>
                                <div class="sparkline8-graph">
                                    <div class="datatable-dashv1-list custom-datatable-overright">
										<table class="table table-borderless datatable" id="complaintsinfolist">
											<thead>
												<tr>
													<th>Slno</th>
													<th>Subject</th>
													<th>Complaint</th>               
													<th>Posted On</th>               
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
    