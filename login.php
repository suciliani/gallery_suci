<!DOCTYPE html>
<html lang="en">

<head> 
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sistem Gallery</title>
    <link rel="" href="../img/logo.png" type="image/x-icon">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
</head>
<?php

session_start();
include '../functions.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_reporting(0);
    // mengambil data dari form login
    $email = $_POST["email"];
    $password = $_POST["password"];

    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM user WHERE Email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION["loggedin"] = true;
        $_SESSION["user"] = true;
        $_SESSION["UserID"] = $user['UserID'];
        $_SESSION["username"] = $user['Username'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Email atau password salah";
    }
}

?>

<body style="background-image:url('bg1.jpg');background-repeat;background-size:cover;background-position:center-center;">



    <div class="container justify-content-center">
    <link rel="stylesheet" href="style.css">
        <div class="row">
            <div class="d-flex flex-column">
            <h4 style="color: #DDA0DD;" class="d-flex justify-content-center mt-3" style="text-align: center;">Gallery - Login</h4>
                <div class="d-flex justify-content-center">
                    <img src="../img/logo.png" class="img-fluid" width="100" alt="">
                </div>
            </div>
                 <form class="user" method="POST" action="">
                        <div class="form-group">
                            <label>Email</label><br>
                                 <input type="email" class="form-control-user" name="email" placeho><br>
                        </div>
                            <div class="form-group">
                                <label>Password</label><br>
                                    <input type="password" class="form-control-user" name="password" planceho><br>
                            </div>

                        <button>Log in</button>
                        <p> Belum punya akun?
                            <a href="daftar.php"style="color: #1E90FF;">Daftar Akun</a>
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>

</html>