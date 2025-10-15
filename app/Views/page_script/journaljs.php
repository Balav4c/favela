<script>
function loadJournal(fromDate, toDate) {
	var url = baseUrl + "journals/loadjournal";
	$.post(url, {fromDate, toDate}, function(data){
		if(data) {
			$('.jrn-trnslist').empty();
			$('.dateblock').html(data.daterange);
			for(var i=0;i<data.journalData.length;i++) {
				for(var j=0;j<data.journalData[i].length;j++) {
					if(data.journalData[i][j].trn_type == 1) {
						$('.jrn-trnslist').append('<div class="row jrn-tnrs">\
												<div class="col-md-2 bd-lt bd-rt">'+data.journalData[i][j].trndate+'</div>\
												<div class="col-md-6 bd-rt">'+data.journalData[i][j].ledger_name+'&nbsp;('+data.journalData[i][j].narration+')</div>\
												<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;'+data.journalData[i][j].amount+'</div>\
												<div class="col-md-2 text-end bd-rt">&nbsp;</div>\
											</div>');
					}
					else if(data.journalData[i][j].trn_type == 2){
						$('.jrn-trnslist').append('<div class="row jrn-tnrs">\
													<div class="col-md-2 bd-lt bd-rt">&nbsp;</div>\
													<div class="col-md-6 text-end bd-rt">To '+data.journalData[i][j].ledger_name+'</div>\
													<div class="col-md-2 text-end bd-rt">&nbsp;</div>\
													<div class="col-md-2 text-end bd-rt"><i class="fa fa-rupee"></i>&nbsp;'+data.journalData[i][j].amount+'</div>\
												</div>');
					}
				}
			}
		}
	},'json');
}
$(document).ready(function(){
	/*$('#roles-btn').click(function(){
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
	});*/
	//var fDate = '<?php echo "01/".date('m/Y'); ?>';
	//var tdate = '<?php echo date("t/m/Y"); ?>';
	
	var fDate = '<?php echo date('Y-m')."-01"; ?>';
	var tdate = '<?php echo date("Y-m-t"); ?>';
	loadJournal(fDate, tdate);
	$('#jr-filter-btn').click(function(){
		var fromDate = $('#from_date').val();
		var to_date = $('#to_date').val();
		if(fromDate && to_date) {
			fromDate;
			to_date;
		}
		else {
			fromDate = fDate;
			to_date = tdate;
		}
		loadJournal(fromDate, to_date);
	});
	$('#jr-reset-btn').click(function(){
		loadJournal(fDate, tdate);
		$('#from_date').val('');
		$('#to_date').val('');
	});
});
</script>