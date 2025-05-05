<?php
include("../Connection/Connection.php");
session_start();

$selQry = "SELECT c.*, u.user_name, u.user_photo 
           FROM tbl_chat c 
           INNER JOIN tbl_user u ON u.user_id = c.chat_fromuid OR u.user_id = c.chat_touid 
           WHERE (c.chat_fromuid = '".$_SESSION["uid"]."' OR c.chat_touid = '".$_SESSION["uid"]."') 
           AND (c.chat_fromuid = '".$_GET["uid"]."' OR c.chat_touid = '".$_GET["uid"]."') 
           ORDER BY c.chat_datetime";
$rowdis = $conn->query($selQry);
$currentDate = '';
while ($datadis = $rowdis->fetch_assoc()) {
    $messageDate = date('Y-m-d', strtotime($datadis["chat_datetime"]));
    if ($messageDate != $currentDate) {
        $currentDate = $messageDate;
        echo "<div class='date-con'>" . date('M d, Y', strtotime($currentDate)) . "</div>";
    }
    
    if ($datadis["chat_fromuid"] == $_SESSION["uid"]) {
?>
<div class="par-s-mess">
    <div class="s-mess" data-chat-id="<?php echo $datadis['chat_id'] ?>">
        <div class="message-text"><?php echo htmlspecialchars($datadis["chat_content"]) ?></div>
        <?php if ($datadis["chat_file"]) { ?>
            <div class="file-preview">
                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $datadis["chat_file"])) { ?>
                    <img src="../Assets/Files/Chat/<?php echo htmlspecialchars($datadis["chat_file"]) ?>" alt="Attachment" class="file-preview">
                <?php } else { ?>
                    <a href="../Assets/Files/Chat/<?php echo htmlspecialchars($datadis["chat_file"]) ?>" target="_blank">Download: <?php echo htmlspecialchars($datadis["chat_file"]) ?></a>
                <?php } ?>
            </div>
        <?php } ?>
        <span class="delete-btn">🗑️</span>
    </div>
    <div class="s-time"><?php echo date('h:i A', strtotime($datadis["chat_datetime"])) ?></div>
</div>
<?php
    } else {
?>
<div class="par-r-mess">
    <div class="r-mess" data-chat-id="<?php echo $datadis['chat_id'] ?>">
        <div class="message-text"><?php echo htmlspecialchars($datadis["chat_content"]) ?></div>
        <?php if ($datadis["chat_file"]) { ?>
            <div class="file-preview">
                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $datadis["chat_file"])) { ?>
                    <img src="../Assets/Files/Chat/<?php echo htmlspecialchars($datadis["chat_file"]) ?>" alt="Attachment" class="file-preview">
                <?php } else { ?>
                    <a href="../Assets/Files/Chat/<?php echo htmlspecialchars($datadis["chat_file"]) ?>" target="_blank">Download: <?php echo htmlspecialchars($datadis["chat_file"]) ?></a>
                <?php } ?>
            </div>
        <?php } ?>
        <span class="delete-btn">🗑️</span>
    </div>
    <div class="r-time"><?php echo date('h:i A', strtotime($datadis["chat_datetime"])) ?></div>
</div>
<?php
    }
}
?>