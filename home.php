<?php include('./components/home_header.php');

$s_username = $_SESSION['username'];
$username = $password = '';
$errors = array('username' => '', 'password' => '');
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('location:login.php');
}

if (empty($_SESSION['username'])) {
    header('location:login.php');
}

if (isset($_POST['update'])) {

    if (empty($_POST['c_username'])) {
        $c_username = $_SESSION['username'];
    } else {
        $c_username = $_POST['c_username'];
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $c_username)) {
            $errors['username'] = 'Must start with letter, 6-32 characters, Letters and numbers only';
        }
    }

    if (empty($_POST['c_password'])) {
    } else {
        $password = $_POST['c_password'];
        if (!preg_match('/^(?=.*[0-9])(?=.*[A-Z]).{8,20}$/', $password)) {
            $errors['password'] = 'Password should contain atleast 8 Characters, one uppercase letter and one number';
        }
    }

    if (array_filter($errors)) {
    } else {
        $password = md5($password);
        $s_username = $_SESSION['username'];
        //create sql query to insert
        $sql = "UPDATE users SET username='$c_username', password='$password' where username='$s_username'";

        // save to db and check
        if (mysqli_query($conn, $sql)) {
            // success
            $_SESSION['username'] = $c_username;
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>

    <div class="container grey-text">
        <h1>Hello <b><?php echo $s_username; ?></b>!</h1>
        <div class="wrapper card" id="card_wide">
            <h5 style="margin-left: 10px;">Update your details here</h5><br>
            <form action="" method="POST">
                <div class="row">
                    <div class="col s6">
                        <label for="c_username" class="label">Username</label>
                        <input type="text" name='c_username' class="input">
                        <div class="red-text"><?php echo $errors['username']; ?></div>
                    </div>
                    <div class="col s6">
                        <label for="c_password" class="label">Password</label>
                        <input type="password" name='c_password' class="input">
                        <div class="red-text"><?php echo $errors['password']; ?></div>
                    </div>
                </div>

                <button type="update" name='update' class="submit" id="submit_cen">Update!</button>
            </form>
        </div>
    </div>

    <?php include('./components/footer.php'); ?>

</body>

</html>