<?php
session_start();
include '../functions.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['fotoID'])) {
    $fotoID = $data['fotoID'];
    $userID = $_SESSION['UserID'];

    $checkLike = $conn->prepare("SELECT * FROM likefoto WHERE UserID = ? AND FotoID = ?");
    $checkLike->bind_param("ii", $userID, $fotoID);
    $checkLike->execute();
    if ($checkLike->get_result()->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO likefoto (UserID, FotoID) VALUES (?, ?)");
        $stmt->bind_param("ii", $userID, $fotoID);
        if ($stmt->execute()) {
            $likeCount = $conn->prepare("SELECT COUNT(*) AS likes FROM likefoto WHERE FotoID = ?");
            $likeCount->bind_param("i", $fotoID);
            $likeCount->execute();
            $likes = $likeCount->get_result()->fetch_assoc();
            echo json_encode(['success' => true, 'likes' => $likes['likes']]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Already liked']);
    }
}
