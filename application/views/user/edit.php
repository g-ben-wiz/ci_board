<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>
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

            <?php echo form_open('user/edit/'.$id); ?>

                <p>User id <?php echo $id ?></p>

                <p>Username</p>
                <p><input type="text" name="name" value="<?php echo set_value('name', $name);?>" size="50" /></p>

                <p>User Password</p>
                <p><input type="password" name="password" value="" size="50" /></p>

                <p>Password Confirm</p>
                <p><input type="password" name="passconf" value="" size="50" /></p>

                <p>Email</p>
                <p><input type="text" name="email" value="<?php echo set_value('email', $email); ?>" size="50" /></p>

                 <?php 
                     if (isset($priv_str)) {

                        echo "<p>Privilege Level</p>";
                        echo "<p>";
                        echo $priv_str;
                        echo "</p> ";
                     }
                 ?>

                <p><input type="submit" value="Submit" /></p>

            </form>
        </div>
    </div>

</body>
</html>
