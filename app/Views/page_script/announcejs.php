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
                data: 'announce_status'
            },
            {
                data: 'status'
            },
            {
                data: 'action'
            }
        ]
    });

    // Converts DD-MM-YYYY → YYYY-MM-DD
    function convertDateFormat(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('-'); // split by dash
        return parts.length === 3 ? `${parts[2]}-${parts[1]}-${parts[0]}` : dateStr;
    }

    $('#anoc-btn').click(function() {
        var url = baseUrl + "announcements/createnew";

        // Get dates from inputs
        var announce_date = $('#announce_date').val();
        var expiry_date = $('#expiry_date').val();

        // Convert to MySQL format (YYYY-MM-DD)
        $('#announce_date').val(convertDateFormat(announce_date));

        // Only convert expiry_date if not empty
        if (expiry_date) {
            $('#expiry_date').val(convertDateFormat(expiry_date));
        }

        $.post(url, $('#createAnnouncement').serialize(), function(data) {
            if (data.status == 1) {
                initAlert(data.respmsg, 1);
                $('#createAnnouncement')[0].reset();
                announceList.ajax.reload();
            } else {
                initAlert(data.respmsg, 0);
            }
        }, 'json');
    });




    $(document).on('click', '.toggle-status', function() {
        var id = $(this).data('id');
        var newStatus = $(this).data('status');

        $.post(baseUrl + "announcements/toggleStatus", {
            id: id,
            status: newStatus
        }, function(res) {
            var data = JSON.parse(res);
            if (data.success) {
                $('#announcementlist').DataTable().ajax.reload(null, false); // refresh table
            } else {
                alert('Failed to update status');
            }
        });
    });


    // $('.datepicker').datepicker({
    //     format: 'dd-mm-yyyy',
    //     autoclose: true,
    //     todayHighlight: true
    // });

    $('#expiry_date').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
        startDate: new Date() // sets min selectable date to today
    });

    // If you want end date strictly after today:
    $('#expiry_date').datepicker('setStartDate', '+1d'); // tomorrow

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





});





</script>