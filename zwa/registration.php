<?php session_start(); 
require_once "header.php";
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SignUp</title>
    <script src="regValidate.js"></script>
</head>

<body>
    <div class="reg-form">
        <h2>Sign Up</h2>
        <form id="regForm" action="reg.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
            <label for="avatar">Choose Avatar*: </label>
            <input type="file" id = "avatar" name="avatar" accept="image/*">
            <label for="name">Name*: </label>
            <input tabindex="1" accesskey="j" type="text" id="name" name="name">
            <label for="surname">Surname*: </label>
            <input tabindex="2" accesskey="p" type="text" id="surname" name="surname">
            <label for="email">Email*:</label>
            <input tabindex="3" accesskey="l" type="email" id="email" name="email">
            <label for="password">Password*:</label>
            <input tabindex="4" accesskey="c" type="password" id="password" name="password">
            <label for="repeat">Repeat password*:</label>
            <input tabindex="5" accesskey="a" type="password" id="repeat" name="repeat">
            <div class="checkbox">
                <label>Select your role*:</label>
                <div class="custom-checkbox">
                    <input type="radio" id="workerRadio" name="role" value="worker">
                    <label for="workerRadio">Worker</label>
                </div>
                <div class="custom-checkbox">
                    <input type="radio" id="employerRadio" name="role" value="employer">
                    <label for="employerRadio">Employer</label>
                </div>
            </div>
            <p>* required field</p>

            <button tabindex="6" accesskey="k" id="submitButton" type="submit">Create Account</button>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                valid();
            });
        </script>
    </div>
    <?php require_once "footer.php"; ?>
</body>

</html>