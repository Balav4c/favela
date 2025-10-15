<!DOCTYPE html>
<html>
	<head>
		<title>Favela Gatepass</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo base_url('public/css/bootstrap.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('public/css/style.css'); ?>">
		<style>
		.form-group {
			margin-bottom:10px;
		}
		label {
			font-weight:bold;
		}
		body {
			background-color:#FFF !important;
		}
		</style>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="clearfix">&nbsp;</div>
					<img src="<?php echo base_url('public/img/gatepass-logo.png'); ?>" class="gatepass-logo"/>
					<div class="clearfix">&nbsp;</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Visitor Name:</label>
						<div class="form-control"><?php echo $vname; ?></div>
					</div>
					<div class="form-group">
						<label>Visitor Place:</label>
						<div class="form-control"><?php echo $vplace; ?></div>
					</div>
					<div class="form-group">
						<label>Visitor Phone:</label>
						<div class="form-control"><?php echo $vphone; ?></div>
					</div>
					<div class="form-group">
						<label>Visiting Date:</label>
						<div class="form-control"><?php echo $vdate; ?></div>
					</div>
					<div class="form-group">
						<label>Purpose of Visit:</label>
						<div class="form-control"><?php echo $vpurpose; ?></div>
					</div>
					<div class="form-group">
						<label>Person/Flat to Visit:</label>
						<div class="form-control"><?php echo $vperson; ?></div>
					</div>
				</div>
			</div>
		</div>
			
		
	</body>
	<!-- jquery ============================================ -->
    <script src="<?php echo base_url('public/js/vendor/jquery-1.11.3.min.js'); ?>"></script>
    <!-- bootstrap JS ============================================ -->
    <script src="<?php echo base_url('public/js/bootstrap.min.js'); ?>"></script>
</html>