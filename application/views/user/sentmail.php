<!DOCTYPE html>
<html>
<head>
<title>Password request</title>
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url('css/style.css') ?>" />
</head>
<body>
    <div id = "container">
        <div id = "navigation">
            <?php 
                echo $nav_text;
            ?>
        </div>

        <div id = "content">
            <?php echo validation_errors(); ?>
            <p>We've sent you an email about how to reset your password</p>
        </div>
    </div>

</body>
</html>
