<!DOCTYPE html>
<html>
<head>
<title>Add Post</title>
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
            <h2>Posting in <?php echo $category_name; ?></h2>

            <?php echo validation_errors(); ?>

            <?php echo form_open_multipart('post/add/'.$category_id); ?>

                <?php echo $hidden_field; ?>

                <p>Post Title</p>
                <p><input type="text" name="post_title" value="" size="50" /></p>

                <p>Image</p>
                <p><input type="file" name="post_image" value="" size="50" /></p>

                <p>Text</p>
                <textarea name = "post_text" rows = "5" cols = "25"></textarea>

                <p><input type="submit" value="Submit" /></p>
            </form>
        </div>
    </div>

</form>

</body>
</html>
