<?php
include("../Assets/Connection/Connection.php");
session_start();

// Fetch recipient user details
$sel = "SELECT * FROM tbl_user WHERE user_id = '".$_GET["id"]."'";
$res = $conn->query($sel);
$row = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($row["user_name"]) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .par-card {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .head-sec {
            width: 840px;
            background-color: #6c8cfc;
            padding: 17px;
            border-radius: 10px;
            border-bottom: 1px solid white;
            margin-left: 8px;
            margin-top: 8px;
            display: flex;
            gap: 10px;
        }
        .content {
            display: flex;
            gap: 10px;
            width: 840px;
        }
        .option {
            color: white;
            font-size: 20px;
        }
        .option-content {
            width: 115px;
            padding: 10px;
            background-color: #90caf9;
            border-radius: 10px;
            position: absolute;
            left: 1036px;
            margin-top: 4px;
            display: none;
            transition: transform 0.3s ease-in-out;
        }
        .option-selection {
            display: flex;
            gap: 5px;
            flex-direction: column;
            align-items: center;
        }
        .con-card {
            width: 840px;
            padding: 24px;
            border-radius: 10px;
            background-color: transparent;
            min-height: 520px;
        }
        .message-card {
            padding: 24px clusters;
            border-top: 1px solid white;
        }
        .message-box {
            padding: 10px;
            width: 750px;
            margin-left: 5px;
            border: none;
            background-color: #F5F5F5;
            border-radius: 10px;
        }
        .chat-card {
            padding: 10px;
            box-shadow: 0 0 33px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .btn {
            background-color: transparent;
            border: none;
        }
        .ic-size {
            font-size: 20px;
            color: #6c8cfc;
        }
        .font {
            color: white;
            font-size: 20px;
            font-family: 'Poor Richard', sans-serif;
        }
        .r-mess, .s-mess {
            padding: 5px;
            border-radius: 10px;
            max-width: 555px;
            width: fit-content;
            position: relative;
            cursor: pointer;
        }
        .r-mess {
            background-color: #BBDEFB;
        }
        .s-mess {
            background-color: #90caf9;
        }
        .par-r-mess {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-bottom: 5px;
        }
        .par-s-mess {
            display: flex;
            flex-direction: column;
            padding-bottom: 5px;
            align-items: flex-end;
        }
        .s-time, .r-time {
            padding: 2px;
            font-size: 9px;
        }
        .s-time {
            display: flex;
            justify-content: flex-end;
        }
        .con-width {
            min-width: 35px;
        }
        .scroll-con {
            overflow-y: scroll;
            height: 581px;
        }
        .scroll-con::-webkit-scrollbar {
            display: none;
        }
        .blur-page {
            margin-top: -10px;
            height: 100%;
            width: 99%;
            background-color: rgba(255, 255, 255, 0.729);
            position: absolute;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .profile {
            display: none;
            width: 100%;
            background-color: #ffffffed;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: absolute;
            left: 0px;
            top: 0px;
        }
        .profile-close {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .cl-ic {
            font-size: 27px;
            color: #d4d2d2;
        }
        .date-con {
            display: flex;
            justify-content: center;
            align-items: center;
            color: gray;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .delete-btn {
            display: none;
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
            color: red;
            font-size: 14px;
        }
        .r-mess:hover .delete-btn, .s-mess:hover .delete-btn {
            display: block;
        }
        .file-preview {
            margin-top: 5px;
            max-width: 200px;
        }
        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .sk-folding-cube {
            margin: 20px auto;
            width: 40px;
            height: 40px;
            position: absolute inflating;
            left: 50%;
            top: 50%;
            -webkit-transform: rotateZ(45deg);
                    transform: rotateZ(45deg);
        }
        .sk-folding-cube .sk-cube {
            float: left;
            width: 50%;
            height: 50%;
            position: relative;
            -webkit-transform: scale(1.1);
                -ms-transform: scale(1.1);
                    transform: scale(1.1); 
        }
        .sk-folding-cube .sk-cube:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #333;
            -webkit-animation: sk-foldCubeAngle 2.4s infinite linear both;
                    animation: sk-foldCubeAngle 2.4s infinite linear both;
            -webkit-transform-origin: 100% 100%;
                -ms-transform-origin: 100% 100%;
                    transform-origin: 100% 100%;
        }
        .sk-folding-cube .sk-cube2 {
            -webkit-transform: scale(1.1) rotateZ(90deg);
                    transform: scale(1.1) rotateZ(90deg);
        }
        .sk-folding-cube .sk-cube3 {
            -webkit-transform: scale(1.1) rotateZ(180deg);
                    transform: scale(1.1) rotateZ(180deg);
        }
        .sk-folding-cube .sk-cube4 {
            -webkit-transform: scale(1.1) rotateZ(270deg);
                    transform: scale(1.1) rotateZ(270deg);
        }
        .sk-folding-cube .sk-cube2:before {
            -webkit-animation-delay: 0.3s;
                    animation-delay: 0.3s;
        }
        .sk-folding-cube .sk-cube3:before {
            -webkit-animation-delay: 0.6s;
                    animation-delay: 0.6s; 
        }
        .sk-folding-cube .sk-cube4:before {
            -webkit-animation-delay: 0.9s;
                    animation-delay: 0.9s;
        }
        @-webkit-keyframes sk-foldCubeAngle {
            0%, 10% {
                -webkit-transform: perspective(140px) rotateX(-180deg);
                        transform: perspective(140px) rotateX(-180deg);
                opacity: 0; 
            } 25%, 75% {
                -webkit-transform: perspective(140px) rotateX(0deg);
                        transform: perspective(140px) rotateX(0deg);
                opacity: 1; 
            } 90%, 100% {
                -webkit-transform: perspective(140px) rotateY(180deg);
                        transform: perspective(140px) rotateY(180deg);
                opacity: 0; 
            } 
        }
        @keyframes sk-foldCubeAngle {
            0%, 10% {
                -webkit-transform: perspective(140px) rotateX(-180deg);
                        transform: perspective(140px) rotateX(-180deg);
                opacity: 0; 
            } 25%, 75% {
                -webkit-transform: perspective(140px) rotateX(0deg);
                        transform: perspective(140px) rotateX(0deg);
                opacity: 1; 
            } 90%, 100% {
                -webkit-transform: perspective(140px) rotateY(180deg);
                        transform: perspective(140px) rotateY(180deg);
                opacity: 0; 
            }
        }
    </style>
</head>
<body>
    <div class="blur-page" id="b-page">
        <div class="sk-folding-cube">
            <div class="sk-cube1 sk-cube"></div>
            <div class="sk-cube2 sk-cube"></div>
            <div class="sk-cube4 sk-cube"></div>
            <div class="sk-cube3 sk-cube"></div>
        </div>
    </div>
    <div class="profile" id="profile">
        <div>
            <div class="profile-close" onclick="close_profile()">
                <i class="fa-regular fa-circle-xmark cl-ic"></i>
            </div>
            <div>
                <img src="../Assets/Files/User/Photo/<?php echo htmlspecialchars($row["user_photo"]) ?>" width="300px" height="300px" alt="">
            </div>
        </div>
    </div>    
    <div class="par-card">
        <div class="chat-card">
            <div class="head-sec" id="changeable">
                <div class="content">
                    <div onclick="open_profile()">
                        <img src="../Assets/Files/User/Photo/<?php echo htmlspecialchars($row["user_photo"]) ?>" width="40px" height="40px" style="border-radius: 50%;" alt="">
                    </div>
                    <div class="font">
                        <?php echo htmlspecialchars($row["user_name"]) ?>
                        <input type="hidden" name="txt_id" id="txt_id" value="<?php echo $_GET["id"] ?>">
                    </div>
                </div>
                <div>
                    <button style="background-color: transparent;border: none;" onclick="handleOption()">
                        <i class="fa-solid fa-ellipsis-vertical option"></i>
                    </button>
                </div>
            </div>
            <div class="option-content" id="option-div">
                <div class="option-selection">
                    <div><a style="text-decoration: none;color: white;" onclick="clearChat()"><i class="fa-solid fa-broom"></i> Clear Chat</a></div>
                </div>
            </div>
            <div class="scroll-con" id="sc-down">
                <div class="con-card" id="conversation"></div>
            </div>
            <div class="message-card">
                <label for="photo_data">
                    <i class="fas fa-paperclip ic-size"></i>
                </label>
                <input type="file" name="photo_data" id="photo_data" style="display: none;" onchange="previewFile()">
                <input type="text" name="txt_msg" class="message-box" id="txt_msg" placeholder="Type your message..." autocomplete="off">
                <button class="btn send-btn" id="sendBtn" type="submit" onclick="sendChat('<?php echo $_GET["id"] ?>')">
                    <i class="fas fa-paper-plane ic-size"></i>
                </button>
                <div id="filePreview" class="file-preview"></div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        document.getElementById("b-page").style.display = "none";
        document.getElementById("profile").style.display = "none";
        let isSending = false;

        function sendChat(uid) {
            if (isSending) return;
            isSending = true;
            var sendBtn = document.getElementById("sendBtn");
            sendBtn.disabled = true;

            var msg = document.getElementById("txt_msg").value;
            var fileInput = document.getElementById("photo_data");
            var file = fileInput.files[0];
            if (msg.trim() === "" && !file) {
                isSending = false;
                sendBtn.disabled = false;
                return;
            }
            if (msg.length > 35) {
                alert("Character length less than 35 allowed");
                document.getElementById("txt_msg").value = "";
                isSending = false;
                sendBtn.disabled = false;
                return;
            }

            var fd = new FormData();
            fd.append("file", file);
            fd.append("uid", uid);
            fd.append("msg", msg);

            $.ajax({
                type: 'POST',
                url: "../Assets/AjaxPages/UAjaxChat.php",
                data: fd,
                processData: false,
                contentType: false,
                success: function(data) {
                    document.getElementById('txt_msg').value = "";
                    document.getElementById("photo_data").value = "";
                    document.getElementById("filePreview").innerHTML = "";
                    $('#sc-down').animate({scrollTop: $('#sc-down')[0].scrollHeight});
                    isSending = false;
                    sendBtn.disabled = false;
                },
                error: function() {
                    isSending = false;
                    sendBtn.disabled = false;
                    alert("Failed to send message");
                }
            });
        }

        function deleteMessage(chatId) {
            if (confirm("Are you sure you want to delete this message?")) {
                $.ajax({
                    url: "../Assets/AjaxPages/UAjaxChat.php?action=delete&chat_id=" + chatId,
                    success: function(result) {
                        viewChat();
                    }
                });
            }
        }

        function clearChat() {
            var id =  document.getElementById("txt_id").value;
            if (confirm("Are you sure you want to clear all messages?")) {
                document.getElementById("b-page").style.display = "block";
                $.ajax({
                    url: "../Assets/AjaxPages/UAjaxChat.php?action=clear&uid=" + id,
                    success: function(result) {
                        document.getElementById("b-page").style.display = "none";
                        alert("Chat cleared");
                        viewViewChat();
                    }
                });
            }
        }

        function viewChat() {
            var id = document.getElementById("txt_id").value;
            $.ajax({
                url: "../Assets/AjaxPages/ChatLoad.php?uid=" + id,
                success: function(data) {
                    $("#conversation").html(data);
                    $('#sc-down').animate({scrollTop: $('#sc-down')[0].scrollHeight});
                }
            });
        }

        function previewFile() {
            var file = document.getElementById("photo_data").files[0];
            var preview = document.getElementById("filePreview");
            preview.innerHTML = "";
            if (file) {
                if (file.type.startsWith('image/')) {
                    var img = document.createElement("img");
                    img.src = URL.createObjectURL(file);
                    img.className = "file-preview";
                    preview.appendChild(img);
                } else {
                    preview.innerHTML = `<p>Selected file: ${file.name}</p>`;
                }
            }
        }

        function handleOption() {
            var optionDiv = document.getElementById('option-div');
            optionDiv.style.display = optionDiv.style.display === "none" ? "block" : "none";
        }

        function close_profile() {
            document.getElementById("profile").style.display = "none";
        }

        function open_profile() {
            document.getElementById("profile").style.display = "flex";
        }

        // Attach click event to messages for deletion
        $(document).on('click', '.r-mess, .s-mess', function() {
            var chatId = $(this).data('chat-id');
            deleteMessage(chatId);
        });

        viewChat();
        setInterval(viewChat, 800);
    </script>
</body>
</html>