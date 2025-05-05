<?php
include("../Connection/Connection.php");
session_start();

// Handle message deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $chatId = $_GET['chat_id'];
    $delQry = "DELETE FROM tbl_chat WHERE chat_id = '$chatId' AND (chat_fromuid = '".$_SESSION["uid"]."' OR chat_touid = '".$_SESSION["uid"]."')";
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
    $delQry = "DELETE FROM tbl_chat WHERE (chat_fromuid = '".$_SESSION["uid"]."' AND chat_touid = '$uid') OR (chat_fromuid = '$uid' AND chat_touid = '".$_SESSION["uid"]."')";
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

$msg = $_POST['msg'];
$uid = $_POST['uid'];
$filePath = '';

// Validate and handle file upload
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    if (in_array($_FILES['file']['type'], $allowedTypes) && $_FILES['file']['size'] <= $maxFileSize) {
        $targetDir = "../Assets/Files/Chat/";
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

// Insert message only if not a duplicate
$checkQry = "SELECT * FROM tbl_chat WHERE chat_fromuid = '".$_SESSION["uid"]."' AND chat_touid = '$uid' AND chat_content = '$msg' AND chat_file = '$filePath' AND chat_datetime = DATE_FORMAT(sysdate(), '%M %d %Y, %h:%i %p')";
$checkResult = $conn->query($checkQry);
if ($checkResult->num_rows == 0) {
    $insQry = "INSERT INTO tbl_chat (chat_fromuid, chat_touid, chat_content, chat_file, chat_datetime) 
               VALUES ('".$_SESSION["uid"]."', '$uid', '$msg', '$filePath', DATE_FORMAT(sysdate(), '%M %d %Y, %h:%i %p'))";
    if ($conn->query($insQry)) {
        echo "sended";
    } else {
        echo "failed";
        exit;
    }

    // Update or insert chat list
    $selQry = "SELECT * FROM tbl_chatlist WHERE (from_id='".$_SESSION['uid']."' OR to_id='".$_SESSION['uid']."') 
               AND (from_id='$uid' OR to_id='$uid')";
    $result = $conn->query($selQry);
    if ($result->num_rows > 0) {
        $updQry = "UPDATE tbl_chatlist SET chat_content='$msg', chat_datetime=CURRENT_TIMESTAMP() 
                   WHERE (from_id='".$_SESSION['uid']."' OR to_id='".$_SESSION['uid']."') AND (from_id='$uid' OR to_id='$uid')";
        if ($conn->query($updQry)) {
            echo "List Updated";
        } else {
            echo "List Updation failed";
        }
    } else {
        $insQryL = "INSERT INTO tbl_chatlist(from_id, to_id, chat_content, chat_datetime, chat_type) 
                    VALUES ('".$_SESSION['uid']."', '$uid', '$msg', CURRENT_TIMESTAMP(), 'USER')";
        if ($conn->query($insQryL)) {
            echo "List Inserted";
        } else {
            echo "List Insertion Failed";
        }
    }
} else {
    echo "Duplicate message ignored";
}
?>