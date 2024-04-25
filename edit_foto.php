<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php


// Handle form submission
if (isset($_POST['edit'])) {
    $FotoID = mysqli_real_escape_string($conn, $_POST['FotoID']);
    $albumID = mysqli_real_escape_string($conn, $_POST['AlbumID']);
    $LokasiFile = mysqli_real_escape_string($conn, $_POST['LokasiFile']);

    $query = "UPDATE Foto SET FotoID = '$FotoID', AlbumID = '$AlbumID', LokasiFile = '$LokasiFile' WHERE ForoID = '$FotoID'";

    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Foto Berhasil di Edit!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Foto Gagal Di-Edit!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    }
}


?>



<body id="page-top">

    <div id="wrapper">

        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include "topbar.php"; ?>

                <div class="container-fluid">
                    <div class="mb-3">
                        <p>
                            <a class="btn btn-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <i style="color: black;"class="fas fa-"></i> Edit Data Foto
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="albumID">Album:</label>
                                        <select name="albumID" class="form-control" required>
                                            <?php $album = mysqli_query($conn, "SELECT * FROM album"); ?>
                                            <?php foreach ($album as $al) : ?>
                                                <option value="<?= $al['AlbumID']; ?>"><?= $al['NamaAlbum']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="collapse" id="collapseExample">
                                    <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="foto">Pilih Foto untuk mengganti foto:</label>                         
                                        <input type="file" class="form-control-file" id="foto" name="foto" required>
                                        </select>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-success w-100">simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                                       
                                   
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <?php include "footer.php"; ?>

        </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "plugin.php"; ?>

    <script>
        $(document).ready(function() {
            $('#dataX').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sLast": "Terakhir",
                        "sNext": "Selanjutnya",
                        "sPrevious": "Sebelumnya"
                    },
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "sSearch": "Cari:",
                    "sEmptyTable": "Tidak ada data yang tersedia dalam tabel",
                    "sLengthMenu": "Tampilkan _MENU_ data",
                    "sZeroRecords": "Tidak ada data yang cocok dengan pencarian Anda"
                }
            });
        });
    </script>

    <script>
        <?php if (isset($script)) {
            echo $script;
        } ?>
    </script>
</body>

</html>