<script>
var payrqlist;
function deletePayrequest(pr_id) {
	if(pr_id) {
		var url = baseUrl + "paymentrequest/deletePayrequest/"+pr_id;
		initDelConfirm("Your data will be lost. Are you sure you want to delete the request?", 2, url, payrqlist, '');
	}
}
function statcheck(el) {
	var pay_status = ($(el).is(':checked') ? $(el).val() : 2);
	var pr_id = $(el).attr('data-id');
	var url = baseUrl + "paymentrequest/changeStatus";
	$.post(url, {pay_status, pr_id}, function(data){
		initAlert("Status changed successfully.", 1);
	},'json');
}
function enableResident(val) {
	if(val==2) {
		$('#residence').attr('disabled',false);
	}
	else {
		$('#residence').val('');
		$('#residence_hd').val('');
		$('#residence').attr('disabled',true);
	}
}
$(document).ready(function(){
	payrqlist = $('#payrqlist').DataTable({
		'paging': true,
		'sort': true,
		'searching': true,
		'processing': true,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': {
			'url': baseUrl + "paymentrequest/listpayrequest"
		},
		'columns': [
			{ data: 'slno' },
			{ data: 'payment_type' },
			{ data: 'payment_notes' },
			{ data: 'amount' },
			{ data: 'request_to' },
			{ data: 'user_app' },
			{ data: 'created_on' },
			{ data: 'status' },
			{ data: 'action' }
		]
	});
	
	$('#payrequest-btn').click(function(){
		
		var url = baseUrl + "paymentrequest/savepayrequest";
		$.post(url, $('#createpayrequest').serialize(), function(data){
			if(data){
				if(data.status==1) {
					initAlert(data.respmsg, 1);
					//$('#createpayrequest')[0].reset();
					payrqlist.ajax.reload();
				}
				else {
					initAlert(data.respmsg, 0);
					$('#createpayrequest')[0].reset();
				}
			}
		}, 'json');
		
	});
	
	$('#residence').keyup(function(e){
		
		var keyCode = e.keyCode || e.which;
		if(keyCode==13) {
			var uid = $('#res-auto').find('.no-0').attr('data-id');
			var uname = $('#res-auto').find('.no-0').html();
			$('#residence').val(uname);
			$('#residence_hd').val(uid);
			$('#res-auto').empty();
			$('#res-auto').hide();
		}
		else {
			$('#res-auto').empty();
			var url = baseUrl + "paymentrequest/getResidents";
			var searchkey = $(this).val();
			$.post(url, {searchkey}, function(response){
				if(response.status==1) {
					$('#res-auto').show();
					$('#res-auto').empty();
					for(var i=0;i<response.resdata.length;i++) {
						$('#res-auto').append('<div class="col-md-12 no-'+i+'" data-id="'+response.resdata[i].uid+'">'+response.resdata[i].name+'</div>');
					}
					$('#res-auto').find('.col-md-12').click(function(){
						var uid = $(this).attr('data-id');
						var uname = $(this).html();
						$('#residence').val(uname);
						$('#residence_hd').val(uid);
						$('#res-auto').empty();
						$('#res-auto').hide();
					});
				}
				else {
					$('#res-auto').empty();
					$('#res-auto').hide();
				}
			},'json');
		}
	});
});
</script>