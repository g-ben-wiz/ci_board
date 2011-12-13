<!DOCTYPE html>
<html>
<head>
<title>Password mailed</title>
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
            <p>The password has been mailed to your account's email address.</p>
        </div>
    </div>

</body>
</html>
