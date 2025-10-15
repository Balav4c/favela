
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
                                            <li><span class="bread-blod">Journal</span>
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
                            <div class="welcome-wrapper shadow-reset res-mg-t mg-b-30 journal-wrapper">
								<div class="jr-filter-inputs text-end">
									<input type="date" name="from_date" id="from_date" />
									<input type="date" name="to_date" id="to_date" />
									<button class="btn btn-secondary" id="jr-filter-btn"><i class="fa fa-filter"></i></button>
									<button class="btn btn-secondary" id="jr-reset-btn"><i class="fa fa-refresh"></i></button>
									<hr/>
								</div>
                                <div class="welcome-adminpro-title text-center">
                                    <h1>Journal</h1>
                                    <div><?php echo $orgname; ?></div>
									<div class="dateblock"></div>
								</div>
                                <div class="adminpro-message-list">
									<div class="journal-block">
										<div class="row jrn-head">
											<div class="col-md-2 bd-lt bd-rt bd-bt">Date</div>
											<div class="col-md-6 bd-rt bd-bt">Account</div>
											<div class="col-md-2 text-end bd-rt bd-bt">Debit</div>
											<div class="col-md-2 text-end bd-rt bd-bt">Credit</div>
										</div>
										<div class="jrn-trnslist"></div>
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
    