<!DOCTYPE html>
<html>
<head>
<title>
<?php echo $post->title; ?>
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
            <p><?php echo $post->title; ?></p>
            <p><?php echo $op_text ; ?></p>
            <p><?php echo $post->post_date; ?></p>

            <?php
                echo $admin_text;
                echo "<p><a href = '" .site_url("post/showimage/".$id) . "'>";
                echo "<img src = '" . site_url("post/showthumb/".$id). "'/>";
                echo "</a></p>";
            ?>

            <p><?php echo $post->text; ?></p>

            <?php
                $i = 0;

                foreach ($replies as $reply) {
                    echo "<p>".$reply->title."</p>";
                    echo "<p>".$reply->post_date."</p>";

                    echo "<p>" . $reply_author_text[$i] . "</p>";

                    echo "<p><a href = '" .site_url("post/showimage/".$reply->id) . "'>";
                    echo "<img src = '" . site_url("post/showthumb/".$reply->id). "'/>";
                    echo "</a></p>";

                    echo "<p>" .$reply->text."</p>";

                    echo "<p>" . $reply_admin_text[$i] . "</p>";
                }
            ?>

            <p><?php echo anchor('post/add/'.$post->category_id, 'Add Post'); ?></p>
            <p><?php echo anchor('post/addreply/'.$id, 'Add Reply'); ?></p>

        </div>
    </div>

</body>

</html>
