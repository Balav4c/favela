<script>
var announceList;

function deleteAnnouncement(aid) {
    var url = baseUrl + "announcements/deleteAnnouncement/" + aid;
    initDelConfirm("Your data will be lost. Are you sure you want to delete the announcement?", 2, url, announceList,
        '');
}

function statcheck(el) {
    var status = ($(el).is(':checked') ? $(el).val() : 2);
    var anoc_id = $(el).attr('data-id');
    var url = baseUrl + "announcements/changeStatus";
    $.post(url, {
        status,
        anoc_id
    }, function(data) {
        initAlert("Status changed successfully.", 1);
           $('#announcementlist').DataTable().ajax.reload(null, false);
    }, 'json');
}
$(document).ready(function() {
    announceList = $('#announcementlist').DataTable({
        'paging': true,
        'sort': true,
        'searching': true,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': baseUrl + "announcements/listannouncement"
        },
        'columns': [{
                data: 'slno'
            },
            {
                data: 'subject'
            },
            {
                data: 'announcements'
            },
            {
                data: 'announce_date'
            },
            {
                data: 'expiry_date'
            },
            {
                data: 'status_text'
            }, // ✅ was announce_status
            {
                data: 'status_toggle'
            }, // ✅ was status
            {
                data: 'action'
            }
        ]
    });

    // Converts DD-MM-YYYY → YYYY-MM-DD


  $('#anoc-btn').click(function() {
    var announcement = $('#announcements').val().trim();
    var publishDate = $('#announce_date').val().trim();
    var endDate = $('#expiry_date').val().trim();

    if (announcement === '') {
        initAlert("Please enter Announcement.", 0);
        $('#announcements').focus();
        return false;
    }

    if (publishDate === '') {
        initAlert("Please select Publish Date.", 0);
        $('#announce_date').focus();
        return false;
    }

    if (endDate === '') {
        initAlert("Please select End Date.", 0);
        $('#expiry_date').focus();
        return false;
    }

    // Check End Date >= Publish Date
    var pubDate = new Date(publishDate.split('-').reverse().join('-'));
    var expDate = new Date(endDate.split('-').reverse().join('-'));
    if (expDate < pubDate) {
        initAlert("End Date cannot be before Publish Date.", 0);
        $('#expiry_date').focus();
        return false;
    }

    // Submit form via AJAX
    var url = baseUrl + "announcements/createnew";
    $.post(url, $('#createAnnouncement').serialize(), function(data) {
        if (data.status == 1) {
            initAlert(data.respmsg, 1);
            
            // Only reset the form if creating a new announcement
            var anoc_id = $('input[name="anoc_id"]').val();
            if (!anoc_id || anoc_id == 0) {
                $('#createAnnouncement')[0].reset();
                // Also reset Flatpickr values
                publishPicker.clear();
                endPicker.clear();
            }

            // Reload DataTable
            announceList.ajax.reload();
        } else {
            initAlert(data.respmsg, 0);
        }
    }, 'json');
});



});


//Readmore
$(document).on('click', '.read-more', function() {
    var container = $(this).closest('.announcement-text');
    var fullText = container.data('full');
    container.html(fullText + ' <a href="javascript:void(0);" class="read-less">Read Less</a>');
});

$(document).on('click', '.read-less', function() {
    var container = $(this).closest('.announcement-text');
    var fullText = container.data('full');
    var preview = fullText.substring(0, 100);
    container.html(preview + '... <a href="javascript:void(0);" class="read-more">Read More</a>');
});

document.addEventListener("DOMContentLoaded", function() {
    // Publish date picker
    window.publishPicker = flatpickr("#announce_date", {
        dateFormat: "d-m-Y",
        altInput: true,
        altFormat: "d-m-Y",
        allowInput: true,
        minDate: "today",
        defaultDate: document.querySelector('#announce_date').value, // prefilled value
        onChange: function(selectedDates, dateStr, instance) {
            if(selectedDates.length > 0) {
                endPicker.set('minDate', selectedDates[0]);
            }
        }
    });

    // End date picker
    window.endPicker = flatpickr("#expiry_date", {
        dateFormat: "d-m-Y",
        altInput: true,
        altFormat: "d-m-Y",
        allowInput: true,
        minDate: "today",
        defaultDate: document.querySelector('#expiry_date').value // prefilled value
    });
});


</script>