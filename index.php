<?php
//This is a login page
session_start();
include('config/dbconnect.php');

$username = $password = '';
$errors = array('username' => '', 'password' => '', 'combo_err' => '');

if (isset($_POST['login'])) {
    //Check each input with Regular expression and validate
    if (empty($_POST['username'])) {
        $errors['username'] = 'Username is required';
    } else {
        $username = $_POST['username'];
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $username)) {
            $errors['username'] = 'Must start with letter, 6-32 characters, Letters and numbers only';
        }
    }

    if (empty($_POST['password'])) {
        $errors['password'] = 'Password is required!';
    } else {
        $password = $_POST['password'];
        if (!preg_match('/^(?=.*[0-9])(?=.*[A-Z]).{8,20}$/', $password)) {
            $errors['password'] = 'Password should contain atleast 8 Characters, one uppercase letter and one number';
        }
    }

    //retrieve username and password from db
    if (array_filter($errors)) {
        //The above condition will return boolen value based on errors presence!
    } else {
        //no errors
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $password = md5($password);
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            // $_SESSION['success']='You are now logged in';
            header('location: home.php');
        } else {
            $errors['combo_err'] = '*Wrong Username/password combination!!';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
</head>

<body>
    <?php include('./components/header.php'); ?>
    <div class="container">

        <div class="card">
            <form action="" class="form container" method="POST">
                <label for="username" class="label">Username</label>
                <input type="text" name='username' class="input" value="<?php echo htmlspecialchars($username) ?>">
                <div class="red-text"><?php echo $errors['username']; ?></div>
                <br><br>

                <label for="password" class="label">Password</label>
                <input type="password" name='password' class="input" value="<?php echo htmlspecialchars($password) ?>">
                <div class="red-text"><?php echo $errors['password']; ?></div>
                <div class="red-text"><?php echo $errors['combo_err']; ?></div>
                <br><br>

                <button type="submit" name="login" class=" submit">Login!</button>

                <p>Don't have an Account?<a href="register.php"><b> Register!</b></a></p>
            </form>
        </div>
    </div>

    <?php include('./components/footer.php'); ?>
</body>

</html>