<!DOCTYPE html>
<html>
<head>
<title>Registration</title>
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
            <p>User registered</p>
            <p><?php echo anchor('homepage', 'Home'); ?></p>
        </div>
    </div>

</body>
</html>
