
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
                                            <li><span class="bread-blod">User Roles</span>
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
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="welcome-wrapper shadow-reset res-mg-t mg-b-30">
                                <div class="welcome-adminpro-title">
                                    <h1>Create New User Roles</h1>
                                    <p>Enter the required details to create the roles.</p>
                                </div>
                                <div class="adminpro-message-list">
									<div class="alert"></div>
									<form name="createroles" id="createroles" method="post">
										<div class="form-group">
											<label for="buildname">Role Name</label>
											<input type="text" class="form-control" name="rolename" id="rolename" placeholder="Enter the role name" value="<?= $rolename; ?>"/>
										</div>
										<div class="form-group checkbox-group">
											<div class="row">
												<div class="col-md-12"><b>Accessable Menus</b><hr/></div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-12">
															<input type="checkbox" id="flats" value="2" name="menu_role[]" <?php echo (in_array("2",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="flats">Manage Flats/Towers</label>
														</div>
														<div class="col-md-12">
															<input type="checkbox" id="roles" value="3" name="menu_role[]" <?php echo (in_array("3",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="roles">Manage App User Roles</label>
														</div>
														<div class="col-md-12">
															<input type="checkbox" id="residents" value="4" name="menu_role[]" <?php echo (in_array("4",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="residents">Manage Residents</label>
														</div>
														<div class="col-md-12">
															<input type="checkbox" id="complaints" value="5" name="menu_role[]" <?php echo (in_array("5",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="complaints">Complaints & Informations</label>
														</div>
														<div class="col-md-12">
															<input type="checkbox" id="gatepass" value="6" name="menu_role[]" <?php echo (in_array("6",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="gatepass">Gate Pass</label>
														</div>
														
													</div>
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-12">
															<input type="checkbox" id="messages" value="7" name="menu_role[]" <?php echo (in_array("7",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="messages">Residents Messages</label>
														</div>
														<div class="col-md-12">
															<input type="checkbox" id="finance" value="8" name="menu_role[]" <?php echo (in_array("8",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="finance">Finance & Operations</label>
														</div>
														<div class="col-md-12">
															<input type="checkbox" id="accounts" value="9" name="menu_role[]" <?php echo (in_array("9",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="accounts">Book of Accounts</label>
														</div>
														<!--<div class="col-md-12">
															<input type="checkbox" id="accounts" value="10" name="menu_role[]" <?php echo (in_array("10",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="accounts">Chat</label>
														</div>-->
														<div class="col-md-12">
															<input type="checkbox" id="alerts" value="11" name="menu_role[]" <?php echo (in_array("11",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="alerts">Emergency Alerts</label>
														</div>
														<div class="col-md-12">
															<input type="checkbox" id="settings" value="12" name="menu_role[]" <?php echo (in_array("12",$role_previlage) ? 'checked="true"' : ''); ?>>
															<label for="settings">Settings</label>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div class="form-group text-right">
											<input type="hidden" name="role_id" value="<?php echo (isset($role_id) && $role_id!="" ? $role_id : 0); ?>" />
											<?php 
											if(isset($role_id) && $role_id!="") {
											?>
											<a href="<?= base_url('roles');?>"><button type="button" class="btn btn-secondary">Cancel</button></a>
											<?php 
											}
											?>
											<button type="button" name="roles-btn" id="roles-btn" class="btn btn-primary">
											<?php 
											if(isset($role_id) && $role_id!="") {
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
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="sparkline8-list shadow-reset">
                                <div class="sparkline8-hd">
                                    <div class="main-sparkline8-hd">
                                        <h1>Listed Roles</h1>
                                    </div>
                                </div>
                                <div class="sparkline8-graph">
                                    <div class="datatable-dashv1-list custom-datatable-overright">
										<table class="table table-borderless datatable" id="roleslist">
											<thead>
												<tr>
													<th>Slno</th>
													<th>Role Name</th>               
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
    