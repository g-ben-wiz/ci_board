<!DOCTYPE html>
<html>
<head>
<title>Email DNE</title>
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
            <p>Email address was not found in the database</p>
        </div>
    </div>

</body>
</html>
