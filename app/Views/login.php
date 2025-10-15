
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
            <div class="col-md-6 login">
                <div class="col-md-9 login-box">
                    <div class="col-md-12 text-center">
                        <h3>Hi <?php echo $organisation; ?></h3>
                        <h4>Welcome Back!</h4>
                        <p>Login to your account with registered Email Id.</p>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <form method="post" name="login-form" id="login-form">
                        <span id="alertbox"></span>
                        <div class="form-group">
                            <label for="email">Email Id:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input type="password" class="form-control" id="pwd" name="pwd">
                        </div>
                        <div class="g-recaptcha brochure__form__captcha"
                            data-sitekey="6LeoL5UpAAAAABy-sNgzr_XHc2vWl2Kpr45VHWey"></div>
                        <!-- <div class="checkbox">
                            <label><input type="checkbox"> Remember me</label>
                        </div> -->
                        <div class="remember-me d-flex align-items-center justify-content-center">
                        <input type="checkbox" id="rememberMe" class="me-2">
                        <label for="rememberMe" class="text-muted">Don't ask me on this computer for 3 months</label>
                    </div>
                        <button type="button" class="btn btn-primary col-md-12 signin">Sign In</button>
                        <div class="form-group">
                            <input type="hidden" name="identifier" value="<?php echo $identifier; ?>" />
                            <div class="clearfix">&nbsp;</div>
                            <p>Cant access your account? <a href="#">Forgot password?</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-center">
        &copy; <?php echo date('Y'); ?> Favela. All rights reserved. A product of V4C Solutions Pvt Ltd.
    </div>
</body>
<script src="<?php echo base_url('public/js/vendor/jquery-1.11.3.min.js'); ?>"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
function authenticate() {
    var url = '<?php echo base_url('login/authinticate'); ?>';
    $.post(url, $('#login-form').serialize(), function(data) {

        if (data.status == 1) {
            window.location.href = "<?php echo base_url('dashboard'); ?>";
        } else {
            $('#alertbox').html('<div class="' + data.class + '">' + data.msg + '</div>');
            setTimeout(function() {
                $('#alertbox').empty();
            }, 5000);
        }
    }, 'json');
}
$(document).ready(function() {

    $('.signin').click(function() {
        authenticate()
    });

    $(document).on('keypress', function(e) {
        if (e.which == 13) {
            authenticate();
        }
    });
});
</script>

</html>