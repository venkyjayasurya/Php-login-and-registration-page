<?php

session_start();

include('config/dbconnect.php');

$username = $email = $password1 = $password2 = '';
$errors = array('username' => '', 'email' => '', 'password1' => '', 'password2' => '', 'passerr' => '');

if (isset($_POST['submit'])) {

    //Check each input with Regular expression and validate
    if (empty($_POST['username'])) {
        $errors['username'] = 'Username is required';
    } else {
        $username = $_POST['username'];
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $username)) {
            $errors['username'] = 'Must start with letter, 6-32 characters, Letters and numbers only';
        }
    }

    if (empty($_POST['email'])) {
        $errors['email'] = 'Email is required';
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter valid email address';
        }
    }

    if (empty($_POST['password1'])) {
        $errors['password1'] = 'Password is required!';
    } else {
        $password1 = $_POST['password1'];
        if (!preg_match('/^(?=.*[0-9])(?=.*[A-Z]).{8,20}$/', $password1)) {
            $errors['password1'] = 'Password should contain atleast 8 Characters, one uppercase letter and one number';
        }
    }

    if (empty($_POST['password2'])) {
        $errors['password2'] = 'Password is required!';
    } else {
        $password2 = $_POST['password2'];
        if (!preg_match('/^(?=.*[0-9])(?=.*[A-Z]).{8,20}$/', $password2)) {
            $errors['password2'] = 'Password should contain atleast 8 Characters, one uppercase letter and one number';
        }
    }

    if ($_POST["password1"] != $_POST["password2"]) {
        $errors['passerr'] = "*Password doesn't match!";
    }


    //insert to db
    if (array_filter($errors)) {
        foreach ($errors as $x => $x_value) {
            echo "Key=" . $x . ", Value=" . $x_value;
            echo "<br>";
        }
    } else {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password1 = mysqli_real_escape_string($conn, $_POST['password1']);
        $password2 = mysqli_real_escape_string($conn, $_POST['password2']);

        $password = md5($password1);

        //create sql query to insert
        $sql = "INSERT INTO users(username,email,password) VALUES('$username', '$email', '$password')";

        // save to db and check
        if (mysqli_query($conn, $sql)) {
            // success
            $_SESSION['username']=$username;
            header('Location: home.php');
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
</head>

<body>
    <?php include('./components/header.php'); ?>
    <div class="container">
        <div class="card">
            <!-- <h5 class="center blue-col" style="margin-bottom:30px;">Register</h6> -->
            <form action="register.php" class="form container" method="POST">
                <label for="username" class="label">Username</label>
                <input type="text" name='username' class="input" value="<?php echo htmlspecialchars($username) ?>">
                <div class="red-text"><?php echo $errors['username']; ?></div>
                <br><br>
                <label for="email" class="label">e-Mail</label>
                <input type="email" name='email' class="input" value="<?php echo htmlspecialchars($email) ?>">
                <div class="red-text"><?php echo $errors['email']; ?></div>
                <br><br>
                <label for="password1" class="label">Password</label>
                <input type="password" name='password1' class="input" value="<?php echo htmlspecialchars($password1) ?>">
                <div class="red-text"><?php echo $errors['password1']; ?></div>
                <br><br>
                <label for="password2" class="label">Confirm your password</label>
                <input type="password" name='password2' class="input" value="<?php echo htmlspecialchars($password2) ?>">
                <div class="red-text"><?php echo $errors['password2']; ?></div>

                <div class="red-text"><?php echo $errors['passerr']; ?></div>
                <br><br>

                <button type="register" name="submit" class="submit">Register!</button>

                <p>Already have an Account?<a href="login.php"><b> Login!</b></a></p>
            </form>
        </div>
    </div>

    <?php include('./components/footer.php'); ?>
</body>

</html>