<script>
var residentslist;
var aadharno;

function verifyAadhaar() {
    var aadhaarInput = $('#aadhaar_no').val().trim();

    // === FRONT-END VALIDATION ===
    if (aadhaarInput === '') {
        initAlert("Please enter your Aadhaar number before verifying.", 0);
        return false; // stop further execution
    }

    // Optional: Validate Aadhaar length (should be 12 digits)
    if (!/^\d{12}$/.test(aadhaarInput)) {
        initAlert("Invalid Aadhaar number format. Please enter 12 digits.", 0);
        return false;
    }
    var url = baseUrl + "residents/verifyaadhaar";
    var aadhno = btoa($('#aadhaar_no').val());
    $.post(url, {
        aadhno: aadhno
    }, function(data) {
        $('#verificationModal').modal('show');
        $('#verificationModal').find('.modal-body').hide();

        if (data.status == 1) {
            $('.progress').show();
            $(".progress-bar").css('width', '0%');
            $(".progress-bar").animate({
                width: 100 + '%'
            }, {
                duration: 500,
                specialEasing: {
                    width: "linear"
                },
                complete: function() {
                    $('#existing-user').show();
                    $('.response-msg').html(data.message);
                    $('.name').html(data.userdetails.name);
                    $('.gender').html(data.userdetails.gender);
                    $('.dob').html(data.userdetails.dob);
                    $('.address').html(data.userdetails.p_address);
                    $('#proceed').attr('data-uid', data.userdetails.uid);
                    if (data.userdetails.profile_photo) {
                        $('.profilepic').find('img').attr('src', data.userdetails.profile_photo);
                    }
                    $('#proceed').click(function() {
                        var userId = $(this).attr('data-uid');
                        var acpt_url = baseUrl + "residents/acceptUser";
                        $.post(acpt_url, {
                            userId
                        }, function(data) {
                            $('#verificationModal').modal('hide');
                            if (data.status == 1) {
                                initAlert(data.message, 1);
                            } else {
                                initAlert(data.message, 3);
                            }
                            aadharno = $('#aadhaar_no').val();
                            $('#aadhaar_no').val('');
                            $('.aadharno').html(aadharno);
                            $('.cname').html(data.userinfo.name);
                            $('.cgender').html(data.userinfo.gender);
                            $('.cdob').html(data.userinfo.dob);
                            $('.caddress').html(data.userinfo.p_address);
                            $('#contactno').val(data.userinfo.phone);
                            $('#email').val(data.userinfo.email_id);
                            $('#caddress').val(data.userinfo.c_address);
                            $('#user_id').val(data.userinfo.uid);
                            $('#aadhaar_hd').val(data.userinfo.aadhar_no);
                            if (data.userinfo.profile_photo) {
                                $('.c-profilepic').find('img').attr('src', data
                                    .userinfo.profile_photo);
                            }
                            $('.c-userdetails').show();
                            $('.aadhaar-card-info').hide();
                            $('.inst-info').hide();
                            $('.res-contact').show();
                            residentslist.ajax.reload();
                        }, 'json');
                    });
                }
            });
        } else if (data.status == 2) {
            $('.progress').show();
            $(".progress-bar").css('width', '0%');
            $(".progress-bar").animate({
                width: 100 + '%'
            }, {
                duration: 500,
                specialEasing: {
                    width: "linear"
                },
                complete: function() {
                    $('#send-otp').show();
                    $('#otps')[0].reset();
                    $('.response-msg').html(data.message);
                    $('#sendOTP').click(function() {
                        var sendotpurl = baseUrl + "residents/sendotp";
                        $.post(sendotpurl, {
                            aadhno
                        }, function(response) {
                            if (response.result.code == 200 && response.result.data
                                .ref_id) {
                                var tempOtp = '808936';
                                $('.verify-otp').show();
                                var currentBoxNumber = 0;
                                $(".otp").keyup(function(event) {
                                    textboxes = $("input.otp");
                                    currentBoxNumber = textboxes.index(
                                        this);
                                    if (textboxes[currentBoxNumber + 1] !=
                                        null) {
                                        nextBox = textboxes[
                                            currentBoxNumber + 1];
                                        nextBox.focus();
                                        nextBox.select();
                                        event.preventDefault();
                                        return false;
                                    }
                                });
                                var ref_id = response.result.data.ref_id;
                                var otp = '';
                                $('#verifyOTP').click(function() {
                                    $('.otp').each(function() {
                                        otp = otp + $(this).val();
                                    });
                                    var verifyurl = baseUrl +
                                        'residents/verifyotp';
                                    $.post(verifyurl, {
                                        otp,
                                        ref_id
                                    }, function(verifyresp) {

                                        if (verifyresp) {
                                            var p_address =
                                                verifyresp.result
                                                .split_address
                                                .house + "," +
                                                verifyresp.result
                                                .split_address
                                                .landmark + "," +
                                                verifyresp.result
                                                .split_address
                                                .dist + "," +
                                                verifyresp.result
                                                .split_address
                                                .country + "," +
                                                verifyresp.result
                                                .split_address
                                                .pincode;
                                            $('#verificationModal')
                                                .modal('hide');
                                            aadharno = $(
                                                    '#aadhaar_no')
                                                .val();
                                            $('#aadhaar_no').val(
                                                '');
                                            $('.aadharno').html(
                                                aadharno);
                                            $('.cname').html(
                                                verifyresp
                                                .result.name);
                                            $('.cgender').html(
                                                verifyresp
                                                .result.gender);
                                            $('.cdob').html(
                                                verifyresp
                                                .result.dob);
                                            $('.caddress').html(
                                                p_address);
                                            $('#aadhaar_hd').val(
                                                aadharno);
                                            if (verifyresp.result
                                                .photo_link) {
                                                //$('.c-profilepic').find('img').attr('src',"data:image/jpeg;base64,"+verifyresp.result.photo_link);
                                                $('.c-profilepic')
                                                    .find('img')
                                                    .attr('src',
                                                        verifyresp
                                                        .result
                                                        .photo_link
                                                    );
                                            }
                                            $('#user_id').val(
                                                verifyresp
                                                .userId);
                                            $('.c-userdetails')
                                                .show();
                                            $('.aadhaar-card-info')
                                                .hide();
                                            $('.inst-info').hide();
                                            $('.res-contact')
                                                .show();
                                            if (verifyresp
                                                .newuser == 1) {
                                                $('.change-user')
                                                    .show();
                                                $('#change-resident')
                                                    .click(
                                                        function() {
                                                            $('.change-user')
                                                                .hide();
                                                            var __userId =
                                                                verifyresp
                                                                .userId;
                                                            var changeUserUrl =
                                                                baseUrl +
                                                                'residents/changeuser';
                                                            $.post(changeUserUrl, {
                                                                    userId: __userId
                                                                },
                                                                function(
                                                                    resp
                                                                ) {
                                                                    if (
                                                                        resp
                                                                    ) {
                                                                        $('.c-userdetails')
                                                                            .hide();
                                                                        $('.res-contact')
                                                                            .hide();
                                                                        $('.verify-otp')
                                                                            .hide();
                                                                        $('.aadhaar-card-info')
                                                                            .show();
                                                                        $('.inst-info')
                                                                            .show();
                                                                    }
                                                                },
                                                                'json'
                                                            );
                                                        })
                                            }
                                            residentslist.ajax
                                                .reload();
                                        }
                                    }, 'json');
                                });
                            } else {
                                $('#verificationModal').modal('hide');
                                initAlert(response.result.data.message, 0);
                            }
                        }, 'json');
                    });
                }
            });
        } else if (data.status == 0) {
            $('#verificationModal').modal('hide');
            initAlert(data.message, 0);
        }
    }, 'json');
}

function deleteFlatUser(us_id) {

    var delUserUrl = baseUrl + 'residents/deleteUser/' + us_id;
    initDelConfirm("Your data will be lost. Are you sure you want to delete the resident?", 2, delUserUrl,
        residentslist, '');
}

function statcheck(el) {
    var us_status = ($(el).is(':checked') ? $(el).val() : 2);
    var us_id = $(el).attr('data-id');
    var url = baseUrl + "residents/changeStatus";
    $.post(url, {
        us_status,
        us_id
    }, function(data) {
        initAlert("Status changed successfully.", 1);
    }, 'json');
}

// function userDetails(us_id) {
//     if (us_id) {
//         var url = baseUrl + "residents/userDetails";
//         $.post(url, {
//             us_id
//         }, function(data) {
//             if (data) {
//                 $('.aadhaar-card-info').hide();
//                 $('.inst-info').hide();
//                 $('.c-userdetails').show();
//                 $('.close-details').show();
//                 $('.res-contact').show();
//                 $('.cname').html(data.name);
//                 $('.aadharno').html(data.aadhar_no);
//                 $('.cgender').html(data.gender);
//                 $('.cdob').html(data.dob);
//                 $('.caddress').html(data.p_address);
//                 $('.c-profilepic').find('img').attr('src', data.profile_photo);
//                 //$('.c-profilepic').find('img').attr('src',"data:image/jpeg;base64,"+verifyresp.result.photo_link);
//                 $('#email').val(data.email_id);
//                 $('#contactno').val(data.phone);
//                 $('#caddress').val(data.c_address);
//                 $('#user_id').val(data.uid);
//                 $('#aadhaar_hd').val(data.aadhar_no);

//                 $('#close-resident').click(function() {
//                     $('.aadhaar-card-info').show();
//                     $('.inst-info').show();
//                     $('.c-userdetails').hide();
//                     $('.close-details').hide();
//                     $('.res-contact').hide();
//                     $('#email').val('');
//                     $('#contactno').val('');
//                     $('#caddress').val('');
//                     $('#user_id').val('');
//                     $('#aadhaar_hd').val('');
//                 });
//             } else {
//                 initAlert("User not found in the system", 0);
//             }
//         }, 'json');
//     }
// }

function userDetails(us_id) {
    if (us_id) {
        var url = baseUrl + "residents/userDetails";
        $.post(url, {
            us_id
        }, function(data) {
            if (data) {
                // Hide all common sections first
                $('.c-userdetails').hide();
                $('.close-details').show();
                $('.inst-info').hide();
                $('.res-contact').show();

                // Prefill contact info
                $('#email').val(data.email_id || '');
                $('#contactno').val(data.phone || '');
                $('#caddress').val(data.c_address || '');
                $('#user_id').val(data.uid || '');

                // Check if Aadhaar is available 
                if (data.aadhar_no && data.aadhar_no !== '') {
                    //  Show With Aadhaar section
                    $('input[name="residence_method"][value="with_aadhaar"]').prop('checked', true);
                    $('.aadhaar-card-info').hide();
                    $('.c-userdetails').show();
                    $('.manual-details').hide();


                    // Prefill Aadhaar info
                    $('#aadhaar_no').val(data.aadhar_no);
                    $('#aadhaar_hd').val(data.aadhar_no);
                    $('.cname').html(data.name);
                    $('.aadharno').html(data.aadhar_no);
                    $('.cgender').html(data.gender);
                    $('.cdob').html(data.dob);
                    $('.caddress').html(data.p_address);
                    $('.c-profilepic img').attr('src', data.profile_photo || 'assets/img/default-user.png');
                } else {
                    // Show Without Aadhaar section
                    $('input[name="residence_method"][value="without_aadhaar"]').prop('checked', true);
                    $('.aadhaar-card-info').hide();
                    $('.manual-details').show();

                    // Prefill manual fields
                    $('#manual_name').val(data.name || '');
                    $('#manual_gender').val(data.gender || '');
                    $('#manual_dob').val(data.dob || '');
                    $('#manual_address').val(data.p_address || '');
                    $('.c-profilepic img').attr('src', data.profile_photo || 'assets/img/default-user.png');
                    // --- Handle ID Proof prefill correctly ---
                    if (data.id_proof) {
                        // Set dropdown without triggering change immediately
                        $('#idProof').val(data.id_proof);

                        // Manually update label and placeholder (same logic as your change event)
                        const $label = $('#idProofLabel');
                        const $inputGroup = $('.id-proof-input');
                        const $input = $('#idProofNumber');

                        let labelText = '';
                        let placeholderText = '';

                        switch (data.id_proof) {
                            case 'pan_card':
                                labelText = 'Enter PAN Card Number';
                                placeholderText = 'e.g. ABCDE1234F';
                                break;
                            case 'vote_id':
                                labelText = 'Enter Voter ID Number';
                                placeholderText = 'e.g. TN/12/123/456789';
                                break;
                            case 'driving_license':
                                labelText = 'Enter Driving License Number';
                                placeholderText = 'e.g. TN09 20220012345';
                                break;
                            default:
                                labelText = '';
                                placeholderText = '';
                        }

                        // Show and fill the field correctly
                        $label.text(labelText);
                        $input.attr('placeholder', placeholderText);
                        $input.val(data.id_proof_number || '');
                        $inputGroup.show();
                    } else {
                        // No ID proof, hide the field
                        $('.id-proof-input').hide();
                        $('#idProofNumber').val('');
                    }

                }

                // --- Show close button and define its action ---
                $('#close-resident').off('click').on('click', function() {
                    $('.aadhaar-card-info').show();
                    $('.inst-info').show();
                    $('.c-userdetails').hide();
                    $('.close-details').hide();
                    $('.res-contact').hide();

                    // Reset all fields
                    $('#email, #contactno, #caddress, #user_id, #aadhaar_hd, #aadhaar_no, #manual_name, #manual_gender, #manual_dob, #manual_address, #idProofNumber')
                        .val('');
                    $('#idProof').val('');
                });

            } else {
                initAlert("User not found in the system", 0);
            }
        }, 'json');
    }
}


function addNewDoor() {
    var doorno = $('#doorno').val();
    var list_us_id = $('#list_us_id').val();
    var durl = baseUrl + "residents/addnewdoor";
    $.post(durl, {
        doorno,
        list_us_id
    }, function(response) {
        if (response.status == 1) {
            initAlert(response.message, 1);
            listdoors();
            $('#doorno').val('');
        } else {
            initAlert(response.message, 0);
        }
    }, 'json');
}

function listdoors() {
    var listurl = baseUrl + "residents/listdoor";
    var list_us_id = $('#list_us_id').val();
    var doorico = '<?php echo base_url('public/img/door.jpg'); ?>';
    $('.door-list').empty();
    $('.door-list').html('<div class="col-md-12 text-center door-block">Loading Data..</div>');
    $.post(listurl, {
        list_us_id
    }, function(response) {
        if (response) {
            $('.door-list').empty();
            for (var i = 0; i < response.length; i++) {
                $('.door-list').append('<div class="col-md-12 door-block">\
											<div class="col-md-1 gutter-0">\
												<img src="' + doorico + '" />\
											</div>\
											<div class="col-md-9 door-no-block">\
												Door No.&nbsp;' + response[i].door_no + '\
											</div>\
											<div class="col-md-1 door-no-delete">\
												<i class="fa fa-trash" data-did="' + response[i].door_id + '"></i>\
											</div>\
										</div>');
            }
            $('.door-no-delete').find('.fa-trash').click(function() {
                var did = $(this).attr('data-did');
                var deldoorUrl = baseUrl + 'residents/deleteDoor/' + did;
                initDelConfirm("Are you sure you want to delete the door?", 2, deldoorUrl, '',
                    listdoors);
            });
        }
    }, 'json');
}

function userDoors(us_id, resname) {
    $('#list_us_id').val(us_id);
    $('.resname').html('<p>Resident: ' + resname + '</p>');
    listdoors();
    $('.right-slider').slideDown();
}
$(document).ready(function() {
    residentslist = $('#residentslist').DataTable({
        'paging': true,
        'sort': true,
        'searching': true,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': baseUrl + "residents/listresidents"
        },
        'columns': [{
                data: 'slno'
            },
            {
                data: 'res_name'
            },
            {
                data: 'res_aadhaar'
            },
            {
                data: 'res_phone'
            },
            {
                data: 'res_email'
            },
            {
                data: 'status'
            },
            {
                data: 'action'
            }
        ]
    });

    $('#verifyaadhaar').click(function() {
        verifyAadhaar();
    });
    $('#aadhaar_no').keyup(function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode == 13) {
            e.preventDefault();
        }
    });
    // $('#saveUser').click(function() {
    //     var saveUrl = baseUrl + "residents/updateuser";
    //     $('#aadharno').val(aadharno);
    //     $.post(saveUrl, $('#createresidents').serialize(), function(data) {
    //         if (data == 1) {
    //             initAlert("Resident details saved successfully!", 1);
    //             $('.c-userdetails').hide();
    //             $('.res-contact').hide();
    //             $('.inst-info').show();
    //             $('.aadhaar-card-info').show();
    //             residentslist.ajax.reload();
    //         } else {
    //             initAlert(
    //                 "Cant save the details at the moment. Something went wrong. Please contact support.!",
    //                 1);
    //         }
    //     }, 'json');
    // });
    $('#saveUser').click(function() {
        var saveUrl = baseUrl + "residents/updateuser";
        var residenceMethod = $('input[name="residence_method"]:checked').val();
        var user_id = $('#user_id').val().trim();

        // --- Validation for "Without Aadhaar" ---
        if (residenceMethod === 'without_aadhaar') {
            var manual_name = $('#manual_name').val().trim();
            var manual_gender = $('#manual_gender').val();
            var manual_dob = $('#manual_dob').val();
            var manual_address = $('#manual_address').val().trim();
            var idproof = $('#idProof').val();
            var id_proof_number = $('#idProofNumber').val().trim();

            // Check required fields
            if (
                manual_name === '' ||
                manual_gender === '' ||
                manual_dob === '' ||
                manual_address === '' ||
                idproof === '' ||
                id_proof_number === ''
            ) {
                initAlert("Please fill in all required fields.", 0);
                return false;
            }

            // --- ID Proof Specific Validation ---
            let pattern, errorMsg;

            switch (idproof) {
                case 'pan_card':
                    pattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/; // PAN: ABCDE1234F
                    errorMsg = "Invalid PAN Card Number. Format: ABCDE1234F";
                    break;
                case 'vote_id':
                    pattern = /^[A-Z]{2}\/[0-9]{2}\/[0-9]{3}\/[0-9]{6}$/; // Example: TN/12/123/456789
                    errorMsg = "Invalid Voter ID Number. Format: TN/12/123/456789";
                    break;
                case 'driving_license':
                    pattern = /^[A-Z]{2}[0-9]{2}\s?[0-9]{11}$/; // Example: TN09 20220012345
                    errorMsg = "Invalid Driving License Number. Format: TN09 20220012345";
                    break;
                default:
                    pattern = null;
            }

            if (pattern && !pattern.test(id_proof_number)) {
                initAlert(errorMsg, 0); // Show validation error
                return false;
            }
        }

        // --- Validation for "With Aadhaar" ---
        if (residenceMethod === 'with_aadhaar') {
            var aadhaar_no = $('#aadhaar_hd').val().trim();
            if (aadhaar_no === '') {
                initAlert("Please verify Aadhaar before saving.", 0);
                return false;
            }
            // Optional: Aadhaar number format check (12 digits)
            if (!/^\d{12}$/.test(aadhaar_no)) {
                initAlert("Invalid Aadhaar number. It must be 12 digits.", 0);
                return false;
            }
        }

        // --- Proceed to Save ---
        $.post(saveUrl, $('#createresidents').serialize(), function(data) {
            if (data == 1) {
                initAlert("Resident details saved successfully!", 1);

                // If new user → reset form
                if (user_id === '' || user_id === null) {
                    $('#createresidents')[0].reset();
                    $('input[name="residence_method"][value="' + residenceMethod + '"]').prop(
                        'checked', true);
                    $('.c-userdetails').hide();
                    $('.res-contact').show();
                    $('.manual-details').show();
                    $('.inst-info').hide();
                    $('.aadhaar-card-info').hide();
                }

                // Reload DataTable
                residentslist.ajax.reload(null, false);

            } else {
                initAlert(
                    "Can't save the details at the moment. Something went wrong. Please contact support!",
                    3
                );
            }
        }, 'json');
    });

    // $('#saveUser').click(function() {
    //     var saveUrl = baseUrl + "residents/updateuser";
    //     var residenceMethod = $('input[name="residence_method"]:checked').val();
    //     var user_id = $('#user_id').val().trim();

    //     // Validate fields (same as your existing code)
    //     if (residenceMethod === 'without_aadhaar') {
    //         var manual_name = $('#manual_name').val().trim();
    //         var manual_gender = $('#manual_gender').val();
    //         var manual_dob = $('#manual_dob').val();
    //         var manual_address = $('#manual_address').val().trim();
    //         var idproof = $('#idProof').val();
    //         var id_proof_number = $('#idProofNumber').val().trim();

    //         if (
    //             manual_name === '' ||
    //             manual_gender === '' ||
    //             manual_dob === '' ||
    //             manual_address === '' ||
    //             idproof === '' ||
    //             id_proof_number === ''
    //         ) {
    //             initAlert("Please fill in all required fields.", 0);
    //             return false;
    //         }
    //     }

    //     if (residenceMethod === 'with_aadhaar') {
    //         var aadhaar_no = $('#aadhaar_hd').val().trim();
    //         if (aadhaar_no === '') {
    //             initAlert("Please verify Aadhaar before saving.", 0);
    //             return false;
    //         }
    //         if (!/^\d{12}$/.test(aadhaar_no)) {
    //             initAlert("Invalid Aadhaar number. It must be 12 digits.", 0);
    //             return false;
    //         }
    //     }

    //     // --- Create FormData object ---
    //     var formData = new FormData($('#createresidents')[0]);

    //     // Check if user uploaded or captured a photo
    //     var uploadedFile = $('#upload_photo')[0].files[0];
    //     var capturedImage = $('#captured_image').val();

    //     if (uploadedFile) {
    //         formData.append('profile_photo_file', uploadedFile);
    //     } else if (capturedImage !== '') {
    //         formData.append('captured_image', capturedImage);
    //     }

    //     $.ajax({
    //         url: saveUrl,
    //         type: 'POST',
    //         data: formData,
    //         contentType: false,
    //         processData: false,
    //         dataType: 'json',
    //         success: function(data) {
    //             if (data == 1) {
    //                 initAlert("Resident details saved successfully!", 1);
    //                 $('#createresidents')[0].reset();
    //                 residentslist.ajax.reload(null, false);
    //             } else {
    //                 initAlert(
    //                     "Can't save the details at the moment. Please contact support!",
    //                     3);
    //             }
    //         },
    //         error: function() {
    //             initAlert("Error occurred while saving user details.", 3);
    //         }
    //     });
    // });







    $("body").keydown(function(e) {
        var AcckeyCode = e.keyCode || e.which;
        if (AcckeyCode == 27) {
            $('.right-slider').slideUp();
        }
    });

});




$(document).ready(function() {
    function toggleSections() {
        const selectedMethod = $('input[name="residence_method"]:checked').val();

        if (selectedMethod === 'with_aadhaar') {
            $('.aadhaar-card-info').show();
            $('#instructions_section').show();
            $('#contact_info_section').hide();
            $('.manual-details').hide();
            $('.c-userdetails').hide();
            $('#photoUpload').hide();
        } else if (selectedMethod === 'without_aadhaar') {
            $('.aadhaar-card-info').hide();
            $('#instructions_section').hide();
            $('#contact_info_section').show();
            $('.manual-details').show();
            $('.c-userdetails').hide();
            $('#photoUpload').show();
        }
    }

    toggleSections();
    $('input[name="residence_method"]').on('change', toggleSections);
});



//Id proof change

$('#idProof').on('change', function() {
    const selected = $(this).val();
    const $label = $('#idProofLabel');
    const $inputGroup = $('.id-proof-input');
    const $input = $('#idProofNumber');

    if (selected === '') {
        $inputGroup.hide();
        $input.val('');
        return;
    }

    // Change label and placeholder dynamically
    let labelText = '';
    let placeholderText = '';

    switch (selected) {
        case 'pan_card':
            labelText = 'Enter PAN Card Number';
            placeholderText = 'e.g. ABCDE1234F';
            break;
        case 'vote_id':
            labelText = 'Enter Voter ID Number';
            placeholderText = 'e.g. TN/12/123/456789';
            break;
        case 'driving_license':
            labelText = 'Enter Driving License Number';
            placeholderText = 'e.g. TN09 20220012345';
            break;
    }

    // Update label and placeholder
    $label.text(labelText);
    $input.attr('placeholder', placeholderText);

    // Clear previous input whenever proof type changes
    $input.val('');

    // Show input field
    $inputGroup.show();
});




// Radio button toggle
$('input[name="residence_method"]').change(function() {
    var val = $(this).val();
    if (val === 'with_aadhaar') {
        // Show Aadhaar section
        $('.aadhaar-card-info').show();
        $('.manual-details').hide();

        // Clear manual fields to avoid old prefilled data
        $('#manual_name, #manual_gender, #manual_dob, #manual_address, #idProof, #idProofNumber').val('');
    } else {
        // Show manual section
        $('.aadhaar-card-info').hide();
        $('.manual-details').show();

        // Clear Aadhaar field to avoid old prefilled data
        $('#aadhaar_no, #aadhaar_hd').val('');
        $('#email, #contactno, #caddress').val('');
    }
});


// --- For upload preview ---

document.getElementById('upload_photo').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('uploadPreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// --- Camera + Delete setup ---
const openCameraBtn = document.getElementById('openCamera');
const cameraPreview = document.getElementById('cameraPreview');
const capturePhotoBtn = document.getElementById('capturePhoto');
const capturedCanvas = document.getElementById('capturedCanvas');
const capturedImageInput = document.getElementById('captured_image');
const deletePhotoBtn = document.getElementById('deletePhoto');
let stream;

openCameraBtn.addEventListener('click', async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: true
        });
        cameraPreview.srcObject = stream;
        cameraPreview.style.display = 'block';
        capturePhotoBtn.style.display = 'inline-block';
        capturedCanvas.style.display = 'none';
        deletePhotoBtn.style.display = 'none';
    } catch (err) {
        alert('Camera access denied or unavailable.');
        console.error(err);
    }
});

capturePhotoBtn.addEventListener('click', () => {
    const context = capturedCanvas.getContext('2d');
    capturedCanvas.width = cameraPreview.videoWidth;
    capturedCanvas.height = cameraPreview.videoHeight;
    context.drawImage(cameraPreview, 0, 0, cameraPreview.videoWidth, cameraPreview.videoHeight);
    capturedCanvas.style.display = 'block';
    deletePhotoBtn.style.display = 'inline-block';

    // Stop the camera stream
    stream.getTracks().forEach(track => track.stop());
    cameraPreview.style.display = 'none';
    capturePhotoBtn.style.display = 'none';

    // Save base64 image
    capturedImageInput.value = capturedCanvas.toDataURL('image/png');
});

// --- Delete photo logic ---
deletePhotoBtn.addEventListener('click', () => {
    capturedCanvas.style.display = 'none';
    deletePhotoBtn.style.display = 'none';
    capturedImageInput.value = '';

    // Optionally allow retaking photo
    openCameraBtn.style.display = 'inline-block';
});
</script>