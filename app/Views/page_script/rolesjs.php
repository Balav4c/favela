<script>
var roleslist;
function deleteRoles(rid) {
	var url = baseUrl + "roles/deleteRoles/"+rid;
	initDelConfirm("Your data will be lost. Are you sure you want to delete the role?", 2, url, roleslist, '');
}
function statcheck(el) {
	var rl_status = ($(el).is(':checked') ? $(el).val() : 2);
	var rl_id = $(el).attr('data-id');
	var url = baseUrl + "roles/changeStatus";
	$.post(url, {rl_status, rl_id}, function(data){
		initAlert("Status changed successfully.", 1);
	},'json');
}
$(document).ready(function(){
	roleslist = $('#roleslist').DataTable({
		'paging': true,
		'sort': true,
		'searching': true,
		'processing': true,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': {
			'url': baseUrl + "roles/listroles"
		},
		'columns': [
			{ data: 'slno' },
			{ data: 'rolename' },
			{ data: 'status' },
			{ data: 'action' }
		]
	});
	
	$('#roles-btn').click(function(){
		var url = baseUrl + "roles/createnew";
		$.post(url, $('#createroles').serialize(), function(data){
			$('#createroles')[0].reset();
			if(data.status==1) {
				initAlert(data.respmsg, 1);
				roleslist.ajax.reload();
			}
			else {
				initAlert(data.respmsg, 0);
				$('#createroles')[0].reset();
			}
		},'json');
	});
});
</script>