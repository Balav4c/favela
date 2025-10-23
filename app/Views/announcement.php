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
                                    <img src="<?php echo base_url('public/img/user.png'); ?>" />
                                    &nbsp;Welcome <?php echo ucwords($username); ?>
                                </h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <ul class="breadcome-menu">
                                <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                </li>
                                <li><span class="bread-blod">Announcements</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- welcome Project, sale area start-->
<div class="welcome-adminpro-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                <div class="welcome-wrapper shadow-reset res-mg-t mg-b-30">
                    <div class="welcome-adminpro-title">
                        <h1>Create New Announcement</h1>
                        <p>Enter the required details to create the Announcement.</p>
                    </div>
                    <div class="adminpro-message-list">
                        <form name="createAnnouncement" id="createAnnouncement" method="post">
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" class="form-control" name="subject" id="subject"
                                    placeholder="Enter announcement subject" value="<?= $subject ?>" maxlength="30" />
                            </div>
                            <div class="form-group">
                                <label for="announcements">Announcement *</label>
                                <textarea class="form-control" name="announcements" id="announcements" rows="4"
                                    placeholder="Enter the announcement."><?= isset($announcements) ? $announcements : '' ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label for="announce_date">Publish Date *</label>
                                        <input type="text" class="form-control pl-4" id="announce_date"
                                            name="announce_date" value="<?= esc($announce_date) ?>"
                                            placeholder="Select publish date" />
                                        <i class="fa fa-calendar" style="right: 23px;top: 57%;transform: translateY(-50%); pointer-events: none;position: absolute;"></i>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label for="expiry_date">End Date *</label>
                                        <input type="text" class="form-control pl-4" id="expiry_date" name="expiry_date"
                                            value="<?= esc($expiry_date) ?>" placeholder="Select expiry date" />
                                        <i class="fa fa-calendar"
                                            style="right: 23px;top: 57%;transform: translateY(-50%); pointer-events: none;position: absolute;"></i>
                                    </div>
                                </div>
                            </div>




                            <div class="form-group text-right">
                                <input type="hidden" name="anoc_id"
                                    value="<?php echo (isset($anoc_id) && $anoc_id!="" ? $anoc_id : 0); ?>" />
                                <?php 
											if(isset($anoc) && $anoc!="") {
											?>
                                <a href="<?= base_url('announcements')?>"><button type="button"
                                        class="btn btn-secondary">Cancel</button></a>
                                <?php 
											}
											?>
                                <button type="button" name="anoc-btn" id="anoc-btn" class="btn btn-primary">
                                    <?php 
											if(isset($anoc) && $anoc!="") {
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
            <div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
                <div class="sparkline8-list shadow-reset">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1>Listed Announcements</h1>
                        </div>
                    </div>
                    <div class="sparkline8-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <table class="table table-borderless datatable" id="announcementlist">
                                <thead>
                                    <tr>
                                        <th>Slno</th>
                                        <th>Subject</th>
                                        <th>Announcement</th>
                                        <th>Publish Date</th>
                                        <th>End Date</th>
                                        <th>Announce Status</th>
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