<script>
var acctypelist;
function deleteAccType(acc_id) {
	var url = baseUrl + "accounts/deleteAccType/"+acc_id;
	initDelConfirm("Your data will be lost. Are you sure you want to delete the account type?", 2, url, acctypelist, '');
}
function statcheck(el) {
	var acc_status = ($(el).is(':checked') ? $(el).val() : 2);
	var acc_id = $(el).attr('data-id');
	var url = baseUrl + "accounts/changeStatus";
	$.post(url, {acc_status, acc_id}, function(data){
		initAlert("Status changed successfully.", 1);
	},'json');
}
function loadcategory(fsid) {
	$('.fs').hide();
	$('.fs-'+fsid).show();
}
$(document).ready(function(){
	$('.fs').hide();
	acctypelist = $('#acctypelist').DataTable({
		'paging': true,
		'sort': true,
		'searching': true,
		'processing': true,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': {
			'url': baseUrl + "accounts/listacctype"
		},
		'columns': [
			{ data: 'slno' },
			{ data: 'acctypename' },
			{ data: 'fin_statement' },
			{ data: 'statement_category' },
			{ data: 'utype' },
			{ data: 'status' },
			{ data: 'action' }
		]
	});
	
	$('#acctype-btn').click(function(){
		var url = baseUrl + "accounts/createnew";
		$.post(url, $('#createacctype').serialize(), function(data){
			$('#createacctype')[0].reset();
			if(data.status==1) {
				initAlert(data.respmsg, 1);
				acctypelist.ajax.reload();
			}
			else {
				initAlert(data.respmsg, 0);
				$('#createacctype')[0].reset();
			}
		},'json');
	});
});
</script>