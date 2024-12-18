<!doctype html>
<html lang="en">
<head>
  <link rel="stylesheet" href="style.css">

  <meta charset="utf-8">
</head>
<header>
        <div class="inner">
            <h1>GoodJob</h1>
            <nav class="menu">
                <ul>
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        
                        echo '<li><a class="menu_link" href="index.php">Home</a></li>';
                        echo '<li><a class="menu_link" href="account.php">Profile</a></li>';
                        

                    } else {
                        echo '<li><a class="menu_link" href="index.php">Home</a></li>';
                        echo '<li><a class="menu_link" href="login.php">Sign in</a></li>';
                        echo '<li><a class="menu_link" href="registration.php">Sign up</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
      </header>

      </html>