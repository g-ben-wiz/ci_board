<!DOCTYPE html>
<html>
<head>
<title>
CI_Board categories
</title>

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
            <h2>Category index</h2>
            <?php
                echo '<ul>';
                foreach ($categories as $cat) {
                    echo '<li>';
                    echo "<a href = '" . site_url('category/view/'.$cat->name) . "' >";
                    echo $cat->name;
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            ?>
        </div>

    </div>

</body>

</html>

