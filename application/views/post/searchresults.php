<!DOCTYPE html>
<html>
<head>
<title>
<?php echo "Search results"; ?>
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
            <div id = "pager">
                <p><?php 
                    if ($is_prev) {
                        echo anchor('post/search/'.$term.'/'. ($pagenum - 1), 'Previous'); 
                    }
                    else {
                        echo anchor('post/search/'.$term.'/', 'Search'); 
                    }
                    ?>
                </p>
                <p><?php 
                    if ($is_next) {
                        echo anchor('post/search/'.$term.'/'. ($pagenum + 1), 'Next'); 
                    }
                    ?>
                </p>
            </div>

            <?php
                foreach ($results as $result) {
                    echo "<p><a href = '" . site_url("post/view/".$result->id)."'>".$result->title."</a></p>";
                }
            ?>

        </div>
    </div>

</body>
</html>
