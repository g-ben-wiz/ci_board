<!DOCTYPE html>
<html>
<head>
<title>Add Reply</title>
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

            <?php

                $parent_id    = $parent->id;
                $parent_title = $parent->title;
                $parent_text  = $parent->text;

            ?>

            <?php echo form_open_multipart('post/addreply/'.$parent_id); ?>

                <?php echo $hidden_field; ?>

                <p>Replying to</p>
                <?php
                    echo "<p>".$parent_title. "</p>";
                    echo "<p><a href = '" .site_url("post/showimage/".$parent_id) . "'>";
                    echo "<img src = '" . site_url("post/showthumb/".$parent_id). "'/>";
                    echo "</a></p>";
                ?>
                <p><?php echo $parent_text?></p>

                <p>Image</p>
                <p><input type="file" name="post_image" value="" size="50" /></p>

                <p>Text</p>
                <textarea name = "post_text" rows = "5" cols = "25"></textarea>

                <p><input type="submit" value="Submit" /></p>

            </form>
        </div>
    </div>

</body>
</html>
