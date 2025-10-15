<head>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<div class="modal fade" id="exampleModalCenter" data-base-url="<?= base_url('viewdetails/') ?>" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <hr class="viewhr">
            <div class="modal-header">
                <div class="complaint-head-view">
                    <i class="fa-solid fa-circle-info icon-info"></i>
                    <h5 class="modal-title modal-head">Complaint Details</h5>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Complaint:</strong> <span id="complaintContent">Loading...</span></p>
                <p><strong>Reply:</strong> <span id="complaintReply">Loading...</span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>