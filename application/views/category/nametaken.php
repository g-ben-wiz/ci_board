<!DOCTYPE html>
<html>
<head>
<title>Category taken</title>
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
            <p>This category exists already.</p>

            <p><?php echo anchor('category/add', 'Add something else'); ?></p>
        </div>
    </div>

</body>
</html>
