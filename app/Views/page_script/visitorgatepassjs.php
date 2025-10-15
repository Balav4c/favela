<script src="<?php echo base_url('public/js/html2canvas.js'); ?>"></script>
<script>
var gatepasslist = '';
function deletePass(gp_id) {
	var url = baseUrl + "visitorgatepass/deletePass/"+gp_id;
	initDelConfirm("Your data will be lost. Are you sure you want to delete the gatepass?", 2, url, gatepasslist, '');
	$('#gp-view-block').hide();
	$('#gp-form-block').show();
	$('#gatepassform')[0].reset();
}
function popupDetails(gp_id) {
	
	var url = baseUrl + "visitorgatepass/getPassDetails";
	$.post(url, {gp_id}, function(data){
		if(data.status==1) {
			var gatepassPath = baseUrl + "gatepass/"+ data.gatepass.token+".jpg";
			
			var whatsapp_msg = "Hello "+data.gatepass.vname+", This is your gatepass for the entry. \
								Open the below link to access the gatepass. Once you are at the entrance, scan \
								the QR with security. Your entry will be permitted. Thank you. ";
				
			var shareurl = whatsapp_msg + baseUrl + "mygatepass/" + data.gatepass.token;
			$('#gatepasslist-block').hide();
			$('#gatepassDetails-block').show();
			$('.gatepassimg').attr('src',gatepassPath);
			$('#v-visitor').html(data.gatepass.vname);
			$('#v-place').html(data.gatepass.vplace);
			$('#v-phone').html(data.gatepass.vphone);
			$('#v-date').html(data.gatepass.vdate);
			$('#v-purpose').html(data.gatepass.vpurpose);
			$('#v-person').html(data.gatepass.vperson);
			$('#v-checkin').html((data.gatepass.vcheckin ? data.gatepass.vcheckin : '-NA-'));
			$('#v-checkout').html((data.gatepass.vcheckout ? data.gatepass.vcheckout : '-NA-'));
			$('#v-security').html((data.gatepass.vsecurity ? data.gatepass.vsecurity : '-NA-'));
			$('#v-status').html(data.gatepass.status);
			$('#v-created').html(data.gatepass.vcreated);
			$('#v-createdon').html(data.gatepass.vcreatedon);
			$('#sharewhatsapp-view').attr("href","https://api.whatsapp.com/send?text="+shareurl+"&"+data.gatepass.vphone);
		}
		else {
			initAlert(data.respmsg, 0);
		}
	}, 'json');
}
$(document).ready(function(){
	
	gatepasslist = $('#gatepasslist').DataTable({
		'paging': true,
		'sort': true,
		'searching': true,
		'processing': true,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': {
			'url': baseUrl + "visitorgatepass/listgatepass"
		},
		'columns': [
			{ data: 'slno' },
			{ data: 'visitor' },
			{ data: 'datevisit' },
			{ data: 'checkIn' },
			{ data: 'checkOut' },
			{ data: 'action' }
		]
	});
	
	$('#gp-btn').click(function(){
		$(this).html('Creating Gatepass... Please wait!');
		var url = baseUrl + "visitorgatepass/createnew";
		$.post(url, $('#gatepassform').serialize(), function(data){
			$('#gatepassform')[0].reset();
			if(data.status==1) {
				initAlert(data.respmsg, 1);
				gatepasslist.ajax.reload();
				$('#gp-view-block').show();
				$('#gp-form-block').hide();
				$('.gp-vname').html('<h4><b>'+data.gpdata.visitor_name+'</b></h4>');
				$('.gp-vdate').html('Visiting on: ' + data.gpdata.date_of_visit);
				$('.qrimg').attr('src', baseUrl + data.qr);
				
				var whatsapp_msg = "Hello "+data.gpdata.visitor_name+", This is your gatepass for the entry. \
								Open the below link to access the gatepass. Once you are at the entrance, scan \
								the QR with security. Your entry will be permitted. Thank you. ";
								
				var imgurl = baseUrl + "visitorgatepass/savepassimg";
				var shareurl = whatsapp_msg + baseUrl + "mygatepass/"+data.gpdata.token;
				html2canvas(document.getElementById("gp-card")).then(function(canvas){
					var ajax = new XMLHttpRequest();
					ajax.open("POST", imgurl, true);
					ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
					ajax.send("qr="+data.gpdata.token+"&image="+canvas.toDataURL("image/jpeg",0.9));
					ajax.onreadystatechange = function(){
						if(this.readyState == 4 && this.status == 200) {
							$('#gp-btn').html('Create');
							$('#done-gp').click(function(){
								$('#gp-view-block').hide();
								$('#gp-form-block').show();
								$('#gatepassform')[0].reset();
							});
							var phone = $('#phone').val();
							$('#sharewhatsapp').attr("href","https://api.whatsapp.com/send?text="+shareurl+"&"+phone);
						}
					}
				});
			}
			else {
				initAlert(data.respmsg, 0);
				$('#gatepassform')[0].reset();
			}
		},'json');
	});
	$("body").keydown(function(e){ 
		var EsckeyCode = e.keyCode || e.which;
		if(EsckeyCode==27){
			$('#gatepasslist-block').show();
			$('#gatepassDetails-block').hide();
		}
	});
});
</script>