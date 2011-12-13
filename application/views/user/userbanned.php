<!DOCTYPE html>
<html>
<head>
<title>User banned</title>
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

            <p>You have banned the user</p>
            <h3><?php echo $user->name; ?></h3>
            <p>Reason:</p>
            <p><?php echo $user->userban_reason; ?></p>

        </div>
    </div>

</body>
</html>
