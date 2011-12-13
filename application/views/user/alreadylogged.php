<!DOCTYPE html>
<html>
<head>
<title>Already logged in</title>
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
            <p>You are logged in already </p>
            <p><?php echo anchor('user/logout', 'Log out'); ?></p>
        </div>
    </div>

</body>
</html>
