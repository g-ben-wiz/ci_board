<!DOCTYPE html>
<html>
<head>
<title>Login</title>
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

            <?php echo form_open('user/login'); ?>

                <?php echo $hidden_field; ?>

                <p>Username</p>
                <p><input type="text" name="username" value="" size="50" /></p>

                <p>Password</p>
                <p><input type="password" name="password" value="" size="50" /></p>

                <p><input type="submit" value="Submit" /></p>

            </form>
        </div>
    </div>

</body>
</html>

