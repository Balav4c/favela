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

    $('#anoc-btn').click(function() {
        var url = baseUrl + "announcements/createnew";
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

	
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',  
		autoclose: true,
        todayHighlight: true
    });





});
</script>