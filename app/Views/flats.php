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
                                            <li><span class="bread-blod">Flats</span>
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
                                    <h1>Create New Building/Tower</h1>
                                    <p>Enter the required details to create the flat.</p>
                                </div>
                                <div class="adminpro-message-list">
									<form name="createflat" id="createflat" method="post">
										<div class="form-group">
											<label for="buildname">Building/Tower *</label>
											<input type="text" class="form-control" name="buildname" id="buildname" placeholder="Enter building or tower name" value="<?= $building ?>"/>
										</div>
										<div class="form-group">
											<label for="flatnos">Flats</label>
											<input type="text" class="form-control" name="flatnos" id="flatnos" placeholder="Enter number of flats in the tower." value="<?= $flats ?>"/>
										</div>
										<div class="form-group">
											<label for="recptno">Reception Contact</label>
											<input type="text" class="form-control" name="recptno" id="recptno" placeholder="Enter reception contact no." value="<?= $reception ?>"/>
										</div>
										<div class="form-group text-right">
											<input type="hidden" name="bd_id" value="<?php echo (isset($bd) && $bd!="" ? $bd : 0); ?>" />
											<?php 
											if(isset($bd) && $bd!="") {
											?>
											<a href="<?= base_url('flats')?>"><button type="button" class="btn btn-secondary">Cancel</button></a>
											<?php 
											}
											?>
											<button type="button" name="flt-btn" id="flt-btn" class="btn btn-primary">
											<?php 
											if(isset($bd) && $bd!="") {
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
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="sparkline8-list shadow-reset">
                                <div class="sparkline8-hd">
                                    <div class="main-sparkline8-hd">
                                        <h1>Listed Flats</h1>
                                    </div>
                                </div>
                                <div class="sparkline8-graph">
                                    <div class="datatable-dashv1-list custom-datatable-overright">
										<table class="table table-borderless datatable" id="flatslist">
											<thead>
												<tr>
													<th>Slno</th>
													<th>Building/Tower</th>
													<th>No. of Flats</th>
													<th>Reception No.</th>               
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
    