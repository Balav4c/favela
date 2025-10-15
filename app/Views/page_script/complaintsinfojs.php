<script>
var complaintsinfolist;

function deleteComplaints(cm_id) {
    var url = baseUrl + "complaintsinfo/deleteComplaint/" + cm_id;
    initDelConfirm("Your data will be lost. Are you sure you want to delete the complaint?", 2, url, complaintsinfolist,
        '');
}

function statcheck(el) {
    var rl_status = ($(el).is(':checked') ? $(el).val() : 2);
    var rl_id = $(el).attr('data-id');
    var url = baseUrl + "complaintsinfo/changeStatus";
    $.post(url, {
        rl_status,
        rl_id
    }, function(data) {
        initAlert("Read status changed successfully.", 1);
        complaintsinfolist.ajax.reload();
    }, 'json');
}
$(document).ready(function() {
    complaintsinfolist = $('#complaintsinfolist').DataTable({
        'paging': true,
        'sort': true,
        'searching': true,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': baseUrl + "complaintsinfo/loadComplaints"
        },
        'columns': [{
                data: 'slno'
            },
            {
                data: 'subject'
            },
            {
                data: 'content'
            },
            {
                data: 'posted_on'
            },
            {
                data: 'status'
            },
            {
                data: 'action'
            }
        ]
    });

    $("body").keydown(function(e) {
        var AcckeyCode = e.keyCode || e.which;
        if (AcckeyCode == 27) {
            $('.right-slider').slideUp();
        }
    });

    $('#residence').keyup(function(e) {

        var keyCode = e.keyCode || e.which;
        if (keyCode == 13) {
            var uid = $('#res-auto').find('.no-0').attr('data-id');
            var uname = $('#res-auto').find('.no-0').html();
            $('#residence').val(uname);
            $('#residence_hd').val(uid);
            $('#res-auto').empty();
            $('#res-auto').hide();
        } else {
            $('#res-auto').empty();
            var url = baseUrl + "complaintsinfo/getResidents";
            var searchkey = $(this).val();
            $.post(url, {
                searchkey
            }, function(response) {
                if (response.status == 1) {
                    $('#res-auto').show();
                    $('#res-auto').empty();
                    for (var i = 0; i < response.resdata.length; i++) {
                        $('#res-auto').append('<div class="col-md-12 no-' + i + '" data-id="' +
                            response.resdata[i].uid + '">' + response.resdata[i].name +
                            '</div>');
                    }
                    $('#res-auto').find('.col-md-12').click(function() {
                        var uid = $(this).attr('data-id');
                        var uname = $(this).html();
                        $('#residence').val(uname);
                        $('#residence_hd').val(uid);
                        $('#res-auto').empty();
                        $('#res-auto').hide();
                    });
                } else {
                    $('#res-auto').empty();
                    $('#res-auto').hide();
                }
            }, 'json');
        }
    });

    $('#cmp-btn').click(function() {

        var url = baseUrl + "complaintsinfo/saveComplaint";
        $.post(url, $('#complaints').serialize(), function(data) {
            if (data.status == 1) {
                initAlert(data.respmsg, 1);
                complaintsinfolist.ajax.reload();
                if ($('#cmp_id').val() != 0) {
                    //undefined.
                } else {
                    $('#complaints')[0].reset();
                }
            } else {
                initAlert(data.respmsg, 0);
                $('#complaints')[0].reset();
            }
        }, 'json');
    });

});

/***Bala script Start */

/**Reply function **/

function replyComplaints(cm_id, element) {
    $(".reply-message-box").remove();

    var btnContainer = $(element).closest('.complaints-reply');
    if (btnContainer.next('.reply-message-box').length) {
        btnContainer.next('.reply-message-box').remove();
        return;
    }
    var replyBox = `
    <div class="reply-message-box">
        <input type="text" class="reply-message"name="reply" placeholder="Type your message..."  />
        <button onclick="sendReply(${cm_id}, this)" class="reply-button">
            <a class="sm-abtn-send">Send</a>
            </button>
			  <button onclick="closeReplyBox(this)" class="reply-button">
            <a class="sm-abtn-cancel">Cancel</a>
        </button>
    </div>
`;
    btnContainer.after(replyBox);
}

function sendReply(cm_id, element) {
    var base_url = "<?= base_url(); ?>";
    var message = $(element).closest('.reply-message-box').find('input[name="reply"]').val().trim();

    if (message === '') {
        initAlert('Please Enter a Message!', 0);
        return;
    }

    $.ajax({
        url: base_url + "complaintsinfo/saveReply",
        type: "POST",
        data: {
            cm_id: cm_id,
            reply: message,
        },
        dataType: "json",
        success: function(response) {
            if (response.status === 1) {
                initAlert(response.respmsg, 1);
                complaintsinfolist.ajax.reload();
                $("#exampleModalCenter").modal("hide");
                $(element).closest('.reply-message-box').remove();
                var pendingBtn = $(element).closest('.col-md-12').find('.sm-abtn-pending');
                if (pendingBtn.length) {
                    pendingBtn.removeClass('sm-abtn-pending').addClass('sm-abtn-replied').text('Replied');

                }
            } else {
                initAlert(response.respmsg, 0);
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            initAlert('Something went wrong. Please try again.', 0);
        }

    });
}

function closeReplyBox(element) {
    $(element).closest('.reply-message-box').remove();
}

//View Details


function viewDetails(cm_id) {
    var baseurl = $("#exampleModalCenter").data("base-url") + cm_id;

    $.ajax({
        url: baseurl,
        type: "GET",
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                let complaint = response.content;
                let repliesHtml = "";
                let loggedInUser = response.logged_in_user;
                if (response.replies.length > 0) {
                    response.replies.forEach(function(reply, index) {
                        let displayUsername = loggedInUser ? "You" : reply
                         
                    // Format the date and time (D/M/YYYY H:i A)
                        let originalDate = new Date(reply.reply_date);
                        let day = originalDate.getDate();
                        let month = originalDate.getMonth() + 1; // Months are zero-based
                        let year = originalDate.getFullYear();
                        let hours = originalDate.getHours();
                        let minutes = originalDate.getMinutes().toString().padStart(2, "0"); // Ensure two-digit minutes
                        let ampm = hours >= 12 ? "PM" : "AM";
                        hours = hours % 12 || 12; // Convert to 12-hour format

                        let formattedDateTime = `${day}-${month}-${year} ${hours}:${minutes} ${ampm}`;

                        repliesHtml += `<p>
                            <strong>${displayUsername}:</strong> ${reply.reply} <br>
                            <small><i>${formattedDateTime}</i></small>
                        </p>`;
                        // Add a horizontal line after each reply except the last one
                        if (index !== response.replies.length - 1) {
                            repliesHtml += `<hr>`;
                        }
                    });
                } else {
                    repliesHtml = "<p>No replies yet.</p>";
                }
                repliesHtml += `<a href="javascript:void(0);" class="sm-abtn-reply complaints-reply" 
                        onclick="replyComplaints(${cm_id}, this)">Reply</a>`;
                $("#complaintContent").html(complaint);
                $("#complaintReply").html(repliesHtml);
                $("#exampleModalCenter").modal("show");
            } else {
                alert("No complaint details found.");
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            alert("Failed to fetch complaint details.");
        }
    });
}


//******Bala Script's End *********/
</script>