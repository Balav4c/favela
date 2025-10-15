<script>
var flatlist;
function deleteFlat(fid) {
	var url = baseUrl + "flats/deleteFlat/"+fid;
	initDelConfirm("Your data will be lost. Are you sure you want to delete the flat/tower?", 2, url, flatlist, '');
}
function statcheck(el) {
	var bd_status = ($(el).is(':checked') ? $(el).val() : 2);
	var bd_id = $(el).attr('data-id');
	var url = baseUrl + "flats/changeStatus";
	$.post(url, {bd_status, bd_id}, function(data){
		initAlert("Status changed successfully.", 1);
	},'json');
}
$(document).ready(function(){
	flatlist = $('#flatslist').DataTable({
		'paging': true,
		'sort': true,
		'searching': true,
		'processing': true,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': {
			'url': baseUrl + "flats/listflats"
		},
		'columns': [
			{ data: 'slno' },
			{ data: 'building' },
			{ data: 'flatnos' },
			{ data: 'recepno' },
			{ data: 'status' },
			{ data: 'action' }
		]
	});
	
	$('#flt-btn').click(function(){
		var url = baseUrl + "flats/createnew";
		$.post(url, $('#createflat').serialize(), function(data){
			$('#createflat')[0].reset();
			if(data.status==1) {
				initAlert(data.respmsg, 1);
				flatlist.ajax.reload();
			}
			else {
				initAlert(data.respmsg, 0);
				$('#createflat')[0].reset();
			}
		},'json');
	});
});
</script>