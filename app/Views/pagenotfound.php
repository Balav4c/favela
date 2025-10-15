<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <title>Favela - Organize Your Residence | Version 1.0.0</title>
    <meta name="robots" content="noindex">
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('public/favicon.ico'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('public/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('public/css/login.css'); ?>">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 logo-box">
                <div class="col-md-12 logo">
                    <img src="<?php echo base_url('public/img/favela.png'); ?>" />
                </div>
            </div>
            <div class="col-md-6 col-lg-4 logo-box">
                <div class="text-center logo">
                    <img src="<?php echo base_url('public/img/keys.png'); ?>" class="key" alt="Key Icon">
                    <p class="login-head">Please Enter your Authentication Key to Log In.</p>

                    <div class="authen">

                        <form id="authForm" method="POST" action="<?php echo base_url('authenticate'); ?>">
                            <input type="text" id="authKey" name="token" placeholder="Enter your key" required>
                            <button type="submit" class="submit-btn">Access</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-12 text-center">
        &copy; 2023 Favela. All rights reserved. A product of V4C Solutions Pvt Ltd.
    </div>
</body>

</html>
