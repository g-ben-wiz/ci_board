<!DOCTYPE html>
<html>
<head>
<title>Ban User</title>
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

            <?php echo form_open('user/ban/'.$user->id); ?>

                <p><?php printf("You are banning user %s, user id %d", $user->name, $user->id); ?></p>

                <p>Ban Reason</p>
                <p><input type="text" name="banreason" value="" size="50" /></p>

                <p><input type="submit" value="Submit" /></p>

            </form>
        </div>
    </div>

</body>
</html>
