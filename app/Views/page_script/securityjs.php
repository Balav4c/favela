<script>
var securitylist = '';
function deleteSecurity (fs_id) {
	var url = baseUrl + "securities/deleteSecurity/"+fs_id;
	initDelConfirm("Your data will be lost. Are you sure you want to delete the security?", 2, url, securitylist, '');
}
function popupfeedback(sc_id) {
	
	$('.feedback-list').empty();
	var url = baseUrl + "securities/listfeedbacks";
	$.post(url, {sc_id}, function(data){
		if(data) {
			if(data.feedbacklist.length>0) {
				for(var i=0;i<data.feedbacklist.length;i++) {
					var stars='';
					for(var j=0;j<data.feedbacklist[i].star_rating;j++) {
						stars +='<i class="fa fa-star-o"></i>';
					}
					
					$('.feedback-list').append('<div class="col-md-12 feedback-item">\
													<label class="f-name" for="feedback">'+data.feedbacklist[i].fname+'</label>\
													<div class="star-rates-list">'+stars+'</div>\
													<div class="f-feedback">\
														<p>'+data.feedbacklist[i].feedback+'</p>\
													</div>\
												</div>');
				}
			}
			else {
				$('.feedback-list').append('<div class="col-md-12 feedback-item">\
											<label class="f-name" for="feedback">Favela Admin</label>\
											<p>Be the first one to post the feedback.</p>\
										</div>');
			}
		}
	},'json');
	$('.star-rates').find('.fa-star').css('color','#ddd');
	$('#star_ratings').val(0);
	$('#feedback').val('');
	$('#securityfeedback-block').show();
	$('#securitylist-block').hide();
	
	$('.star-rates').find('.fa-star').on('click', function(){
		$('.star-rates').find('.fa-star').css('color','#ddd');
		var snum = $(this).attr('data-val');
		for(var i=0;i<=snum;i++) {
			$('#'+i).css('color','#0066b3');
		}
		$('#star_ratings').val(snum);
	});
}
$(document).ready(function(){
	if($('#security_id').val()!=0) {
		$('.security-search-block').hide();
		$('.security-block').show();
	}
	securitylist = $('#securitylist').DataTable({
		'paging': true,
		'sort': true,
		'searching': true,
		'processing': true,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': {
			'url': baseUrl + "securities/listsecurities"
		},
		'columns': [
			{ data: 'slno' },
			{ data: 'security_name' },
			{ data: 'security_phone' },
			{ data: 'proof' },
			{ data: 'proof_no' },
			{ data: 'security_company' },
			{ data: 'action' }
		]
	});
	
	$('#security-btn').click(function(){
		var url = baseUrl + "securities/createnew";
		$.post(url, $('#securityform').serialize(), function(data){
			$('#securityform')[0].reset();
			if(data.status==1) {
				if($('#editmode').val()==0) {
					$('#securityform')[0].reset();
					$('.security-search-block').show();
					$('.security-block').hide();
				}
				initAlert(data.respmsg, 1);
				securitylist.ajax.reload();
			}
			else {
				initAlert(data.respmsg, data.status);
				$('#securityform')[0].reset();
				$('.security-search-block').show();
				$('.security-block').hide();
			}
		},'json');
	});
	
	$('#security-search-btn').click(function(){
		var id_no = $('#id_card_no').val();
		var url = baseUrl + "securities/searchsecurity";
		$.post(url, {id_no}, function(data){
			if(data.status==1) {
				$('#security_name').val(data.securitylist.security_name);
				$('#security_phone').val(data.securitylist.security_phone);
				$('#security_cmp_name').val(data.securitylist.security_company);
				$('#security_address').val(data.securitylist.security_company_address);
				$('#security_id').val(data.securitylist.sc_id);
				$('.security-block').find('input').attr('readonly',true);
				$('.security-block').find('textarea').attr('readonly',true);
			}
			$('.security-search-block').hide();
			$('.security-block').show();
		},'json');
	});
	
	$("body").keydown(function(e){ 
		var EsckeyCode = e.keyCode || e.which;
		if(EsckeyCode==27){
			$('#securityfeedback-block').hide();
			$('#securitylist-block').show();
		}
	});
});
</script>