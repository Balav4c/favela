<script>
var ledgerlist;
function deleteLedger(ld_id) {
	var url = baseUrl + "ledger/deleteLedger/"+ld_id;
	initDelConfirm("Your data will be lost. Are you sure you want to delete the ledger?", 2, url, ledgerlist, '');
}
function statcheck(el) {
	var ld_status = ($(el).is(':checked') ? $(el).val() : 2);
	var ld_id = $(el).attr('data-id');
	var url = baseUrl + "ledger/changeStatus";
	$.post(url, {ld_status, ld_id}, function(data){
		initAlert("Status changed successfully.", 1);
	},'json');
}
function resetLedger() {
	openLedger($('#ledger_id').val(), $('#ledger_name').val());
}
function openLedger(ledId, ledname) {
	$('.ledname').html(ledname);
	$('#ledger_id').val(ledId);
	$('#ledger_name').val(ledname);
	$('#open-ledger').show();
	$('#manage-ledgers').hide();
	var ledurl = baseUrl + "ledger/openledger"
	var finyears = $('#finyear').val().split("-");
	var fromYear = finyears[0];
	var toYear = finyears[1];
	var DrTotal = 0;
	var CrTotal = 0;
	$('.dr-trns-holder').html('<div class="row ledg-trns"><div class="col-md-12 text-center bd-lt">Loading Data...</div></div>');
	$('.cr-trns-holder').html('<div class="row ledg-trns"><div class="col-md-12 text-center bd-lt bd-rt">Loading Data...</div></div>');
	$.post(ledurl, {ledId, fromYear, toYear}, function(data){
		$('#dr-trns-holder').empty();
		if(data) {
			$('.dr-trns-holder').empty();
			$('.cr-trns-holder').empty();
			var month = 4;
			var cr_balance_cd = 0;
			var dr_balance_cd = 0;
			/*Financial years loop start here*/
			for(var i=4;i<16;i++) {
				
				/*Balance B/D start here*/
				if(cr_balance_cd > 0) {
					var cr_openingDateArr = '&nbsp;';
					var cr_balance_bd_date = '&nbsp;';
					if(data[i].DrData.length > data[i].CrData.length) {
						for(var j=0;j<data[i].DrData.length;j++) {
							cr_openingDateArr = data[i].DrData[j].trn_date.split("/");
							cr_balance_bd_date = '01/'+cr_openingDateArr[1]+'/'+cr_openingDateArr[2];
						}
					}
					else {
						for(var j=0;j<data[i].CrData.length;j++) {
							cr_openingDateArr = data[i].CrData[j].trn_date.split("/");
							cr_balance_bd_date = '01/'+cr_openingDateArr[1]+'/'+cr_openingDateArr[2];
						}
					}
					
					$('.dr-trns-holder').append('<div class="row ledg-trns">\
													<div class="col-md-2 bd-lt bd-rt bd-date'+i+'">&nbsp;</div>\
													<div class="col-md-6 bd-rt">Balance B/D</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end dr-amnt'+i+'" data-dramnt="'+cr_balance_cd+'"><i class="fa fa-rupee"></i>&nbsp;'+cr_balance_cd+'</div>\
												</div>');
					$('.cr-trns-holder').append('<div class="row ledg-trns">\
													<div class="col-md-2 bd-rt">&nbsp;</div>\
													<div class="col-md-6 bd-rt">&nbsp;</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end">&nbsp;</div>\
												</div>');
					$('.bd-date'+i).html(cr_balance_bd_date);
				}
				else if(dr_balance_cd > 0) {
					var dr_openingDateArr = '&nbsp;';
					var dr_balance_bd_date = '&nbsp;';
					if(data[i].DrData.length > data[i].CrData.length) {
						for(var k=0;k<data[i].DrData.length;k++) {
							dr_openingDateArr = data[i].DrData[k].trn_date.split("/");
							dr_balance_bd_date = '01/'+dr_openingDateArr[1]+'/'+dr_openingDateArr[2];
						}
					}
					else {
						for(var k=0;k<data[i].CrData.length;k++) {
							dr_openingDateArr = data[i].CrData[k].trn_date.split("/");
							dr_balance_bd_date = '01/'+dr_openingDateArr[1]+'/'+dr_openingDateArr[2];
						}
					}
					$('.dr-trns-holder').append('<div class="row ledg-trns">\
													<div class="col-md-2 bd-lt bd-rt">&nbsp;</div>\
													<div class="col-md-6 bd-rt">&nbsp;</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end">&nbsp;</div>\
												</div>');
					$('.cr-trns-holder').append('<div class="row ledg-trns">\
													<div class="col-md-2 bd-rt bd-date'+i+'">&nbsp;</div>\
													<div class="col-md-6 bd-rt">Balance B/D</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end cr-amnt'+i+'" data-dramnt="'+dr_balance_cd+'"><i class="fa fa-rupee"></i>&nbsp;'+dr_balance_cd+'</div>\
												</div>');
					$('.bd-date'+i).html(dr_balance_bd_date);
				}
				/*Balance B/D end here*/
				
				/*Monthly transactions start here*/
				if(data[i].DrData.length>0) {
					for(var j=0;j<data[i].DrData.length;j++) {
						$('.dr-trns-holder').append('<div class="row ledg-trns">\
														<div class="col-md-2 bd-lt bd-rt">'+data[i].DrData[j].trn_date+'</div>\
														<div class="col-md-6 bd-rt">'+data[i].DrData[j].narration+'</div>\
														<div class="col-md-1 bd-rt">&nbsp;</div>\
														<div class="col-md-3 bd-rt text-end dr-amnt'+i+'" data-dramnt="'+data[i].DrData[j].amount+'"><i class="fa fa-rupee"></i>&nbsp;'+data[i].DrData[j].amount+'</div>\
													</div>');
						DrTotal = parseFloat(DrTotal) + parseFloat(data[i].DrData[j].amount);
					}
				}
				
				if(data[i].DrData.length < data[i].CrData.length) {
					var balance = data[i].CrData.length - data[i].DrData.length;
					for(var a=0;a<balance;a++) {
						$('.dr-trns-holder').append('<div class="row ledg-trns">\
												<div class="col-md-2 bd-lt bd-rt">&nbsp;</div>\
												<div class="col-md-6 bd-rt">&nbsp;</div>\
												<div class="col-md-1 bd-rt">&nbsp;</div>\
												<div class="col-md-3 bd-rt text-end">&nbsp;</div>\
											</div>');
					}
				}
					
				if(data[i].CrData.length>0) {
					for(var k=0;k<data[i].CrData.length;k++) {
						$('.cr-trns-holder').append('<div class="row ledg-trns">\
													<div class="col-md-2 bd-rt">'+data[i].CrData[k].trn_date+'</div>\
													<div class="col-md-6 bd-rt">'+data[i].CrData[k].narration+'</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end cr-amnt'+i+'" data-cramnt="'+data[i].CrData[k].amount+'"><i class="fa fa-rupee"></i>&nbsp;'+data[i].CrData[k].amount+'</div>\
												</div>');
						CrTotal = parseFloat(CrTotal) + parseFloat(data[i].CrData[k].amount);
					}
				}	
				if(data[i].DrData.length > data[i].CrData.length) {
					var balance = data[i].DrData.length - data[i].CrData.length;
					for(var a=0;a<balance;a++) {
						$('.cr-trns-holder').append('<div class="row ledg-trns">\
												<div class="col-md-2 bd-rt">&nbsp;</div>\
												<div class="col-md-6 bd-rt">&nbsp;</div>\
												<div class="col-md-1 bd-rt">&nbsp;</div>\
												<div class="col-md-3 bd-rt text-end">&nbsp;</div>\
											</div>');
					}
				}
				/*Monthly transactions ends here*/
				
				/*Balance C/D start here*/
				var drMnthTotal = 0;
				var crMnthTotal = 0;
				$('.dr-amnt'+i).each(function(){
					drMnthTotal = drMnthTotal + parseFloat($(this).attr('data-dramnt'));
				});
				$('.cr-amnt'+i).each(function(){
					crMnthTotal = crMnthTotal + parseFloat($(this).attr('data-cramnt'));
				});
				
				cr_balance_cd = 0;
				dr_balance_cd = 0;
				if(drMnthTotal>crMnthTotal) {
					cr_balance_cd = parseFloat(drMnthTotal) - parseFloat(crMnthTotal);
					$('.dr-trns-holder').append('<div class="row ledg-trns">\
													<div class="col-md-2 bd-lt bd-rt">&nbsp;</div>\
													<div class="col-md-6 bd-rt">&nbsp;</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end">&nbsp;</div>\
												</div>');
					$('.cr-trns-holder').append('<div class="row ledg-trns">\
													<div class="col-md-2 bd-rt">&nbsp;</div>\
													<div class="col-md-6 bd-rt">Balance C/D</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end cr-amnt'+i+'" data-cramnt="'+cr_balance_cd+'"><i class="fa fa-rupee"></i>&nbsp;'+cr_balance_cd+'</div>\
												</div>');
				}
				else if(drMnthTotal<crMnthTotal) {
					dr_balance_cd = parseFloat(crMnthTotal) - parseFloat(drMnthTotal);
					$('.dr-trns-holder').append('<div class="row ledg-trns">\
													<div class="col-md-2 bd-lt bd-rt">&nbsp;</div>\
													<div class="col-md-6 bd-rt">Balance C/D</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end dr-amnt'+i+'" data-dramnt="'+dr_balance_cd+'"><i class="fa fa-rupee"></i>&nbsp;'+dr_balance_cd+'</div>\
												</div>');
					$('.cr-trns-holder').append('<div class="row ledg-trns">\
													<div class="col-md-2 bd-rt">&nbsp;</div>\
													<div class="col-md-6 bd-rt">&nbsp;</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end">&nbsp;</div>\
												</div>');
				}
				/*Balance C/D ends here*/
				
				/*Monthly total start here*/
				dr_mnth_total = 0;
				cr_mnth_total = 0;
				$('.dr-amnt'+i).each(function(){
					dr_mnth_total = dr_mnth_total + parseFloat($(this).attr('data-dramnt'));
				});
				$('.cr-amnt'+i).each(function(){
					cr_mnth_total = cr_mnth_total + parseFloat($(this).attr('data-cramnt'));
				});
				if(dr_mnth_total > 0 && cr_mnth_total > 0) {
					$('.dr-trns-holder').append('<div class="row ledg-trns tot-ledger-row">\
													<div class="col-md-2 bd-lt bd-rt">&nbsp;</div>\
													<div class="col-md-6 bd-rt">Total</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end"><i class="fa fa-rupee"></i>&nbsp;'+dr_mnth_total+'</div>\
												</div>');
					$('.cr-trns-holder').append('<div class="row ledg-trns tot-ledger-row">\
													<div class="col-md-2 bd-rt">&nbsp;</div>\
													<div class="col-md-6 bd-rt">Total</div>\
													<div class="col-md-1 bd-rt">&nbsp;</div>\
													<div class="col-md-3 bd-rt text-end"><i class="fa fa-rupee"></i>&nbsp;'+cr_mnth_total+'</div>\
												</div>');
				}
				/*Monthly total ends here*/
			}
			/*Financial years loop ends here*/
		}
		else {
			$('.dr-trns-holder').html('<div class="row ledg-trns"><div class="col-md-12 text-center bd-lt">No data found</div></div>');
			$('.cr-trns-holder').html('<div class="row ledg-trns"><div class="col-md-12 text-center bd-lt bd-rt">No data found</div></div>');
		}
	},'json');
}
function getsubhead(acc_id) {
	if(acc_id) {
		var edit_shId = '<?php echo $subhead; ?>';
		var url = baseUrl + 'ledger/subheadlist';
		$.post(url, {acc_id}, function(data){
			$('#subheadid').html('<option value="">Select Subhead</option>');
			if(data) {
				for(var i=0; i<data.length;i++) {
					var selected = (edit_shId == data[i].sh_id ? 'selected="selected"' : '');
					$('#subheadid').append('<option value="'+data[i].sh_id+'" '+selected+'>'+data[i].sub_headname+'</option>')
				}
			}
		},'json')
	}
	else {
		$('#subheadid').html('<option value="">Select Subhead</option>');
	}
}
$(document).ready(function(){
	/*$('#open-ledger').show();
	$('#manage-ledgers').hide();*/
	ledgerlist = $('#roleslist').DataTable({
		'paging': true,
		'sort': true,
		'searching': true,
		'processing': true,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': {
			'url': baseUrl + "ledger/listledger"
		},
		'columns': [
			{ data: 'slno' },
			{ data: 'ledgername' },
			{ data: 'acctype' },
			{ data: 'subhead' },
			{ data: 'openBal' },
			{ data: 'createdon' },
			{ data: 'status' },
			{ data: 'action' }
		]
	});
	
	$('#ledger-btn').click(function(){
		var url = baseUrl + "ledger/createnew";
		$.post(url, $('#ledgerform').serialize(), function(data){
			if(data.status==1) {
				initAlert(data.respmsg, 1);
				ledgerlist.ajax.reload();
				if($('#ledger_id').val() == 0) {
					$('#ledgerform')[0].reset();
				}
			}
			else {
				initAlert(data.respmsg, 0);
				$('#ledgerform')[0].reset();
			}
		},'json');
	});
	$("body").keydown(function(e){ 
		var EsckeyCode = e.keyCode || e.which;
		if(EsckeyCode==27){
			$('#open-ledger').hide();
			$('#manage-ledgers').show();
		}
	});
	var __accId = $('#acctype').val();
	if(__accId) {
		getsubhead(__accId);
	}
});
</script>