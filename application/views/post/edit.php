<!DOCTYPE html>
<html>
<head>
<title>Edit Post</title>
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

            <?php echo form_open_multipart('post/edit/'.$id); ?>

                <?php echo $hidden_field; ?>

                <p>Post id <?php echo $id ?></p>

                <p>Title</p>
                <p><input type="text" name="post_title" value="<?php echo set_value('post_title', $post->title); ?>" size="50" /></p>

                <p>Image</p>
                <p>
                <?php echo "<img src = '".site_url('post/showthumb/'.$id)."'/> "; ?>
                </p>
                <p><input type="file" name="post_image" value="" size="50" /></p>

                <p>Text</p>
                <p><input type="text" name="post_text" value="<?php echo set_value('post_text', $post->text); ?>" size="50" /></p>

                <p><input type="submit" value="Submit" /></p>

            </form>
        </div>
    </div>

</body>
</html>

