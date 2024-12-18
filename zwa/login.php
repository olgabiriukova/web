<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles/style.css">

    <meta charset="utf-8" />
    <title>SignIn</title>
  </head>
<body>
    <?php require "header.php" ?>
    
    
    <div class="reg-form">
    <h2>Log In</h2>
    <form action="log.php" method="post">
        <label for="email">Email:</label>
        <input tabindex = "1" accesskey="j" type="email" id="email" name="email" required>
        <label for="password">Heslo:</label>
        <input tabindex = "2" accesskey="p" type="password" id="password" name="password" required>
        <button tabindex="3" accesskey="k" type="submit">Login</button>
    </form>
    </div>
    <?php require_once "footer.php"; ?>
</body>
</html>