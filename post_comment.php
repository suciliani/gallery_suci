<?php
session_start();
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fotoID'], $_POST['komentar'])) {
    $fotoID = $_POST['fotoID'];
    $komentar = $_POST['komentar'];
    $userID = $_SESSION['UserID'];

    $stmt = $conn->prepare("INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $fotoID, $userID, $komentar);
    if ($stmt->execute()) {
        header('Location: index.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
