<!DOCTYPE html>
<html>
<head>
<title>Category created</title>
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

            <p>The category <?php echo $catname ?> was created.</p>

            <p><?php echo anchor('category/add', 'Add something else'); ?></p>
            <p><?php echo anchor('category', 'View categories'); ?></p>
        </div>
    </div>

</body>
</html>
