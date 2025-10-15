<script>
var __accid = 0;
function initOpenAcc(){
	$('.sub-heads').click(function(e){
		e.stopImmediatePropagation();
		e.preventDefault();
		var sh_id = $(this).attr("data-shead");
		$(this).find('.ledger-list').toggle();
		$(this).toggleClass('sub-heads-active');
		$(this).find('.fa').toggleClass('fa-plus fa-minus');
		var url = baseUrl + "chartofaccounts/loadLedgers";
		$.post(url, {sh_id}, function(data){
			if(data) {
				if(data.success==1) {
					$('#shead-ldg-'+data.sh_id).empty();
					if(data.ledgerlist.length > 0) {
						for(var i=0;i<data.ledgerlist.length;i++) {
							$('#shead-ldg-'+data.sh_id).append('<div class="col-md-12">'+data.ledgerlist[i].ledger_name+'</div>');
						}
					}
					else {
						$('#shead-ldg-'+data.sh_id).html('<div class="col-md-12">No ledgers found!</div>');
					}
				}
				else {
					$('#shead-ldg-'+data.sh_id).html('<div class="col-md-12">No ledgers found!</div>');
				}
			}
			else {
				$('#shead-ldg-'+data.sh_id).html('<div class="col-md-12">No ledgers found!</div>');
			}
		},'json');
	});
}
function deleteSubHead(sh_Id, el) {
	__accid = $('#acc_id').val();
	var delUrl = baseUrl + "chartofaccounts/deleteSubhead/"+sh_Id;
	initDelConfirm("Are you sure you want to delete the subhead?", 2, delUrl, '', getAllSubhead);
}
function editSubHead(sh_Id, el) {
	if(sh_Id) {
		var subhead = $(el).parent().find('span').html();
		$(el).parent().find('span').html('<input type="text" class="form-control shead_edit_input" value="'+subhead+'"/>');
		$(el).parent().find('.fa-pencil-square-o').hide();
		var editBox;
		$('.shead_edit_input').keyup(function(e){
			editBox = $(this);
			subheadval = editBox.val();
			var keyCode = e.keyCode || e.which;
			//alert(keyCode);
			if(keyCode == 13) {
				var url = baseUrl + "chartofaccounts/updatesubhead";
				$.post(url,{sh_Id, subheadval}, function(response){
					if(response.success == 1) {
						editBox.parent().parent().find('.fa-pencil-square-o').show();
						editBox.parent().parent().find('span').html(response.subhead);
					}
				},'json');
				loadCharts();
			}
		});
	}
}
function getAllSubhead(acc_id) {
	if(__accid!=0) {
		acc_id = __accid;
		__accid = 0;
	}
	var allurl = baseUrl + "chartofaccounts/getallsubheads";
	$.post(allurl, {acc_id}, function(response){
		if(response) {
			$('.subheadlist').empty();
			for(var i=0;i<response.length;i++) {
				if(response[i].sub_headname) {
					if(response[i].ledgernos > 0) {
						var faicon = '<i class="fa fa-pencil-square-o" onclick="editSubHead('+response[i].sh_id+', this)"></i>';
					}
					else {
						var faicon = '<i class="fa fa-trash" onclick="deleteSubHead('+response[i].sh_id+', this)"></i>';
					}
					$('.subheadlist').append('<div class="shead-item"><span>' + response[i].sub_headname + '</span>' + faicon + '</div>');
				}
			}
		}
	},'json');
	loadCharts();
}
function addOnThis(acc_id, el) {
	$('#acc_id').val(acc_id);
	if(acc_id) {
		$('.subheadlist').empty();
		$('#subheadtitle').html($(el).html());
		$('.subhead-block').slideDown();
		$('#create-subhead')[0].focus();
		
		getAllSubhead(acc_id);
		
		$('#create-subhead').keydown(function(e){
			var keyCode = e.keyCode || e.which;
			if(keyCode == 13 && $(this).val()!='') {
				e.stopImmediatePropagation();
				e.preventDefault();
				var subhead = $(this).val();
				var url = baseUrl + "chartofaccounts/savesubhead";
				var acc_id = $('#acc_id').val();
				$.post(url, {acc_id , subhead}, function(data){
					if(data.status==1) {
						$('.shead-list'+data.acc_id).append('<div class="col-md-12 sub-heads" data-shead="'+data.subhead_id+'">\
															<div id="shead-'+data.subhead_id+'">'+data.subhead+'<i class="fa fa-plus"></i></div>\
															<div class="col-md-12 ledger-list" id="shead-ldg-'+data.subhead_id+'"></div>\
														</div>');
						var faicon = '<i class="fa fa-trash" onclick="deleteSubHead('+data.subhead_id+', this)"></i>';
						$('.subheadlist').append('<div class="shead-item"><span>'+data.subhead+'</span>' + faicon + '</div>');
						$('#create-subhead').val('');
						initOpenAcc();
					}
				},'json');
				
			}
		});
	}
}

function loadCharts() {
	var url = baseUrl + "chartofaccounts/loadCharts";
	$.post(url, function(response){
		if(response) {
			$('.caccount-list').empty();
			for(var i=0; i<response.length; i++) {
				
				var subheadlist = '';
				if(response[i].subheads.length > 0) {
					for(var j=0;j<response[i].subheads.length;j++) {
						subheadlist += '<div class="col-md-12 sub-heads" data-shead="'+response[i].subheads[j].sh_id+'">\
											<div id="shead-'+response[i].subheads[j].sh_id+'">'+response[i].subheads[j].sub_headname+'<i class="fa fa-plus"></i></div>\
											<div class="col-md-12 ledger-list" id="shead-ldg-'+response[i].subheads[j].sh_id+'"></div>\
										</div>';
					}
				}
				
				$('.caccount-list').append('<div class="row">\
												<div class="col-md-12 acctype-head"><a href="javascript:void(0);" title="Click to add sub head." onclick="addOnThis('+response[i].acc_id+',this)">'+response[i].acc_name+'</a></div>\
												<div class="sheads-block shead-list'+response[i].acc_id+'">\
													'+subheadlist+'\
												</div>\
											</div>')
			}
			initOpenAcc();
		}
	},'json');
}
$(document).ready(function(){
	loadCharts();
});
</script>