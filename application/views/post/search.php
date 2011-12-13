<html>
<head>
<title>Search</title>
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

            <?php echo form_open('post/search/'); ?>

                <?php echo $hidden_field; ?>

                <p>Search Term</p>
                <p><input type="text" name="term" value="" size="50" /></p>

                <p><input type="submit" value="Submit" /></p>
            </form>
        </div>

    </div>

</body>
</html>



