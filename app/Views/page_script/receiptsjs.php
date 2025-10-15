<script>
var updown = 0;
var maxupdown = 0;
var lastinput;
var lastcreatedrow = 0;
function getThisLedger() {
	var ledgername = $('.s-active').html();
	var ledgerId = $('.s-active').attr('data-ledid');
	$(lastinput).parent().find('.hidden_ledger').val(ledgerId);
	$(lastinput).val(ledgername);
	$(lastinput).parent().parent().find('.amount-input')[0].focus();
	$('.amount-input').on('keyup', function(e){
		e.stopImmediatePropagation();
		e.preventDefault();
		var AmntkeyCode = e.keyCode || e.which;
		if(AmntkeyCode==13 && $(this).val()) {
			
			var crTotal = 0;
			var drTotal = 0;
			$('.cr_amnt').each(function(){
				if($(this).val()) {
					crTotal = parseFloat(crTotal) + parseFloat($(this).val());
				}
			});
			$('.dr_amnt').each(function(){
				if($(this).val()) {
					drTotal = parseFloat(drTotal) + parseFloat($(this).val());
				}
			});
			
			$('.dr_total').html(drTotal);
			$('.cr_total').html(crTotal);
			
			var _crtot = parseFloat($('.cr_total').html());
			var _drtot = parseFloat($('.dr_total').html());
		
			if((_crtot > _drtot) || (_drtot==0 && _crtot==0)) {
				lastcreatedrow++;
				$('.trns-details').append('<div class="row" id="row-'+lastcreatedrow+'">\
											<div class="col-md-8">\
												<span class="trntype-text">Dr</span>\
												<input type="text" class="ledger-input" name="dr_ledger[]" /> \
												<input type="hidden" id="dr_ledger_id" name="dr_ledger_id[]" class="hidden_ledger"/>\
											</div>\
											<div class="col-md-2">\
												<input type="text" class="amount-input dr_amnt" name="dr_amnt[]" />\
											</div>\
											<div class="col-md-2">&nbsp;</div>\
										</div>');
				tigger_activity();
				$('#row-'+lastcreatedrow).find('.ledger-input')[0].focus();
			}
			else {
				$('#narrate')[0].focus();
				//saveNarrate();
			}
		}
	});
}

function tigger_activity() {
	$('.ledger-input').on('keyup', function(e){
		lastinput = $(this);
		var ledname = $(this).val();
		updown = 1;
		var AcckeyCode = e.keyCode || e.which;
		if (AcckeyCode != 13){
			var url = baseUrl + "receipts/listledgers";
			$('.ledger-list-box').show();
			if(ledname) {
				$.post(url, {ledname}, function(data){
					if(data.findflag == 1) {
						$('.ledger-list-block').empty();
						var itemno = 1;
						var s_active = '';
						maxupdown = data.ledgerlist.length;
						for(var i=0;i<data.ledgerlist.length;i++) {
							s_active = (itemno == 1 ? 's-active' : '');
							$('.ledger-list-block').append('<div class="led-item '+s_active+'" data-ledid="'+data.ledgerlist[i].ld_id+'" id="led-item-'+itemno+'">'+data.ledgerlist[i].ledger_name+'</div>');
							itemno++;
						}
					}
				},'json');
			}
			else {
				$(lastinput).parent().find('.hidden_ledger').val('');
				$('.ledger-list-box').hide()
			}
		}
		else {
			$('.ledger-list-box').hide();
			getThisLedger();
		}
		if(AcckeyCode == 40 || AcckeyCode == 38) {
			$(lastinput).blur();
		}
	});
	
	$("body").keydown(function(e){ 
	
		var AcckeyCode = e.keyCode || e.which;
		//alert(AcckeyCode);
		if(AcckeyCode == 40) {
			if(updown < maxupdown) {
				updown++;
				$('.led-item').removeClass('s-active');
				$('#led-item-'+updown).addClass('s-active');
			}
		}
		else if (AcckeyCode == 38){
			if(updown>1) {
				updown--;
				$('.led-item').removeClass('s-active');
				$('#led-item-'+updown).addClass('s-active');	
			}
		}
		else if (AcckeyCode == 13){ 
			$(lastinput)[0].focus();
			$('.ledger-list-box').hide();
			getThisLedger();
			var drAmnt = $('.dr_total').html();
			var crAmng = $('.cr_total').html();
			if($('#narrate').val() !='' && crAmng!=0 && drAmnt!=0 && $('#trntype').val() == ""){
				$('.transaction-mode-block').show();
				$('#trntype')[0].focus();
				$('#trntype').on('change', function(){
					$('#trnref')[0].focus();
				});
			}
			else {
				if($('#narrate').val() !='' && crAmng!=0 && drAmnt!=0 && $('#trntype').val()) {
					$('.transaction-mode-block').hide();
					$('.acceptReceipt-block').show();
					$('#accpt_ans')[0].focus();
				}
			}
		}
		else if (AcckeyCode == 89){ 
			if($('#trntype').val() && $('#trnref').val()) {
				$('.acceptReceipt-block').hide();
				//ajax method to save receipt
				$('#trntype_hd').val($('#trntype').val());
				$('#trnref_hd').val($('#trnref').val());
				e.stopImmediatePropagation();
				e.preventDefault();
				var rurl = baseUrl + "receipts/savereceipt";
				$.post(rurl, $('#receipt').serialize(), function(data){
					if(data==1) {
						location.reload(true);
					}
				},'json');
			}
		}
		else if (AcckeyCode == 78){
			$('.acceptReceipt-block').hide();
		}
		else if(AcckeyCode == 27) {
			$('.ledger-list-box').hide();
		}
	});
}
$(document).ready(function(){
	
	var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day);
	$('.recp_date').val(today);
	$('#cr_ledger')[0].focus();
	tigger_activity();
});
</script>