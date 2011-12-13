<html>
<head>
<title>Recover Password</title>
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

            <?php echo form_open('user/recoverpassword'); ?>

                <?php echo $hidden_field; ?>

                <p>Email Address</p>
                <p><input type="text" name="email" value="" size="50" /></p>

                <p><input type="submit" value="Submit" /></p>

            </form>

        </div>
    </div>

</body>
</html>
