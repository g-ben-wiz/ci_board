<!DOCTYPE html>
<html>
<head>
<title>Edit Category</title>
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

            <?php echo form_open('category/edit/'.$id); ?>
                <?php echo $hidden_field; ?>

                <p>Category Name</p>
                <p><input type="text" name="catname" value="<?php echo set_value('catname', $catname); ?>" size="25" /></p>

                <p><input type="submit" value="Submit" /></p>

            </form>
        </div>
    </div>

</body>
</html>

