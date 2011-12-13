<!DOCTYPE html>
<html>
<head>
<title>
<?php echo $category_name; ?>
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
            <h2><?php echo $category_name; ?></h2>
            <p><?php echo anchor('post/add/'.$category_id, 'Add Post'); ?></p>

            <ul class = 'thumblist'>
            <?php
                foreach ($cat_posts as $post) {
                    echo "<li class = 'thumblist'><a href = '" .site_url("post/view/".$post->id) . "'>";
                    echo "<img src = '" . site_url("post/showthumb/".$post->id). "'/>";
                    echo "</a></li>";
                }
            ?>
            </ul>
        </div>

    </div>

</body>

</html>
