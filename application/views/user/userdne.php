<!DOCTYPE html>
<html>
<head>
<title>User DNE</title>
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
            <p>User not found in database</p>
            <p><?php echo anchor('user/login', 'Login again'); ?></p>
        </div>
    </div>

</body>
</html>
