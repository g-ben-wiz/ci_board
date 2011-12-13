<!DOCTYPE html>
<html>
<head>
<title>Category deleted</title>
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
            <p>You have deleted the category <?php echo $catname; ?></p>

            <p><?php echo anchor('homepage', 'Home'); ?></p>
        </div>
    </div>

</body>
</html>
