<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi user
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(empty($email)) {
        header('location: /login');
        $_SESSION["warning"] = "Email can't be empty";
        exit;
    }
    if(!preg_match('/^[a-zA-Z0-9_@.]+$/', $email)) {
        header('location: /login');
        $_SESSION["warning"] = "Email can only contain letters, numbers, dot, and underscores.";
        exit;
    }

    if(empty($password)) {
        header('location: /login');
        $_SESSION["warning"] = "Password can't be empty";
        exit;
    }
    if(!preg_match('/^[a-zA-Z0-9_]+$/', $password)) {
        header('location: /login');
        $_SESSION["warning"] = "Password can only contain letters, numbers, and underscores.";
        exit;
    }

    $sql = "SELECT id, password FROM users WHERE email = ?";
    require_once "../config.php";
    if(($statement = mysqli_prepare($link, $sql)) === false) {
        header("location: /login");
        $_SESSION["warning"] = "Internal error";
        exit;
    }
    mysqli_stmt_bind_param($statement, "s", $email);
    if (!mysqli_stmt_execute($statement)) {
        header("location: /login");
        $_SESSION["warning"] = "Internal error";
        mysqli_stmt_close($statement);
        exit;
    }
    mysqli_stmt_store_result($statement);

    if(mysqli_stmt_num_rows($statement) != 1) {
        header("location: /login");
        $_SESSION["warning"] = "Wrong email or password";
        mysqli_stmt_close($statement);
        exit;
    }
    $hashed_password = "";
    $id = "";
    mysqli_stmt_bind_result($statement, $id, $hashed_password);
    if (!mysqli_stmt_fetch($statement) || !password_verify($password, $hashed_password)) {
        header("location: /login");
        $_SESSION["warning"] = "Wrong email or password";
        mysqli_stmt_close($statement);
        exit;
    }
    $_SESSION["loggedin"] = true;
    $_SESSION["id"] = $id;
    header("location: /");

    mysqli_stmt_close($statement);
    exit;
}

if ($_SESSION["loggedin"] ?? false) {
    header('location: /');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Login</title>
</head>
<body class="vh-100 d-flex align-items-center justify-content-center">
    <form style="min-width: 360px;" method="post" class="bg-light py-3 px-4 needs-validation" novalidate>
        <h3>Login</h3>
        <?php
            if (isset($_SESSION['success_msg'])) {
                echo '<div class="alert alert-success" role="alert">'. $_SESSION['success_msg'] .'</div>';
                unset($_SESSION['success_msg']);
            }
            if (isset($_SESSION['warning'])) {
                echo '<div class="alert alert-danger" role="alert">'. $_SESSION['warning'] .'</div>';
                unset($_SESSION['warning']);
            }
        ?>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
            <div class="invalid-feedback">
                Please enter valid email.
            </div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
            <div class="invalid-feedback">
                Please enter password.
            </div>
        </div>
        <div class="mb-3">
            <small>Don't have an account? <a href="/register" class="text-decoration-none">Register</a></small>
        </div>
        <div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </div>
    </form>
    
    <script>
        (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

                    
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                console.log(form)
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>