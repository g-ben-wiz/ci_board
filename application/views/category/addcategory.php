<!DOCTYPE html>
<html>
<head>
<title>Add Category</title>
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

            <h2>Add Category</h2>

            <?php echo form_open('category/add'); ?>
                <?php echo $hidden_field; ?>

                <p>Category Name</p>
                <p><input type="text" name="catname" value="" size="25" /></p>
                <p><input type="submit" value="Submit" /></p>

            </form>

        </div>

    </div>

</body>
</html>
