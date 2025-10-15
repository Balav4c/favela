	<!-- main JS ============================================ -->
    <script src="<?php echo base_url('public/js/main.js'); ?>"></script>
	<script>
	var baseUrl = '<?php echo base_url(); ?>';
	var classname = ['f-fail', 'f-success', 'f-warning', 'f-other']; // 0-error, 1-success, 2-warning, 3-other info
	function initAlert(response,i) {
		for(var j=0;j<classname.length;j++) {
			$('.f-alert').removeClass(classname[j]);
		}
		$('.f-alert').html(response);
		$('.f-alert').addClass(classname[i]);
		$('.f-alert').slideDown();
		setTimeout(function(){
			$('.f-alert').slideUp();
		},3000);
	}
	function initDelConfirm(response,i, url, dataTable='', fnName='') {
	
		$('.f-alert').removeClass(classname[i]);
		$('.f-alert').html(response + '&nbsp;<a href="javascript:void(0)" class="confirmThis" data-confirm="yes">Yes</a>&nbsp;or&nbsp;<a href="javascript:void(0)" class="confirmThis" data-confirm="no">No</a>');
		$('.f-alert').addClass(classname[i]);
		$('.f-alert').slideDown();
		$('.confirmThis').click(function(data){
			if($(this).attr('data-confirm') == 'yes') {
				$.post(url, function(data){
					if(data == 1) {
						$('.f-alert').slideUp();
						if(dataTable) {
							dataTable.ajax.reload();
						}
						else {
							fnName('name');
						}
					}
					else {
						$('.f-alert').slideUp();
						setTimeout(function(){
							initAlert("Can't delete item. Something went wrong.", 0);
						},500);
					}
				},'json');
			}
			else {
				$('.f-alert').slideUp();
			}
		});
	}
	//Bala delete body function
	function initDelConfirmWithBody (response,i, url, body, dataTable='', fnName='',) {
		$('.f-alert').removeClass(classname[i]);
		$('.f-alert').html(response + '&nbsp;<a href="javascript:void(0)" class="confirmThis" data-confirm="yes">Yes</a>&nbsp;or&nbsp;<a href="javascript:void(0)" class="confirmThis" data-confirm="no">No</a>');
		$('.f-alert').addClass(classname[i]);
		$('.f-alert').slideDown();
		$('.confirmThis').click(function(data){
			if($(this).attr('data-confirm') == 'yes') {
				$.post(url,body, function(data){
					if(data == 1 || data?.status === "success") {
						$('.f-alert').slideUp();
						if(dataTable) {
							dataTable.ajax.reload();
						}
						else {
							fnName('name');
						}
					}
					else {
						$('.f-alert').slideUp();
						setTimeout(function(){
							initAlert("Can't delete item. Something went wrong.", 0);
						},500);
					}
				},'json');
			}
			else {
				$('.f-alert').slideUp();
			}
		});
	}
	
	
	$(document).ready(function(){
		$("body").keydown(function(e){
			var keyCode = e.keyCode || e.which;
			//alert(keyCode);
			//return false;
			if(keyCode == 112) {
				e.stopImmediatePropagation();
				e.preventDefault();
				window.location.href = baseUrl + "paymentrequest";
			}
			else if(keyCode == 117) {
				e.stopImmediatePropagation();
				e.preventDefault();
				window.location.href = baseUrl + "receipts";
			}
			else if(keyCode == 116) {
				e.stopImmediatePropagation();
				e.preventDefault();
				window.location.href = baseUrl + "payments";		
			}
			else if(keyCode == 115) {
				e.stopImmediatePropagation();
				e.preventDefault();
				window.location.href = baseUrl + "contra";		
			}
			else if(keyCode == 27) {
				$('.subhead-block').slideUp();
			}
		});
		$('.nav-item').click(function(){
			var url = $(this).attr('data-url');
			if(url) {
				window.location.href = url;
			}
		});
		$('.logo-menu').find('.nav-item').on('mouseover', function(){
			var menutitle = $(this).attr('title');
			$(this).append('<div class="menu-tooltip">'+menutitle+'</div>');
		});
		$('.logo-menu').find('.nav-item').on('mouseout', function(){
			$(this).find('.menu-tooltip').remove();
		});
	});
	</script>
</html>