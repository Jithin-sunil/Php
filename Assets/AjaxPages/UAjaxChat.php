<?php
include("../Connection/Connection.php");
session_start();

// Handle message deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $chatId = $_GET['chat_id'];
    $delQry = "DELETE FROM tbl_chat WHERE chat_id = '$chatId' AND chat_fromuid = '" . $_SESSION["uid"] . "'";
    if ($conn->query($delQry)) {
        echo "Message deleted";
    } else {
        echo "Deletion failed";
    }
    exit;
}

// Handle clear chat
if (isset($_GET['action']) && $_GET['action'] == 'clear') {
    $uid = $_GET['uid'];
    $delQry = "DELETE FROM tbl_chat WHERE (chat_fromuid = '" . $_SESSION["uid"] . "' AND chat_touid = '$uid') OR (chat_fromuid = '$uid' AND chat_touid = '" . $_SESSION["uid"] . "')";
    if ($conn->query($delQry)) {
        echo "Chat cleared";
    } else {
        echo "Clear failed";
    }
    exit;
}

// Handle message sending and file upload
if (!isset($_POST['msg']) || !isset($_POST['uid'])) {
    echo "Invalid request";
    exit;
}

$msg = trim($_POST['msg']);
$uid = $_POST['uid'];
$filePath = '';

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $allowedTypes = array_merge($allowedTypes, ['image/jpg', 'image/pjpeg', 'image/x-png', 'application/x-zip-compressed', 'application/zip']);
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    $fileType = $_FILES['file']['type'];
    $fileSize = $_FILES['file']['size'];

    if (in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize) {
        $targetDir = "../Files/Chat/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $fileName = time() . "_" . basename($_FILES["file"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            $filePath = $fileName;
        } else {
            echo "File upload failed";
            exit;
        }
    } else {
        echo "Invalid file type or size";
        exit;
    }
}

// Prevent duplicate messages
$checkQry = "SELECT * FROM tbl_chat 
             WHERE chat_fromuid = '" . $_SESSION["uid"] . "' 
             AND chat_touid = '$uid' 
             AND chat_content = '$msg' 
             AND chat_file = '$filePath' 
             AND chat_datetime > NOW() - INTERVAL 1 MINUTE";
$checkResult = $conn->query($checkQry);

if ($checkResult->num_rows == 0 && ($msg !== '' || $filePath !== '')) {
    $insQry = "INSERT INTO tbl_chat (chat_fromuid, chat_touid, chat_content, chat_file, chat_datetime) 
               VALUES ('" . $_SESSION["uid"] . "', '$uid', '$msg', '$filePath', NOW())";
    if ($conn->query($insQry)) {
        // Update or insert chat list
        $selQry = "SELECT * FROM tbl_chatlist 
                   WHERE (from_id = '" . $_SESSION['uid'] . "' OR to_id = '" . $_SESSION['uid'] . "') 
                   AND (from_id = '$uid' OR to_id = '$uid')";
        $result = $conn->query($selQry);

        if ($result->num_rows > 0) {
            $updQry = "UPDATE tbl_chatlist 
                       SET chat_content = '$msg', chat_datetime = NOW() 
                       WHERE (from_id = '" . $_SESSION['uid'] . "' OR to_id = '" . $_SESSION['uid'] . "') 
                       AND (from_id = '$uid' OR to_id = '$uid')";
            $conn->query($updQry);
        } else {
            $insQryL = "INSERT INTO tbl_chatlist (from_id, to_id, chat_content, chat_datetime, chat_type) 
                        VALUES ('" . $_SESSION['uid'] . "', '$uid', '$msg', NOW(), 'USER')";
            $conn->query($insQryL);
        }
        echo "Message sent";
    } else {
        echo "Failed to send message";
    }
} else {
    echo "Duplicate message ignored";
}
?>