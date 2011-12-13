<!DOCTYPE html>
<html>
<head>
<title>Login failed</title>
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
            <p>Login unsuccessful</p>
            <p><?php echo anchor('user/login', 'Try again'); ?></p>
        </div>
    </div>

</body>
</html>
