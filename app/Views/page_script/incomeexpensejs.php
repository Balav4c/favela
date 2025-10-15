<script>
function resetfilter() {
	$('#from_date').val('');
	$('#to_date').val('');
	loadincexp();
}
function loadincexp() {
	var fromDate = $('#from_date').val();
	var toDate = $('#to_date').val();
	var url = baseUrl + "incomevsexpense/loadIncomeExpense";
	$.post(url, {fromDate, toDate}, function(data){
		if(data) {
			var limit = (data.income.length >= data.expense.length ? data.income.length : data.expense.length);
			var total_income = 0;
			var total_expense = 0;
			$('.incexp-list').empty();
			for(var i=0; i<limit; i++) {
				if(data.income[i]) {
					$('.incexp-list').append('<div class="col-md-6">\
												<div class="row">\
													<div class="col-md-2 bd-lt bd-rt bd-bt">'+data.income[i].trndate+'</div>\
													<div class="col-md-3 bd-rt bd-bt">'+data.income[i].ledger_name+'</div>\
													<div class="col-md-5 bd-rt bd-bt">'+data.income[i].trn_narration+'</div>\
													<div class="col-md-2 text-end bd-rt bd-bt"><i class="fa fa-rupee"></i>&nbsp;'+data.income[i].trn_amount+'</div>\
												</div>\
											</div>');
					total_income = parseFloat(total_income) + parseFloat(data.income[i].trn_amount);
				}
				else {
					$('.incexp-list').append('<div class="col-md-6">\
												<div class="row">\
													<div class="col-md-2 bd-rt bd-bt">&nbsp;</div>\
													<div class="col-md-3 bd-rt bd-bt">&nbsp;</div>\
													<div class="col-md-5 bd-rt bd-bt">&nbsp;</div>\
													<div class="col-md-2 text-end bd-rt bd-bt">&nbsp;</div>\
												</div>\
											</div>')
				}
				if(data.expense[i]) {
					$('.incexp-list').append('<div class="col-md-6">\
												<div class="row">\
													<div class="col-md-2 bd-rt bd-bt">'+data.expense[i].trndate+'</div>\
													<div class="col-md-3 bd-rt bd-bt">'+data.expense[i].ledger_name+'</div>\
													<div class="col-md-5 bd-rt bd-bt">'+data.expense[i].trn_narration+'</div>\
													<div class="col-md-2 text-end bd-rt bd-bt"><i class="fa fa-rupee"></i>&nbsp;'+data.expense[i].trn_amount+'</div>\
												</div>\
											</div>');
					total_expense = parseFloat(total_expense) + parseFloat(data.expense[i].trn_amount);
				}
				else {
					$('.incexp-list').append('<div class="col-md-6">\
												<div class="row">\
													<div class="col-md-2 bd-rt bd-bt">&nbsp;</div>\
													<div class="col-md-3 bd-rt bd-bt">&nbsp;</div>\
													<div class="col-md-5 bd-rt bd-bt">&nbsp;</div>\
													<div class="col-md-2 text-end bd-rt bd-bt">&nbsp;</div>\
												</div>\
											</div>')
				}
			}
			$('.incexp-list').append('<div class="col-md-6">\
										<div class="row">\
											<div class="col-md-10 bd-lt bd-rt bd-bt"><strong>Total Income</strong></div>\
											<div class="col-md-2 text-end bd-rt bd-bt"><i class="fa fa-rupee"></i>&nbsp;'+total_income+'</div>\
										</div>\
									</div>\
									<div class="col-md-6">\
										<div class="row">\
											<div class="col-md-10 bd-rt bd-bt"><strong>Total Expense</strong></div>\
											<div class="col-md-2 text-end bd-rt bd-bt"><i class="fa fa-rupee"></i>&nbsp;'+total_expense+'</div>\
										</div>\
									</div>');
		}
	},'json');
}
$(document).ready(function(){
	loadincexp();
});
</script>