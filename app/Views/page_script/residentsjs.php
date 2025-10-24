<script>
var residentslist;
var aadharno;

function verifyAadhaar() {
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
    if (!us_id) return;

    $.post(baseUrl + "residents/userDetails", {
        us_id
    }, function(data) {
        if (!data || data.error) {
            initAlert("User not found", 0);
            return;
        }

        // Reset form & hide sections
        $('#createresidents')[0].reset();
        $('.aadhaar-card-info, .manual-details, .c-userdetails, .res-contact, .close-details').hide();
        $('#uploadPreview').hide();

        // Show close button and contact info
        $('.close-details, .res-contact').show();

        if (data.residence_method === 'with_aadhaar') {
            $('input[name="residence_method"][value="with_aadhaar"]').prop('checked', true);

            // Only show details, hide editable Aadhaar input
            $('.aadhaar-card-info').hide();
            $('.manual-details').hide();
            $('.c-userdetails').show();
            $('.inst-info').hide();

            // Prefill details
            $('.cname').text(data.name || '');
            $('.aadharno').text(data.aadhar_no || '');
            $('.cgender').text(data.gender || '');
            $('.cdob').text(data.dob || '');
            $('.caddress').text(data.p_address || '');

            // Profile photo (Base64 from controller)
            let photoPath = data.profile_photo ?
                data.profile_photo :
                baseUrl + "assets/images/default-user.png";
            $('.c-profilepic img').attr('src', photoPath);

            // Prefill contact info (editable)
            $('#email').val(data.email_id || '');
            $('#contactno').val(data.phone || '');
            $('#caddress').val(data.c_address || '');
            $('#user_id').val(data.us_id || '');
            $('#aadhaar_hd').val(data.aadhar_no || ''); // hidden field for update

        } else {
            // Without Aadhaar
            $('input[name="residence_method"][value="without_aadhaar"]').prop('checked', true);
            $('.manual-details, .res-contact').show();
            $('.aadhaar-card-info, .c-userdetails, .inst-info').hide();

            $('#manual_name').val(data.name || '');
            $('#manual_gender').val(data.gender || '');
            $('#manual_dob').val(data.dob || '');
            $('#manual_address').val(data.p_address || '');
            $('#idProof').val(data.id_proof || '');
            $('#idProofNumber').val(data.id_proof_number || '');
            $('#uploadPreview').attr('src', data.manual_photo ?
                baseUrl + "uploads/profile_photos/" + data.manual_photo :
                baseUrl + "assets/images/default-user.png").show();

            $('#email').val(data.email_id || '');
            $('#contactno').val(data.phone || '');
            $('#caddress').val(data.c_address || '');
            $('#user_id').val(data.us_id || '');
        }

        // Close button resets form
        $('#close-resident').off('click').on('click', function() {
            $('#createresidents')[0].reset();
            $('.aadhaar-card-info').show();
            $('.inst-info').show();
            $('.manual-details, .c-userdetails, .res-contact, .close-details').hide();
            $('#uploadPreview').hide();
            $('#user_id').val('');
            $('#aadhaar_hd').val('');
            $('input[name="residence_method"][value="with_aadhaar"]').prop('checked', true);
        });

    }, 'json');
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
   $('#saveUser').click(function (e) {
    e.preventDefault();

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

        if (!manual_name || !manual_gender || !manual_dob || !manual_address || !idproof || !id_proof_number) {
            initAlert("Please fill in all required fields.", 0);
            return false;
        }

        // --- ID Proof Validation ---
        let pattern, errorMsg;
        switch (idproof) {
            case 'pan_card':
                pattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
                errorMsg = "Invalid PAN Card Number. Format: ABCDE1234F";
                break;
            case 'vote_id':
                pattern = /^[A-Z]{2}\/[0-9]{2}\/[0-9]{3}\/[0-9]{6}$/;
                errorMsg = "Invalid Voter ID Number. Format: TN/12/123/456789";
                break;
            case 'driving_license':
                pattern = /^[A-Z]{2}[0-9]{2}\s?[0-9]{11}$/;
                errorMsg = "Invalid Driving License Number. Format: TN09 20220012345";
                break;
        }

        if (pattern && !pattern.test(id_proof_number)) {
            initAlert(errorMsg, 0);
            return false;
        }
    }

    // --- Validation for "With Aadhaar" ---
    if (residenceMethod === 'with_aadhaar') {
        var aadhaar_no = $('#aadhaar_hd').val().trim();
        if (!aadhaar_no) {
            initAlert("Please verify Aadhaar before saving.", 0);
            return false;
        }
        if (!/^\d{12}$/.test(aadhaar_no)) {
            initAlert("Invalid Aadhaar number. It must be 12 digits.", 0);
            return false;
        }
    }

    // --- Prepare FormData (includes file uploads and captured image) ---
    var formData = new FormData($('#createresidents')[0]);

    // ✅ Ensure user_id and residence_method are included explicitly
    formData.append('user_id', user_id);
    formData.append('residence_method', residenceMethod);

    // ✅ Optional: ensure captured image is updated before sending
    if ($('#captured_image').val()) {
        formData.append('captured_image', $('#captured_image').val());
    }

    // --- AJAX call ---
    $.ajax({
        url: saveUrl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',

        success: function (data) {
            console.log("Response:", data);

            if (data.success == 1) {
                initAlert("Resident details saved successfully!", 1);

                // ✅ Refresh DataTable
                if (typeof residentslist !== 'undefined' && residentslist.ajax) {
                    residentslist.ajax.reload(null, false);
                }

                // ✅ Don’t reset the form after update
                if (!user_id) {
                    $('#createresidents')[0].reset();
                    $('#user_id').val('');
                }
            } else if (data.error) {
                initAlert(data.error, 0);
            } else {
                initAlert("Can't save details at the moment. Please contact support!", 3);
            }
        },

        error: function (xhr, status, error) {
            console.error("Error saving user:", xhr.responseText || error);
            initAlert("Server error: " + (xhr.responseText || error), 3);
        }
    });
});





    // --- Close form with ESC key ---
    $('body').keydown(function(e) {
        if (e.keyCode == 27) {
            $('.right-slider').slideUp();
        }
    });

    $("body").keydown(function(e) {
        var AcckeyCode = e.keyCode || e.which;
        if (AcckeyCode == 27) {
            $('.right-slider').slideUp();
        }
    });

});


//Bala***/

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

const video = document.getElementById('cameraPreview');
const canvas = document.getElementById('capturedCanvas');
const capturedImageInput = document.getElementById('captured_image');
const uploadInput = document.getElementById('manual_photo');
const uploadPreview = document.getElementById('uploadPreview');
const captureButton = document.getElementById('capturePhoto');
const deleteButton = document.getElementById('deletePhoto');

let stream;

$('#openCamera').click(async function() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: true
        });
        video.srcObject = stream;
        $('#cameraPreview').show();
        $('#capturePhoto').show();
        $('#deletePhoto').hide();
        $('#uploadPreview').hide();
    } catch (error) {
        alert("Unable to access the camera. Please check permissions.");
        console.error(error);
    }
});

$('#capturePhoto').click(function() {
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    const imageData = canvas.toDataURL('image/png');
    capturedImageInput.value = imageData;

    // Stop the camera stream
    stream.getTracks().forEach(track => track.stop());

    // Show captured image
    $('#cameraPreview').hide();
    $('#capturePhoto').hide();
    $('#deletePhoto').show();
    $('#capturedCanvas').show();
    $('#capturedCanvas')[0].getContext('2d').drawImage(video, 0, 0);
    uploadInput.value = ''; // clear file upload
});

$('#deletePhoto').click(function() {
    $('#capturedCanvas').hide();
    $('#deletePhoto').hide();
    $('#captured_image').val('');
    $('#uploadPreview').hide();
});
</script>