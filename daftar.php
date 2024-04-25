<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Gallery</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
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
include '../functions.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_reporting(0);
    $username = $_POST["username"];
    $namaLengkap = $_POST["namaLengkap"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $alamat = $_POST["alamat"];

    $username = mysqli_real_escape_string($conn, $username);
    $namaLengkap = mysqli_real_escape_string($conn, $namaLengkap);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    $alamat = mysqli_real_escape_string($conn, $alamat);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Email sudah digunakan!',
                text: 'Mohon gunakan email lain.',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $insert_query = "INSERT INTO user (Username, NamaLengkap, Email, Password, Alamat) VALUES ('$username', '$namaLengkap', '$email', '$hashed_password', '$alamat')";
        if (mysqli_query($conn, $insert_query)) {
            $script = "
                Swal.fire({
                    icon: 'success',
                    title: 'Registrasi berhasil!',
                    text: 'Anda dapat login sekarang.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        } else {
            $script = "
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan!',
                    text: 'Gagal menambahkan pengguna.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            ";
        }
    }
}
?>

<body style="background-image:url('bg1.jpg');background-repeat;background-size:cover;background-position:center-center;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="d-flex flex-column">
                <h4 style="color: #DDA0DD; class="mt-5 text-center">Gallery - Daftar Akun</h4>
                <div class="d-flex justify-content-center">
                    <img src="../img/logo.png" class="img-fluid" width="100" alt="">
                </div>
            </div>
            <link rel="stylesheet" href="style.css">
                                    <form class="user" method="POST" action="">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username" placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="namaLengkap" placeholder="Nama Lengkap" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" placeholder="Email" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="alamat" placeholder="Alamat"></textarea>
                                        </div>
                                        <button style="background-color:#ea2ba1;border-color:black;color:white" type="submit" name="daftar" class="btn btn-primary btn-user btn-block">
                                            Daftar
                                        </button>
                                        <center>
                            <br><br>
                            <a href="login.php"style="color: #1E90FF;">Login</a>
                        </center>
                                    </form>

                            
                    </div>

                </div>

            </div>

        </div>

    </div>


    <script>
        <?php if (isset($script)) {
            echo $script;
        } ?>
    </script>

</body>

</html>