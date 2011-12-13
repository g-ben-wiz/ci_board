<!DOCTYPE html>
<html>
<head>
<title>
View User
</title>
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

            <?php
                echo "<h2>" . $user->name . "</h2>";

                switch($user->permission_level) {
                    case -1:
                        echo "<p>banned</p>";
                        echo "<p>reason: " . $user->userban_reason . "</p>";
                        break;
                    case 0:
                        echo "<p>guest</p>";
                        break;
                    case 1:
                        echo "<p>user</p>";
                        break;
                    case 2:
                        echo "<p>administrator</p>";
                        break;
                    default:
                        break;
                }

                echo "<p>Join date " . $user->join_date. "</p>";

            ?>

        </div>
    </div>

</body>

</html>
