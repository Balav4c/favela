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
                                <li><span class="bread-blod">Residents</span>
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
            <form name="createresidents" id="createresidents" method="post" enctype="multipart/form-data">

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="welcome-wrapper shadow-reset res-mg-t mg-b-30">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="welcome-adminpro-title">
                                    <h1>Add New Residents</h1>
                                    <p>Enter the required details to add the residents.</p>
                                </div>

                                <div class="row aadhaar-type align-items-center mb-3">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input aadhar" type="radio" id="with_aadhaar"
                                                name="residence_method" value="with_aadhaar" checked>
                                            <label class="form-check-label aadhar-label" for="with_aadhaar">With
                                                Aadhaar</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input aadhar" type="radio" id="without_aadhaar"
                                                name="residence_method" value="without_aadhaar">
                                            <label class="form-check-label aadhar-label" for="without_aadhaar">Without
                                                Aadhaar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="adminpro-message-list aadhaar-card-info">
                            <div class="row">
                                <div class="col-md-12"><label for="buildname">Residents Aadhaar Card No.</label></div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="aadhaar_no" id="aadhaar_no"
                                            placeholder="Enter the aadhaar card number." value="" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary" id="verifyaadhaar">Verify
                                        Aadhaar</button>
                                </div>
                            </div>
                        </div>

                        <!-- Manual entry fields (for Without Aadhaar) -->
                        <div class="adminpro-message-list manual-details" style="display:none;">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="manual_name" id="manual_name"
                                            placeholder="Enter name" />
                                    </div>

                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select class="form-control" name="manual_gender" id="manual_gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>DOB</label>
                                        <input type="date" class="form-control" name="manual_dob" id="manual_dob" />
                                    </div>

                                    <div class="form-group">
                                        <label>Choose ID Proof</label>
                                        <select class="form-control" name="idproof" id="idProof">
                                            <option value="">Select One</option>
                                            <option value="pan_card">PAN Card</option>
                                            <option value="vote_id">Voter Id</option>
                                            <option value="driving_license">Driving License</option>
                                        </select>
                                    </div>


                                </div>

                                <!-- Full Width Address -->
                                <div class="col-md-12">

                                    <div class="form-group id-proof-input" style="display: none;">
                                        <label id="idProofLabel"></label>
                                        <input type="text" class="form-control" id="idProofNumber"
                                            name="id_proof_number" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" name="manual_address" id="manual_address"
                                            rows="3" placeholder="Enter full address"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><strong>Upload or Take Photo</strong></label>

                                            <div class="row justify-content-center mt-2">
                                                <!-- 🖼️ Drag & Drop Upload Zone -->
                                                <div class="col-md-6 col-sm-12 mb-3">
                                                    <div id="dropZone"
                                                        style="border: 2px dashed #007bff; border-radius: 10px; padding: 25px; max-width: 250px; margin: auto; cursor: pointer;">
                                                        <p id="dropText" class="m-0">Drag & Drop Photo Here<br>or Click
                                                            to Browse</p>
                                                        <input type="file" accept="image/*" id="manual_photo"
                                                            name="manual_photo" style="display:none;" />
                                                    </div>

                                                    <!-- Uploaded Image Preview -->
                                                    <img id="uploadPreview" src="" alt="Uploaded Photo"
                                                        style="display:none; margin-top:10px; max-width:200px; border-radius:8px;" />
                                                </div>

                                                <!-- 📸 Camera Capture Section -->
                                                <div class="col-md-6 col-sm-12 mb-3">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <button type="button" class="btn btn-primary mb-2"
                                                            id="openCamera">📸 Take Photo</button>

                                                        <video id="cameraPreview" autoplay
                                                            style="display:none; margin-top:10px; max-width:250px; border-radius:10px;"></video>

                                                        <button type="button" id="capturePhoto"
                                                            class="btn btn-success mt-2"
                                                            style="display:none;">Capture</button>

                                                        <canvas id="capturedCanvas"
                                                            style="display:none; margin-top:10px; max-width:250px; border-radius:8px;"></canvas>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 🗑 Delete button -->
                                            <button type="button" id="deletePhoto" class="btn btn-danger mt-2"
                                                style="display:none;">🗑 Delete Photo</button>

                                            <!-- Hidden field for captured image -->
                                            <input type="hidden" name="captured_image" id="captured_image">
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>




                        <div class="c-userdetails">
                            <div class="row">
                                <div class="col-md-3 c-profilepic">
                                    <img src="" />
                                </div>
                                <div class="col-md-5">
                                    <b>Name:</b>&nbsp;<span class="cname"></span><br />
                                    <b>Aadhaar No.:</b>&nbsp;<span class="aadharno"></span><br />
                                    <b>Gender:</b>&nbsp;<span class="cgender"></span><br />
                                    <b>DOB:</b>&nbsp;<span class="cdob"></span><br />
                                </div>
                                <div class="col-md-4 change-user text-end">
                                    <button type="button" class="btn btn-default" id="change-resident"><i
                                            class="fa fa-id-card-o" aria-hidden="true"></i>&nbsp;Wrong Idendity</button>
                                </div>
                                <div class="col-md-4 close-details text-end">
                                    <button type="button" class="btn btn-default" id="close-resident"><i
                                            class="fa fa-id-card-o" aria-hidden="true"></i>&nbsp;Close Details</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <b>Address:</b><br />
                                    <span class="caddress"></span>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 inst-info" id="instructions_section">
                    <h4>Instructions</h4>
                    <ul>
                        <li><i class="fa fa-crosshairs" aria-hidden="true"></i>&nbsp;All new users must be aadhaar
                            verified to validate the authenticity of the profiles.</li>
                        <li><i class="fa fa-crosshairs" aria-hidden="true"></i>&nbsp;OTP will be send to the mobile
                            number registered with the aadhaar card.</li>
                        <li><i class="fa fa-crosshairs" aria-hidden="true"></i>&nbsp;Any profiles that are already
                            verified will be displayed with the informations shared with Favela.
                            <li />
                        <li><i class="fa fa-crosshairs" aria-hidden="true"></i>&nbsp;Verify the details and accept the
                            user profiles to your account.</li>
                        <li><i class="fa fa-crosshairs" aria-hidden="true"></i>&nbsp;Any information which does not
                            match with the given proofs must be reported to authority. </li>

                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 res-contact" id="contact_info_section">
                    <div class="welcome-wrapper shadow-reset res-mg-t mg-b-30">
                        <div class="welcome-adminpro-title">
                            <h1>Add Contact Info</h1>
                            <p>Enter the required details to save the contact info.</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Id</label>
                                    <input class="form-control" type="text" name="email" id="email" value="" />
                                    <small>Notifications will be send to this email id.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Contact No</label>
                                    <input class="form-control" type="text" name="contactno" id="contactno" value="" />
                                    <small>This number will be used to login to Favela App.</small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">Communication Address</label>
                                    <textarea class="form-control" type="text" name="caddress" id="caddress" value=""
                                        rows="5"></textarea>
                                    <small>All postal's will be send to this address.</small>
                                </div>
                            </div>
                            <div class="col-md-12 text-end">
                                <input type="hidden" name="user_id" id="user_id" />
                                <input type="hidden" name="aadhaar_hd" id="aadhaar_hd" />
                                <button type="button" class="btn btn-primary" id="saveUser">Save User</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline8-list shadow-reset">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1>Listed Residents</h1>
                        </div>
                    </div>
                    <div class="sparkline8-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <table class="table table-borderless datatable" id="residentslist">
                                <thead>
                                    <tr>
                                        <th>Slno</th>
                                        <th>Resident Name</th>
                                        <th>Aadhaar No.</th>
                                        <th>Mobile No.</th>
                                        <th>Email Id</th>
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
<div class="modal fade" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="progress" style="height: 8px;">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
            <div class="modal-body" id="existing-user">
                <div class="row">
                    <div class="col-md-12 response-msg"></div>
                    <div class="col-md-12 text-center">
                        <h4>Aadhar Card Info</h4>
                    </div>
                </div>
                <div class="col-md-12 bordered">
                    <div class="row">
                        <div class="col-md-3 profilepic">
                            <img src="<?php echo base_url('public/img/profile-pic.png'); ?>" />
                        </div>
                        <div class="col-md-9">
                            <div class="col-md-12 tx-formated">
                                <b>Name:</b>&nbsp;<span class="name"></span><br />
                                <b>Gender:</b>&nbsp;<span class="gender"></span><br />
                                <b>DOB:</b>&nbsp;<span class="dob"></span><br />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-md-12 bordered">
                    <div class="row">
                        <div class="col-md-12 tx-formated">
                            <b>Address:</b><br />
                            <span class="address"></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="proceed" data-uid="">Proceed</button>
                </div>
            </div>
            <div class="modal-body" id="send-otp">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h4>Verfiy Aadhaar</h4>
                    </div>
                    <div class="col-md-12 response-msg"></div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-primary btn-block" id="sendOTP">Send OTP</button>
                    </div>
                    <div class="verify-otp">
                        <div class="col-md-12">
                            <div class="clearfix">&nbsp;</div>
                            <p>Enter OTP send to the mobile number registered with aadhaar.</p>
                            <div class="row">
                                <form id="otps">
                                    <div class="col-md-2"><input type="text" name="otp" id="otp"
                                            class="form-control otp" maxlength="1" /></div>
                                    <div class="col-md-2"><input type="text" name="otp" id="otp"
                                            class="form-control otp" maxlength="1" /></div>
                                    <div class="col-md-2"><input type="text" name="otp" id="otp"
                                            class="form-control otp" maxlength="1" /></div>
                                    <div class="col-md-2"><input type="text" name="otp" id="otp"
                                            class="form-control otp" maxlength="1" /></div>
                                    <div class="col-md-2"><input type="text" name="otp" id="otp"
                                            class="form-control otp" maxlength="1" /></div>
                                    <div class="col-md-2"><input type="text" name="otp" id="otp"
                                            class="form-control otp" maxlength="1" /></div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="clearfix">&nbsp;</div>
                            <button type="button" class="btn btn-success btn-block" id="verifyOTP">Verify OTP</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="right-slider shadow-reset">
    <div class="col-md-12 rslide-head">
        <div class="row">
            <div class="col-md-8">
                <h4>Flat Door No.</h4>
            </div>
            <div class="col-md-12">
                <p>Enter the required details to add the door details.</p>
            </div>
            <div class="col-md-12 resname"></div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <input type="text" name="doorno" id="doorno" class="form-control" placeholder="Eg. 206"
                    onKeyUp="$(this).val($(this).val().replace(/[^\d]/ig, ''))" />
            </div>
            <div class="col-md-5 lt-gutter-0">
                <button class="btn btn-primary" onclick="addNewDoor();">Add To List</button>
                <input type="hidden" name="list_us_id" id="list_us_id" />
            </div>
        </div>
    </div>
    <div class="door-list"></div>
</div>