<!DOCTYPE html>
<html>
<head>
<title>Banned</title>
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
            <p>You have been banned</p>
            <h3><?php echo $user->name; ?></h3>
            <p>Reason:</p>
            <p><?php echo $user->userban_reason; ?></p>

        </div>
    </div>

</body>
<html>
