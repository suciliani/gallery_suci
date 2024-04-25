<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php
if (isset($_POST['submit'])) {
    $judulFoto = mysqli_real_escape_string($conn, $_POST['judulFoto']);
    $deskripsiFoto = mysqli_real_escape_string($conn, $_POST['deskripsiFoto']);
    $albumID = mysqli_real_escape_string($conn, $_POST['albumID']);

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $fileTempPath = $_FILES['foto']['tmp_name'];
        $fileName = uniqid() . '-' . $_FILES['foto']['name'];
        $fileDestPath = "../foto/" . $fileName;

        if (move_uploaded_file($fileTempPath, $fileDestPath)) {
            $lokasiFile = "../foto/" . $fileName;
            $tanggalUnggah = date('Y-m-d');
            $query = "INSERT INTO foto (JudulFoto, DeskripsiFoto, TanggalUnggah, LokasiFile, AlbumID, UserID) VALUES ('$judulFoto', '$deskripsiFoto', '$tanggalUnggah', '$lokasiFile', '$albumID', '$userID')";

            if (mysqli_query($conn, $query)) {
                $script = "
                    Swal.fire({
                        icon: 'success',
                        title: 'Foto Berhasil Diunggah!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                 ";
            } else {
                $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Foto Gagal Diunggah!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                 ";
            }
        } else {
            $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memindahkan file!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                 ";
        }
    } else {
        $script = "
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memindahkan file!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                 ";
    }
}

if (isset($_POST['hapus'])) {
    $fotoID = mysqli_real_escape_string($conn, $_POST['fotoID']);

    mysqli_begin_transaction($conn);

    try {
        $delComments = "DELETE FROM komentarfoto WHERE FotoID = '$fotoID'";
        if (!mysqli_query($conn, $delComments)) {
            throw new Exception("Error deleting comments: " . mysqli_error($conn));
        }

        $delLikes = "DELETE FROM likefoto WHERE FotoID = '$fotoID'";
        if (!mysqli_query($conn, $delLikes)) {
            throw new Exception("Error deleting likes: " . mysqli_error($conn));
        }

        $query = "DELETE FROM foto WHERE FotoID = '$fotoID'";
        if (!mysqli_query($conn, $query)) {
            throw new Exception("Error deleting photo: " . mysqli_error($conn));
        }

        mysqli_commit($conn);

        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Foto Berhasil Dihapus!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } catch (Exception $e) {
        mysqli_rollback($conn);

        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Foto Gagal Di-Hapus!',
                text: '{$e->getMessage()}',
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
                                <i style="color: white;"class="fas fa-plus-square"></i> Tambah Data Foto
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="judulFoto">Judul Foto:</label>
                                        <input type="text" class="form-control" id="judulFoto" name="judulFoto" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="deskripsiFoto">Deskripsi Foto:</label>
                                        <textarea class="form-control" id="deskripsiFoto" name="deskripsiFoto" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="albumID">Album:</label>
                                        <select name="albumID" class="form-control" required>
                                            <?php $album = mysqli_query($conn, "SELECT * FROM album"); ?>
                                            <?php foreach ($album as $al) : ?>
                                                <option value="<?= $al['AlbumID']; ?>"><?= $al['NamaAlbum']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="foto">Pilih Foto:</label>
                                        <input type="file" class="form-control-file" id="foto" name="foto" required>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-success w-100">Unggah</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-secondary">Data Foto Anda</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Judul Foto</th>
                                            <th>Deskripsi Foto</th>
                                            <th>Tanggal Unggah</th>
                                            <th>Lokasi File</th>
                                            <th>Album ID</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $conn->prepare("SELECT * FROM foto WHERE UserID = '$userID'");
                                        $stmt->execute();
                                        $fotos = $stmt->get_result();
                                        ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($fotos as $data) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($data['JudulFoto']); ?></td>
                                                <td><?= htmlspecialchars($data['DeskripsiFoto']); ?></td>
                                                <td><?= htmlspecialchars($data['TanggalUnggah']); ?></td>
                                                <td><?= htmlspecialchars($data['LokasiFile']); ?></td>
                                                <td><?= htmlspecialchars($data['AlbumID']); ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#hapusModal<?= $data['FotoID'] ?>">Hapus</a>
                                                    <a href="edit_foto.php?fotoID=<?= $data['FotoID']; ?>" class="btn btn-sm btn-info">Edit</a>
                                                </td>
                                            </tr>

                                            <!-- Modal hapus foto -->
                                            <div class="modal fade" id="hapusModal<?= $data['FotoID'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus foto</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus foto ini ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-dark" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="fotoID" value="<?= $data['FotoID'] ?>">
                                                                <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
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