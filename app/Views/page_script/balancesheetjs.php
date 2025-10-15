<script>
var roleslist;
function deleteRoles(rid) {
	var url = baseUrl + "roles/deleteRoles/"+rid;
	initDelConfirm("Your data will be lost. Are you sure you want to delete the role?", 2, url, roleslist, '');
}
function loadBalanceSheet() {
	var url = baseUrl + "balancesheet/loadBalanceSheet";
	var fyear = $('#finyear').val();
	$.post(url, {fyear}, function(data){
		if(data) {
			var arrayLimit = (data.asset_arr.length >= data.liable_arr.length ? data.asset_arr.length : data.liable_arr.length);
			$('.bs-list').empty();
			for(var i=0;i<arrayLimit;i++) {
				if(data.liable_arr[i]) {
					$('.bs-list').append('<div class="col-md-4 bd-lt bd-rt bd-bt">'+data.liable_arr[i].acctype+'</div>\
									<div class="col-md-2 text-end bd-rt bd-bt"><i class="fa fa-rupee"></i>&nbsp;'+data.liable_arr[i].subheadtotal+'</div>');
				}
				else {
					$('.bs-list').append('<div class="col-md-4 bd-lt bd-rt bd-bt">&nbsp;</div>\
									<div class="col-md-2 text-end bd-rt bd-bt">&nbsp;</div>');
				}
				
				if(data.asset_arr[i]) { 
					$('.bs-list').append('<div class="col-md-4 bd-rt bd-bt">'+data.asset_arr[i].acctype+'</div>\
									<div class="col-md-2 text-end bd-rt bd-bt"><i class="fa fa-rupee"></i>&nbsp;'+data.asset_arr[i].subheadtotal+'</div>');
				}
				else {
					$('.bs-list').append('<div class="col-md-4 bd-lt bd-rt bd-bt">&nbsp;</div>\
									<div class="col-md-2 text-end bd-rt bd-bt">&nbsp;</div>');
				}
			}
		}
	},'json');
}
$(document).ready(function(){
	loadBalanceSheet();
});
</script>